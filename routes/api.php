<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'AuthController@login')->name('login');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'AuthController@logout')->name('logout');

    Route::resource('approver', 'ApproverController')
        ->only(['store', 'update', 'destroy'])
        ->middleware("can:" . config('permission.attributes.permission.manage_user'));

    Route::name('approval_stage.')->prefix('approval-stage')
        ->middleware("can:" . config('permission.attributes.permission.manage_user'))
        ->group(function () {
            Route::post('/', 'ApprovalStageController@store')->name('store');
            Route::match(['put', 'patch'], '/{stage}', 'ApprovalStageController@update')->name('update');
        });

    Route::name('expense.')->prefix('expense')->group(function () {
        Route::post('/', 'ExpenseController@create')
            ->middleware("can:" . config('permission.attributes.permission.create_expense'))
            ->name('create');
        Route::match(['put', 'patch'], '/{expense}', 'ExpenseController@changeStatus')
            ->middleware("can:" . config('permission.attributes.permission.approve_expense'))
            ->name('update');
        Route::get('/{expense}', 'ExpenseController@show')->name('show');
    });
});
