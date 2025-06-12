<?php

namespace App\Services;

class ServicesManager
{
    public function __construct() {}

    protected ?ConversionService $conversion = null;

    public function conversion(): ConversionService
    {
        return $this->conversion ??= app(ConversionService::class);
    }

    protected ?MediaService $media = null;

    public function media(): MediaService
    {
        return $this->media ??= app(MediaService::class);
    }

    protected ?SettingsService $settings = null;

    public function settings(): SettingsService
    {
        return $this->settings ??= app(SettingsService::class);
    }

    protected ?TeamService $team = null;

    public function team(): TeamService
    {
        return $this->team ??= app(TeamService::class);
    }

    protected ?ToastService $toast = null;

    public function toast(): ToastService
    {
        return $this->toast ??= app(ToastService::class);
    }

    protected ?UserService $user = null;

    public function user(): UserService
    {
        return $this->user ??= app(UserService::class);
    }
}
