<?php

namespace App\Http\Controllers\Client;

use App\Data\Client\Form\ClientFormProps;
use App\Data\Client\Form\ClientFormRequest;
use App\Data\Client\Index\ClientIndexProps;
use App\Data\Client\Index\ClientIndexRequest;
use App\Data\Client\Index\ClientIndexResource;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\ToastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ClientController extends Controller
{
    public function __construct(
        protected ToastService $toastService,
    ) {}

    /**`
     * Display a listing of the resource.
     */
    public function index(ClientIndexRequest $data)
    {

        return Inertia::render('client/Index', ClientIndexProps::from([
            'clients' => Lazy::inertia(
                fn () => ClientIndexResource::collect(
                    Client::query()
                        ->search($data->q)
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->paginate(
                            perPage: $data->per_page ?? Config::integer('default.per_page'),
                            page: $data->page ?? 1,
                        )
                        ->withQueryString(),
                    PaginatedDataCollection::class,
                ),
            ),
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
        $client = Services::client()->createOrUpdateClient->execute($data);

        if (is_null($client)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.users.members.store.success'));

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
