<?php

namespace App\Http\Controllers\Deal;

use App\Data\Client\ClientListResource;
use App\Data\Deal\Billing\Form\BillingDealFormProps;
use App\Data\Deal\Billing\Form\BillingDealFormRequest;
use App\Data\Deal\Billing\Form\BillingDealFormResource;
use App\Data\Deal\Billing\Index\BillingDealIndexProps;
use App\Data\Deal\Billing\Index\BillingDealIndexRequest;
use App\Data\Deal\Billing\Index\BillingDealIndexResource;
use App\Data\Deal\DealListResource;
use App\Data\Deal\DealOneOrManyRequest;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class BillingDealController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(BillingDealIndexRequest $data)
    {

        return Inertia::render('deal/billing/Index', BillingDealIndexProps::from([
            'request'       => $data,
            'billing_deals' => Lazy::inertia(
                fn () => BillingDealIndexResource::collect(
                    Deal::billing()
                        ->search($data->q)
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->with(['client'])
                        ->paginate(
                            perPage: $data->per_page ?? Config::integer('default.per_page'),
                            page: $data->page ?? 1,
                        )
                        ->withQueryString(),
                    PaginatedDataCollection::class,
                ),
            ),

            'trashed_filters' => Lazy::inertia(fn () => TrashedFilter::labels()),
        ]));
    }

    public function edit(Deal $deal)
    {

        return Inertia::render('deal/billing/Edit', BillingDealFormProps::from([
            'deal' => BillingDealFormResource::from(
                $deal->load('client', 'parent'),
            ),
            'clients' => Lazy::inertia(fn () => ClientListResource::collect(Client::all())),
            'deals'   => Lazy::inertia(fn () => DealListResource::collect(Deal::where('id', '!=', $deal->id)->get())),
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BillingDealFormRequest $data, Deal $deal)
    {
        /** @var ?Deal $deal */
        $newClient = Services::billingDeal()->update->execute($data);

        if (is_null($newClient)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.billing_deals.update.success'));

        return to_route('billing.deals.index');
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
            Services::toast()->success->execute(trans_choice('messages.billing_deals.trash.success', $count));
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
            Services::toast()->success->execute(trans_choice('messages.billing_deals.restore.success', $count));
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
            Services::toast()->success->execute(trans_choice('messages.billing_deals.delete.success', $count));
        } catch (\Throwable $e) {
            \DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
