<?php

namespace App\Http\Controllers\Client;

use App\Data\Client\ClientOneOrManyRequest;
use App\Data\Client\ClientResource;
use App\Data\Client\Form\ClientFormProps;
use App\Data\Client\Form\ClientFormRequest;
use App\Data\Client\Index\ClientIndexProps;
use App\Data\Client\Index\ClientIndexRequest;
use App\Data\Comment\CommentResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ClientController extends Controller
{
    /**`
     * Display a listing of the resource.
     */
    public function index(ClientIndexRequest $data)
    {

        return Inertia::render('client/Index', ClientIndexProps::from([
            'request' => $data,
            'clients' => Lazy::inertia(
                fn () => ClientResource::collect(
                    Client::query()
                        ->search($data->q)
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->paginate(
                            perPage: $data->per_page ?? Config::integer('default.per_page'),
                            page: $data->page ?? 1,
                        )
                        ->withQueryString(),
                    PaginatedDataCollection::class,
                )->include('can_view', 'can_update', 'can_trash', 'can_restore', 'can_delete'),
            ),

            'trashedFilters' => Lazy::inertia(fn () => TrashedFilter::labels()),
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('client/Create', ClientFormProps::from([]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientFormRequest $data)
    {
        /** @var ?Client $client */
        $client = Services::client()->createOrUpdate->execute($data);

        if (is_null($client)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.clients.store.success'));

        return to_route('clients.index');
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
    public function edit(Client $client)
    {
        return Inertia::render('client/Edit', ClientFormProps::from([
            'client'   => ClientResource::from($client)->include('can_update'),
            'comments' => $client->comments->load('creator')->map(
                fn ($comment) => CommentResource::from($comment)->include('can_update', 'can_delete'),
            ),
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientFormRequest $data, Client $client)
    {
        /** @var ?Client $client */
        $newClient = Services::client()->createOrUpdate->execute($data);

        if (is_null($newClient)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.clients.update.success'));

        return to_route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function trash(ClientOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Client::query()
                ->when($data->client, fn ($q) => $q->where('id', $data->client))
                ->when($data->ids, fn ($q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.clients.trash.success', $count));
        } catch (\Throwable $e) {
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore(ClientOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Client::query()
                ->onlyTrashed()
                ->when($data->client, fn ($q) => $q->where('id', $data->client))
                ->when($data->ids, fn ($q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.clients.restore.success', $count));
        } catch (\Throwable $e) {
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function destroy(ClientOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Client::query()
                ->withTrashed()
                ->when($data->client, fn ($q) => $q->where('id', $data->client))
                ->when($data->ids, fn ($q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.clients.delete.success', $count));
        } catch (\Throwable $e) {
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
