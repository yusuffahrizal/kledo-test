<?php

namespace App\Providers;

use App\Repositories\Contracts\ApprovalStageRepositoryInterface;
use App\Repositories\Contracts\ApproverRepositoryInterface;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Repositories\Eloquent\ApprovalStageRepository;
use App\Repositories\Eloquent\ApproverRepository;
use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Eloquent\ExpenseRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(ApproverRepositoryInterface::class, ApproverRepository::class);
        $this->app->bind(ApprovalStageRepositoryInterface::class, ApprovalStageRepository::class);
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
