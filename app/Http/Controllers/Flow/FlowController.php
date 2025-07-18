<?php

namespace App\Http\Controllers\Flow;

use App\Data\Flow\FlowResource;
use App\Data\Flow\Form\FlowFormProps;
use App\Data\Flow\Form\FlowFormRequest;
use App\Data\Flow\Index\FlowIndexProps;
use App\Data\Flow\Index\FlowIndexRequest;
use App\Enums\Charge\ChargeFrequency;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\FlowCategory;
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
        $months = $accountingPeriod?->months->map(fn ($m) => $m->format('Y-m'))->toArray() ?? [];
        $tableData = [];

        if ($accountingPeriod) {

            $tableData[] = FlowResource::billing($accountingPeriod);
            $tableData[] = FlowResource::expenseCharges($accountingPeriod);

            FlowCategory::with(['flowCharges' => function ($query) use ($accountingPeriod) {
                $query->whereBetween('charged_at', [
                    $accountingPeriod->starts_at,
                    $accountingPeriod->ends_at,
                ]);
            }])->get()->each(function ($category) use (&$tableData, $accountingPeriod) {
                $tableData[] = FlowResource::flowCategory($category, $accountingPeriod);
            });
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
