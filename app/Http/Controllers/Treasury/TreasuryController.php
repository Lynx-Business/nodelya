<?php

namespace App\Http\Controllers\Treasury;

use App\Data\Treasury\TreasuryIndexProps;
use App\Data\Treasury\TreasuryIndexRequest;
use App\Data\Treasury\TreasuryMonthResource;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\ExpenseBudget;
use App\Models\ExpenseCharge;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;

class TreasuryController extends Controller
{
    public function index(TreasuryIndexRequest $data)
    {
        return Inertia::render('treasury/Index', TreasuryIndexProps::from([
            'request' => $data,
            'months'  => Lazy::inertia(
                function () use ($data) {
                    /** @var \Illuminate\Database\Eloquent\Collection<int, Deal> $billingDeals */
                    $billingDeals = Deal::query()
                        ->billing()
                        ->where(
                            fn (Builder $q) => $q
                                ->whereBetween('starts_at', [$data->starts_at, $data->ends_at])
                                ->orWhereBetween('ends_at', [$data->starts_at, $data->ends_at]),
                        )
                        ->get();

                    /** @var \Illuminate\Database\Eloquent\Collection<int, Deal> $commercialDeals */
                    $commercialDeals = Deal::query()
                        ->commercial()
                        ->where(
                            fn (Builder $q) => $q
                                ->whereBetween('starts_at', [$data->starts_at, $data->ends_at])
                                ->orWhereBetween('ends_at', [$data->starts_at, $data->ends_at]),
                        )
                        ->get();

                    return collect()
                        ->range(0, floor($data->starts_at->diffInMonths($data->ends_at, absolute: true)))
                        ->map(fn (int $i) => $data->starts_at->copy()->addMonths($i)->startOfMonth())
                        ->map(function (Carbon $month) use ($billingDeals, $commercialDeals) {
                            $treasuryMonth = new TreasuryMonthResource($month);

                            $treasuryMonth->real_amount += $billingDeals
                                ->filter(fn (Deal $deal) => $deal->starts_at < $month && $deal->ends_at > $month)
                                ->sum(
                                    fn (Deal $deal) => collect($deal->monthly_expenses)
                                        ->where('date', $month)
                                        ->sum('amount'),
                                );
                            $treasuryMonth->real_amount -= ExpenseBudget::query()
                                ->withWhereHas(
                                    'accountingPeriod',
                                    fn (Builder|BelongsTo $q) => $q
                                        ->where([
                                            ['starts_at', '<=', $month],
                                            ['ends_at', '>=', $month],
                                        ]),
                                )
                                ->whereDoesntHaveRelation('expenseItem.expenseCharges', 'charged_at', $month)
                                ->get()
                                ->sum('monthly_amount');
                            $treasuryMonth->real_amount -= Services::conversion()->centsToPrice(
                                ExpenseCharge::query()
                                    ->where('charged_at', $month)
                                    ->sum('amount_in_cents'),
                            );

                            $treasuryMonth->planned_amount += $treasuryMonth->real_amount + $commercialDeals
                                ->filter(fn (Deal $deal) => $deal->starts_at < $month && $deal->ends_at > $month)
                                ->sum(
                                    fn (Deal $deal) => collect($deal->monthly_expenses)
                                        ->where('date', $month)
                                        ->sum('amount') * $deal->success_rate / 100,
                                );

                            return $treasuryMonth;
                        });
                },
            ),
        ]));
    }
}
