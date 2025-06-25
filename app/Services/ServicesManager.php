<?php

namespace App\Services;

class ServicesManager
{
    public function __construct() {}

    protected ?AccountingPeriodService $accountingPeriod = null;

    public function accountingPeriod(): AccountingPeriodService
    {
        return $this->accountingPeriod ??= app(AccountingPeriodService::class);
    }

    protected ?ConversionService $conversion = null;

    public function conversion(): ConversionService
    {
        return $this->conversion ??= app(ConversionService::class);
    }

    protected ?ContractorService $contractor = null;

    public function contractor(): ContractorService
    {
        return $this->contractor ??= app(ContractorService::class);
    }

    protected ?EmployeeService $employee = null;

    public function employee(): EmployeeService
    {
        return $this->employee ??= app(EmployeeService::class);
    }

    protected ?ExpenseService $expense = null;

    public function expense(): ExpenseService
    {
        return $this->expense ??= app(ExpenseService::class);
    }

    protected ?MediaService $media = null;

    public function media(): MediaService
    {
        return $this->media ??= app(MediaService::class);
    }

    protected ?ProjectDepartmentService $projectDepartment = null;

    public function projectDepartment(): ProjectDepartmentService
    {
        return $this->projectDepartment ??= app(ProjectDepartmentService::class);
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

    protected ?ClientService $client = null;

    public function client(): ClientService
    {
        return $this->client ??= app(ClientService::class);
    }

    protected ?CommercialDealService $deal = null;

    public function commercialDeal(): CommercialDealService
    {
        return $this->deal ??= app(CommercialDealService::class);
    }
}
