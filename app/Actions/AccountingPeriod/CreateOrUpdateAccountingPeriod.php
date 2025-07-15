<?php

namespace App\Actions\AccountingPeriod;

use App\Data\AccountingPeriod\Form\AccountingPeriodFormRequest;
use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\Contractor;
use App\Models\Employee;
use App\Models\ExpenseBudget;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateAccountingPeriod
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(AccountingPeriodFormRequest $data): ?AccountingPeriod
    {
        DB::beginTransaction();

        try {
            $accountingPeriod = $data->accounting_period;
            if (! $accountingPeriod) {
                $accountingPeriod = Services::team()->forTeam(
                    $data->team,
                    function (Team $team) use ($data) {
                        $accountingPeriod = $team->accountingPeriods()->create($data->toArray());

                        $currentAccountingPeriod = $team->currentAccountingPeriod;
                        if (! $currentAccountingPeriod) {
                            // Impossible to copy budgets if there is no current accounting period
                            return $accountingPeriod;
                        }

                        if ($data->keep_general_expense_budgets) {
                            $budgets = $team->expenseBudgets()
                                ->whereBelongsToAccountingPeriod($currentAccountingPeriod)
                                ->whereType(ExpenseType::GENERAL)
                                ->get();
                            $this->keepExpenseBudgets($team, $accountingPeriod, $budgets);
                        }
                        if ($data->keep_employee_expense_budgets) {
                            $budgets = $team->expenseBudgets()
                                ->whereBelongsToAccountingPeriod($currentAccountingPeriod)
                                ->whereType(ExpenseType::EMPLOYEE)
                                ->whereHasMorph('model', [Employee::class], fn (Builder $q) => $q->whereBelongsToAccountingPeriod($accountingPeriod))
                                ->get();
                            $this->keepExpenseBudgets($team, $accountingPeriod, $budgets);
                        }
                        if ($data->keep_contractor_expense_budgets) {
                            $budgets = $team->expenseBudgets()
                                ->whereBelongsToAccountingPeriod($currentAccountingPeriod)
                                ->whereType(ExpenseType::CONTRACTOR)
                                ->whereHasMorph('model', [Contractor::class], fn (Builder $q) => $q->whereBelongsToAccountingPeriod($accountingPeriod))
                                ->get();
                            $this->keepExpenseBudgets($team, $accountingPeriod, $budgets);
                        }

                        return $accountingPeriod;
                    },
                );

            } else {
                $accountingPeriod->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $accountingPeriod;
    }

    private function keepExpenseBudgets(Team $team, AccountingPeriod $accountingPeriod, Collection $budgets): void
    {
        $team->expenseBudgets()->createMany(
            $budgets->map(
                fn (ExpenseBudget $budget) => [
                    'expense_item_id' => $budget->expense_item_id,
                    'model_type'      => $budget->model_type,
                    'model_id'        => $budget->model_id,
                    'amount_in_cents' => $budget->amount_in_cents,
                    'starts_at'       => $accountingPeriod->starts_at,
                    'ends_at'         => $accountingPeriod->ends_at,
                ],
            ),
        );
    }
}
