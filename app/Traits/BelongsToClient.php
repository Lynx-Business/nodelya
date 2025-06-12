<?php

namespace App\Traits;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToClient
{
    public static function bootBelongsToClient(): void
    {
        static::creating(function (self $model) {
            $model->{$model->getClientIdColumn()} ??= Auth::id();
        });
    }

    public function initializeBelongsToClient(): void {}

    public function getClientIdColumn(): string
    {
        return defined(static::class.'::CLIENT_ID') ? static::CLIENT_ID : 'client_id';
    }

    public function getQualifiedClientIdColumn(): string
    {
        return $this->qualifyColumn($this->getClientIdColumn());
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, $this->getClientIdColumn());
    }

    public function scopeWhereBelongsToClient(Builder $query, Client|int $client): Builder
    {
        $client = is_int($client) ? $client : $client->getKey();

        return $query
            ->where($this->getQualifiedClientIdColumn(), $client);
    }
}
