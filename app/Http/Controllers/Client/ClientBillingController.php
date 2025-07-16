<?php

namespace App\Http\Controllers\Client;

use App\Data\Client\ClientResource;
use App\Data\Deal\Billing\Form\BillingDealFormProps;
use App\Data\Deal\Billing\Form\BillingDealFormRequest;
use App\Data\Deal\Billing\Index\BillingDealIndexProps;
use App\Data\Deal\Billing\Index\BillingDealIndexRequest;
use App\Data\Deal\DealOneOrManyRequest;
use App\Data\Deal\DealResource;
use App\Enums\Deal\DealScheduleStatus;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ClientBillingController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(BillingDealIndexRequest $data, Client $client)
    {
        $months = [];

        $data->client_ids = [$client->id];
        $accountingPeriod = $data->accounting_period;

        $months = [];
        if ($accountingPeriod) {
            $period = CarbonPeriod::create($accountingPeriod->starts_at, '1 month', $accountingPeriod->ends_at);
            foreach ($period as $date) {
                $months[] = $date->format('Y-m');
            }
        }

        return Inertia::render('clients/billing/Index', BillingDealIndexProps::from([
            'request'       => $data,
            'client'        => ClientResource::from($client),
            'billing_deals' => Lazy::inertia(
                function () use ($data, $accountingPeriod) {
                    $paginatedDeals = $data->toQuery()
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->with(['client'])
                        ->paginate(
                            perPage: $data->per_page ?? Config::integer('default.per_page'),
                            page: $data->page ?? 1,
                        )
                        ->withQueryString();

                    $resources = DealResource::collect($paginatedDeals, PaginatedDataCollection::class)->include('monthly_expenses', 'can_view', 'can_update', 'can_trash', 'can_restore', 'can_delete');

                    $resources->through(function ($resource) use ($accountingPeriod) {
                        if (isset($resource->monthly_expenses) && is_array($resource->monthly_expenses) && $accountingPeriod) {
                            $resource->monthly_expenses = array_filter(
                                $resource->monthly_expenses ?? null,
                                function ($expense) use ($accountingPeriod) {
                                    return $expense->date?->between(
                                        $accountingPeriod->starts_at,
                                        $accountingPeriod->ends_at,
                                    );
                                },
                            );
                            $resource->monthly_expenses = array_values($resource->monthly_expenses);
                        }

                        return $resource;
                    });

                    return $resources;
                },
            ),
            'accounting_period_months' => $months,
            'trashed_filters'          => Lazy::inertia(fn () => TrashedFilter::labels()),
            'accountingPeriods'        => Lazy::inertia(fn () => Services::accountingPeriod()->list()),
        ]));
    }

    public function edit(Client $client, Deal $deal)
    {

        return Inertia::render('clients/billing/Edit', BillingDealFormProps::from([
            'deal' => DealResource::from(
                $deal->load('client', 'parent', 'projectDepartment'),
            )->include('schedule', 'can_update'),
            'client'             => $client,
            'clients'            => Lazy::inertia(fn () => Services::client()->list()),
            'deals'              => Lazy::inertia(fn () => DealResource::collect(Deal::where('id', '!=', $deal->id)->get())),
            'schedule_status'    => Lazy::inertia(fn () => DealScheduleStatus::labels()),
            'projectDepartments' => Lazy::inertia(
                fn () => Services::projectDepartment()->list(),
            ),
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BillingDealFormRequest $data, Client $client, Deal $deal)
    {
        /** @var ?Deal $deal */
        $newDeal = Services::billingDeal()->update->execute($data);

        if (is_null($newDeal)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.deals.billings.update.success'));

        return to_route('clients.billings.index', ['client' => $client]);
    }

    public function trash(DealOneOrManyRequest $data)
    {
        try {
            \DB::beginTransaction();
            $count = Deal::query()
                ->when($data->deal, fn ($q) => $q->where('id', $data->deal))
                ->when($data->ids, fn ($q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            \DB::commit();
            Services::toast()->success->execute(trans_choice('messages.deals.billings.trash.success', $count));
        } catch (\Throwable $e) {
            \DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(DealOneOrManyRequest $data)
    {
        try {
            \DB::beginTransaction();
            $count = Deal::query()
                ->onlyTrashed()
                ->when($data->deal, fn ($q) => $q->where('id', $data->deal))
                ->when($data->ids, fn ($q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            \DB::commit();
            Services::toast()->success->execute(trans_choice('messages.deals.billings.restore.success', $count));
        } catch (\Throwable $e) {
            \DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DealOneOrManyRequest $data)
    {
        try {
            \DB::beginTransaction();
            $count = Deal::query()
                ->withTrashed()
                ->when($data->deal, fn ($q) => $q->where('id', $data->deal))
                ->when($data->ids, fn ($q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            \DB::commit();
            Services::toast()->success->execute(trans_choice('messages.deals.billings.delete.success', $count));
        } catch (\Throwable $e) {
            \DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
