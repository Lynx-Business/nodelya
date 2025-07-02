<?php

namespace App\Console\Commands\Dev;

use App\Enums\Expense\ExpenseType;
use App\Enums\Role\RoleName;
use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\Banner;
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

                        AccountingPeriod::factory()
                            ->count($count)
                            ->recycle($team)
                            ->create();
                        ProjectDepartment::factory()
                            ->count($count)
                            ->recycle($team)
                            ->create();

                        $categories = ExpenseCategory::factory()
                            ->count($count)
                            ->recycle($team)
                            ->create([
                                'type' => ExpenseType::GENERAL,
                            ]);
                        foreach ($categories as $category) {
                            ExpenseSubCategory::factory()
                                ->count(2)
                                ->recycle($team)
                                ->recycle($category)
                                ->has(
                                    ExpenseItem::factory()
                                        ->count(2)
                                        ->recycle($team)
                                        ->has(
                                            ExpenseBudget::factory()
                                                ->count(2)
                                                ->recycle($team),
                                        )
                                        ->has(
                                            ExpenseCharge::factory()
                                                ->count(2)
                                                ->recycle($team),
                                        ),
                                )->create();
                        }
                    },
                );
            }
        }

        return self::SUCCESS;
    }
}
