<?php

namespace App\Services;

use App\Actions\Contractor\CreateOrUpdateContractor;
use App\Actions\Contractor\DeleteContractorEndsAt;
use App\Actions\Contractor\UpdateContractorEndsAt;
use App\Data\Contractor\ContractorResource;
use App\Models\Contractor;
use Closure;

class ContractorService
{
    public function __construct(
        public CreateOrUpdateContractor $createOrUpdate,
        public UpdateContractorEndsAt $updateEndsAt,
        public DeleteContractorEndsAt $deleteEndsAt,
    ) {}

    /**
     * @param  ?(Closure(\Illuminate\Database\Eloquent\Builder<Contractor> $query): Builder)  $callback
     */
    public function list(?Closure $callback = null)
    {
        return ContractorResource::collect(
            value($callback ?? Contractor::query(), Contractor::query())
                ->orderBy('full_name')
                ->get(),
        );
    }
}
