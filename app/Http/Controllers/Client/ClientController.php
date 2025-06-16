<?php

namespace App\Http\Controllers\Client;

use App\Data\Client\Index\ClientIndexProps;
use App\Data\Client\Index\ClientIndexRequest;
use App\Data\Client\Index\ClientIndexResource;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ClientController extends Controller
{
    /**
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
