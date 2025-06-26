<?php

namespace App\Http\Controllers\Deal;

use App\Data\Client\ClientListResource;
use App\Data\Deal\Commercial\Form\CommercialDealFormProps;
use App\Data\Deal\Commercial\Form\CommercialDealFormRequest;
use App\Data\Deal\Commercial\Index\CommercialDealIndexProps;
use App\Data\Deal\Commercial\Index\CommercialDealIndexRequest;
use App\Data\Deal\Commercial\Index\CommercialDealIndexResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
                    Deal::query()
                        ->search($data->q)
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->orderBy($data->sort_by, $data->sort_direction)
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
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('deal/commercial/Create', CommercialDealFormProps::from([
            'clients' => Lazy::inertia(fn () => ClientListResource::collect(Client::all())),
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
