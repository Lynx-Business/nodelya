<?php

namespace App\Actions\AccountingPeriod;

use App\Data\AccountingPeriod\Form\AccountingPeriodFormRequest;
use App\Models\AccountingPeriod;
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
                $accountingPeriod = $data->team->accountingPeriods()->create($data->toArray());
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
}
