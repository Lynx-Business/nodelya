<?php

namespace App\Console\Commands\Dev;

use App\Enums\Expense\ExpenseType;
use App\Enums\Role\RoleName;
use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\Banner;
use App\Models\Contractor;
use App\Models\Employee;
use App\Models\ExpenseBudget;
use App\Models\ExpenseCategory;
use App\Models\ExpenseCharge;
use App\Models\ExpenseItem;
use App\Models\ExpenseSubCategory;
use App\Models\ProjectDepartment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class DevInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the dev environment';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->call('clear-compiled');
        $this->call('optimize:clear');
        if (! config('app.key')) {
            $this->call('key:generate');
        }
        if (! File::exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        if (app()->isProduction()) {
            alert('Database will be ignored in production');

            return self::SUCCESS;
        }

        if (confirm('Reset database ?')) {
            $this->call('migrate:fresh');
        } else {
            $this->call('migrate');
        }
        $this->call('db:seed');

        if (! File::exists(base_path('_ide_helper.php'))) {
            $this->call('ide-helper:generate');
        }

        if (confirm('Generate fake data ?')) {
            $count = text(
                label: 'How many items to create ?',
                default: 10,
                validate: fn (string $value) => match (true) {
                    intval($value) < 1 => 'Given value must be a positive integer',
                    default            => null
                },
            );

            Banner::factory()
                ->count($count)
                ->create();

            $users = User::factory()
                ->count($count)
                ->sequence(fn (Sequence $sequence) => ['email' => "owner-{$sequence->index}@app.com"])
                ->create();

            /** @var User $user */
            foreach ($users as $user) {
                Services::team()->forTeam(
                    $user->team,
                    function (Team $team) use ($count, $user) {
                        $team->update([
                            'creator_id' => $user->id,
                        ]);
                        $user->assignRole(RoleName::OWNER);

                        ProjectDepartment::factory()
                            ->count($count)
                            ->recycle($team)
                            ->create();

                        foreach (ExpenseType::cases() as $type) {
                            $categories = ExpenseCategory::factory()
                                ->count($count)
                                ->recycle($team)
                                ->create([
                                    'type' => $type,
                                ]);
                            foreach ($categories as $category) {
                                ExpenseSubCategory::factory()
                                    ->count(2)
                                    ->recycle($team)
                                    ->recycle($category)
                                    ->has(
                                        ExpenseItem::factory()
                                            ->count(2)
                                            ->recycle($team),
                                    )->create();
                            }
                        }

                        $contractors = Contractor::factory()
                            ->count($count)
                            ->recycle($team)
                            ->state(fn () => [
                                'project_department_id' => ProjectDepartment::query()
                                    ->inRandomOrder()
                                    ->first()->id,
                            ])
                            ->create();

                        $employees = Employee::factory()
                            ->count($count)
                            ->recycle($team)
                            ->state(fn () => [
                                'project_department_id' => ProjectDepartment::query()
                                    ->inRandomOrder()
                                    ->first()->id,
                            ])
                            ->create();

                        for ($i = 0; $i < 3; $i++) {
                            $accountingPeriod = AccountingPeriod::factory()
                                ->recycle($team)
                                ->create([
                                    'starts_at' => now()->subYears($i)->startOfDay(),
                                ]);

                            ExpenseBudget::factory()
                                ->count($count)
                                ->recycle($team)
                                ->forAccountingPeriod($accountingPeriod)
                                ->state(fn () => [
                                    'expense_item_id' => ExpenseItem::query()
                                        ->whereType(ExpenseType::GENERAL)
                                        ->inRandomOrder()
                                        ->first()->id,
                                ])
                                ->create();

                            ExpenseCharge::factory()
                                ->count($count)
                                ->recycle($team)
                                ->forAccountingPeriod($accountingPeriod)
                                ->state(fn () => [
                                    'expense_item_id' => ExpenseItem::query()
                                        ->whereType(ExpenseType::GENERAL)
                                        ->inRandomOrder()
                                        ->first()->id,
                                ])
                                ->create();

                            foreach ($employees as $employee) {
                                ExpenseBudget::factory()
                                    ->count($count)
                                    ->recycle($team)
                                    ->forAccountingPeriod($accountingPeriod)
                                    ->state(fn () => [
                                        'model_type'      => ExpenseType::EMPLOYEE->toMorphType(),
                                        'model_id'        => $employee->id,
                                        'expense_item_id' => ExpenseItem::query()
                                            ->whereType(ExpenseType::EMPLOYEE)
                                            ->inRandomOrder()
                                            ->first()->id,
                                    ])
                                    ->create();

                                ExpenseCharge::factory()
                                    ->count($count)
                                    ->recycle($team)
                                    ->forAccountingPeriod($accountingPeriod)
                                    ->state(fn () => [
                                        'model_type'      => ExpenseType::EMPLOYEE->toMorphType(),
                                        'model_id'        => $employee->id,
                                        'expense_item_id' => ExpenseItem::query()
                                            ->whereType(ExpenseType::EMPLOYEE)
                                            ->inRandomOrder()
                                            ->first()->id,
                                    ])
                                    ->create();
                            }

                            foreach ($contractors as $contractor) {
                                ExpenseBudget::factory()
                                    ->count($count)
                                    ->recycle($team)
                                    ->forAccountingPeriod($accountingPeriod)
                                    ->state(fn () => [
                                        'model_type'      => ExpenseType::CONTRACTOR->toMorphType(),
                                        'model_id'        => $contractor->id,
                                        'expense_item_id' => ExpenseItem::query()
                                            ->whereType(ExpenseType::CONTRACTOR)
                                            ->inRandomOrder()
                                            ->first()->id,
                                    ])
                                    ->create();

                                ExpenseCharge::factory()
                                    ->count($count)
                                    ->recycle($team)
                                    ->forAccountingPeriod($accountingPeriod)
                                    ->state(fn () => [
                                        'model_type'      => ExpenseType::CONTRACTOR->toMorphType(),
                                        'model_id'        => $contractor->id,
                                        'expense_item_id' => ExpenseItem::query()
                                            ->whereType(ExpenseType::CONTRACTOR)
                                            ->inRandomOrder()
                                            ->first()->id,
                                    ])
                                    ->create();
                            }
                        }
                    },
                );
            }
        }

        return self::SUCCESS;
    }
}
