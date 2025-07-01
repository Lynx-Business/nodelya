<?php

namespace App\Http\Controllers\Deal;

use App\Data\Client\ClientListResource;
use App\Data\Deal\Commercial\Form\CommercialDealFormProps;
use App\Data\Deal\Commercial\Form\CommercialDealFormRequest;
use App\Data\Deal\Commercial\Form\CommercialDealFormResource;
use App\Data\Deal\Commercial\Index\CommercialDealIndexProps;
use App\Data\Deal\Commercial\Index\CommercialDealIndexRequest;
use App\Data\Deal\Commercial\Index\CommercialDealIndexResource;
use App\Data\Deal\Commercial\Validate\CommercialDealValidateProps;
use App\Data\Deal\Commercial\Validate\CommercialDealValidateRequest;
use App\Data\Deal\DealListResource;
use App\Data\Deal\DealOneOrManyRequest;
use App\Enums\Deal\DealStatus;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class CommercialDealController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(CommercialDealIndexRequest $data)
    {

        return Inertia::render('deal/commercial/Index', CommercialDealIndexProps::from([
            'request'          => $data,
            'commercial_deals' => Lazy::inertia(
                fn () => CommercialDealIndexResource::collect(
                    Deal::commercial()
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('deal/commercial/Create', CommercialDealFormProps::from([
            'clients' => Lazy::inertia(fn () => ClientListResource::collect(Client::all())),
            'deals'   => Lazy::inertia(fn () => DealListResource::collect(Deal::all())),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommercialDealFormRequest $data)
    {
        /** @var ?Deal $deal */
        $deal = Services::commercialDeal()->createOrUpdate->execute($data);

        if (is_null($deal)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.commercial_deals.store.success'));

        return to_route('commercial.deals.index');
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
    public function edit(Deal $deal)
    {

        return Inertia::render('deal/commercial/Edit', CommercialDealFormProps::from([
            'deal' => CommercialDealFormResource::from(
                $deal->load('client', 'parent'),
            ),
            'clients' => Lazy::inertia(fn () => ClientListResource::collect(Client::all())),
            'deals'   => Lazy::inertia(fn () => DealListResource::collect(Deal::where('id', '!=', $deal->id)->get())),
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommercialDealFormRequest $data, Deal $deal)
    {
        /** @var ?Deal $deal */
        $newClient = Services::commercialDeal()->createOrUpdate->execute($data);

        if (is_null($newClient)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.commercial_deals.update.success'));

        return to_route('commercial.deals.index');
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
            Services::toast()->success->execute(trans_choice('messages.commercial_deals.trash.success', $count));
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
            Services::toast()->success->execute(trans_choice('messages.commercial_deals.restore.success', $count));
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
            Services::toast()->success->execute(trans_choice('messages.commercial_deals.delete.success', $count));
        } catch (\Throwable $e) {
            \DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function validateDeal(Deal $deal)
    {
        $reference = $this->generateReference($deal);

        return Inertia::render('deal/commercial/Validate', CommercialDealValidateProps::from([
            'deal'      => DealListResource::from($deal),
            'reference' => $reference,
        ]));
    }

    public function processValidation(CommercialDealValidateRequest $data, Deal $deal)
    {

        DB::transaction(function () use ($data, $deal) {

            $deal->update([
                'amount'    => $data->amount,
                'reference' => $data->reference,
                'status'    => DealStatus::VALIDATED,
            ]);
        });

        Services::toast()->success->execute(__('messages.commercial_deals.validate.success'));

        return to_route('billing.deals.index');
    }

    private function generateReference(Deal $deal): string
    {
        $year = now()->format('Y');
        $letter = '';

        if ($deal->deal_id) {
            $parentDeal = Deal::findOrFail($deal->deal_id);

            $childCount = Deal::where('deal_id', $parentDeal->id)
                ->whereNotNull('reference')
                ->count();

            while ($childCount > 0) {
                $remainder = ($childCount - 1) % 26;
                $letter = chr(65 + $remainder).$letter;
                $childCount = intval(($childCount - 1) / 26);
            }
        }

        $increment = Deal::count();

        return sprintf('%sN%d%s', $year, $increment, $letter ? $letter : '');
    }
}
