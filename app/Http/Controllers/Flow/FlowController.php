<?php

namespace App\Http\Controllers\Flow;

use App\Data\Flow\Form\FlowFormProps;
use App\Data\Flow\Form\FlowFormRequest;
use App\Data\Flow\Index\FlowIndexProps;
use App\Data\Flow\Index\FlowIndexRequest;
use App\Enums\Charge\ChargeFrequency;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\FlowCategory;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;

class FlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FlowIndexRequest $data)
    {
        $accountingPeriod = $data->accounting_period;
        $months = [];

        if ($accountingPeriod) {
            $period = CarbonPeriod::create($accountingPeriod->starts_at, '1 month', $accountingPeriod->ends_at);
            foreach ($period as $date) {
                $months[] = $date->format('Y-m');
            }
        }

        $tableData = [
            [
                'name'   => __('models.flow.name'),
                'values' => [],
            ],
        ];

        if ($accountingPeriod) {
            $deals = Deal::billing()
                ->whereInAccountingPeriod($accountingPeriod->id)
                ->get();

            $billingTotals = array_fill_keys($months, 0);

            foreach ($deals as $deal) {
                $schedules = $deal->schedule;

                if ($schedules) {
                    foreach ($schedules as $schedule) {
                        foreach ($schedule->data as $item) {
                            $monthKey = $item->date->format('Y-m');

                            if (isset($billingTotals[$monthKey])) {
                                $billingTotals[$monthKey] += $item->amount;
                            }
                        }
                    }
                }
            }

            $tableData[0]['values'] = $billingTotals;

            $categories = FlowCategory::with(['flowCharges' => function ($query) use ($accountingPeriod) {
                $query->whereInAccountingPeriod($accountingPeriod->id);
            }])->get();

            foreach ($categories as $category) {
                $categoryTotals = array_fill_keys($months, 0);

                foreach ($category->flowCharges as $charge) {
                    $monthKey = $charge->charged_at->format('Y-m');

                    if (isset($categoryTotals[$monthKey])) {
                        $categoryTotals[$monthKey] += $charge->amount;
                    }
                }

                $tableData[] = [
                    'name'   => $category->name,
                    'values' => $categoryTotals,
                ];
            }
        }

        return Inertia::render('flows/Index', FlowIndexProps::from([
            'request'                  => $data,
            'table_data'               => Lazy::inertia(fn () => $tableData),
            'accounting_period_months' => $months,
            'trashed_filters'          => Lazy::inertia(fn () => TrashedFilter::labels()),
            'accountingPeriods'        => Lazy::inertia(fn () => Services::accountingPeriod()->list()),
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('flows/Create', FlowFormProps::from([
            'charge_frequency' => Lazy::inertia(fn () => ChargeFrequency::labels()),
            'flowCategories'   => Lazy::inertia(
                fn () => Services::flow()->categoriesList(),
            ),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FlowFormRequest $data)
    {

        $charges = Services::flow()->createOrUpdateCharge->execute($data);

        if (is_null($charges)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.flows.store.success'));

        return to_route('flows.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
