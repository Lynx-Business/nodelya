<?php

namespace App\Http\Controllers\Deal;

use App\Data\Deal\Commercial\Form\CommercialDealFormProps;
use App\Data\Deal\Commercial\Form\CommercialDealFormRequest;
use App\Data\Deal\Commercial\Index\CommercialDealIndexProps;
use App\Data\Deal\Commercial\Index\CommercialDealIndexRequest;
use App\Data\Deal\Commercial\Validate\CommercialDealValidateProps;
use App\Data\Deal\Commercial\Validate\CommercialDealValidateRequest;
use App\Data\Deal\DealOneOrManyRequest;
use App\Data\Deal\DealResource;
use App\Enums\Deal\DealStatus;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\Deal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        $months = [];

        $accountingPeriod = $data->accounting_period;

        $months = [];
        if ($accountingPeriod) {
            $start = Carbon::parse($accountingPeriod->starts_at);
            $end = Carbon::parse($accountingPeriod->ends_at);
            $period = CarbonPeriod::create($start, '1 month', $end);

            foreach ($period as $date) {
                $months[] = $date->format('Y-m');
            }
        }

        return Inertia::render('deals/commercial/Index', CommercialDealIndexProps::from([
            'request'          => $data,
            'commercial_deals' => Lazy::inertia(
                function () use ($data, $accountingPeriod) {
                    $paginatedDeals = Deal::commercial()
                        ->search($data->q)
                        ->when($data->client_ids, fn (Builder $q) => $q->whereIntegerInRaw('client_id', $data->client_ids))
                        ->when($data->name, fn (Builder $q) => $q->where('name', 'like', '%'.$data->name.'%'))
                        ->when($data->amount, fn (Builder $q) => $q->where('amount_in_cents', Services::conversion()->priceToCents($data->amount)))
                        ->when($data->success_rate, fn (Builder $q) => $q->where('success_rate', $data->success_rate))
                        ->when($data->code, fn (Builder $q) => $q->where('code', 'like', '%'.$data->code.'%'))
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->when($accountingPeriod, fn (Builder $query) => $query->whereInAccountingPeriod($accountingPeriod->id))
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
            'clients'                  => Lazy::inertia(fn () => Services::client()->list()),
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('deals/commercial/Create', CommercialDealFormProps::from([
            'clients' => Lazy::inertia(fn () => Services::client()->list()),
            'deals'   => Lazy::inertia(fn () => DealResource::collect(Deal::all())),
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

        Services::toast()->success->execute(__('messages.deals.commercials.store.success'));

        return to_route('deals.commercials.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deal $deal)
    {

        return Inertia::render('deals/commercial/Edit', CommercialDealFormProps::from([
            'deal' => DealResource::from(
                $deal->load('client', 'parent'),
            )->include('schedule', 'can_update'),
            'clients' => Lazy::inertia(fn () => Services::client()->list()),
            'deals'   => Lazy::inertia(fn () => DealResource::collect(Deal::where('id', '!=', $deal->id)->get())),
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommercialDealFormRequest $data, Deal $deal)
    {
        /** @var ?Deal $deal */
        $newDeal = Services::commercialDeal()->createOrUpdate->execute($data);

        if (is_null($newDeal)) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.deals.commercials.update.success'));

        return to_route('deals.commercials.index');
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
            Services::toast()->success->execute(trans_choice('messages.deals.commercials.trash.success', $count));
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
            Services::toast()->success->execute(trans_choice('messages.deals.commercials.restore.success', $count));
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
            Services::toast()->success->execute(trans_choice('messages.deals.commercials.delete.success', $count));
        } catch (\Throwable $e) {
            \DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function validateDeal(Deal $deal)
    {
        $reference = $this->generateReference($deal);

        return Inertia::render('deals/commercial/Validate', CommercialDealValidateProps::from([
            'deal'         => DealResource::from($deal),
            'reference'    => $reference,
            'expenseItems' => Lazy::inertia(
                fn () => Services::expense()->itemsList(),
            ),
            'projectDepartments' => Lazy::inertia(
                fn () => Services::projectDepartment()->list(),
            ),
            'contractors' => Lazy::inertia(
                fn () => Services::contractor()->list(),
            ),
        ]));
    }

    public function processValidation(CommercialDealValidateRequest $data, Deal $deal)
    {
        DB::transaction(function () use ($data, $deal) {

            $deal->update([
                'project_department_id' => $data->project_department_id,
                'reference'             => $data->reference,
                'status'                => DealStatus::VALIDATED,
            ]);

            $deal->expenseCharges()->delete();

            foreach ($data->expense_charges as $charge) {
                $deal->expenseCharges()->create([
                    'expense_item_id' => $charge->expense_item_id,
                    'amount'          => $charge->amount,
                    'charged_at'      => $charge->charged_at,
                    'model_type'      => $charge->contractor_id ? Contractor::class : null,
                    'model_id'        => $charge->contractor_id ?? null,
                ]);
            }
        });

        Services::toast()->success->execute(__('messages.deals.commercials.validate.success'));

        return to_route('deals.billings.index');
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
