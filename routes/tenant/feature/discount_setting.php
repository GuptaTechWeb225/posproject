<?php

use App\Http\Controllers\Pos\Api\Settings\DiscountController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::get('discount-products/{branch}', [DiscountController::class, 'discountProducts'])->name('discount.products');
    Route::resource('discount-list', DiscountController::class);
    Route::get('discount-product-validation', [DiscountController::class, 'discountProductValidation'])->name('discount.product.validation');
    Route::get('stock-variant-with-branch/{branch_id}/{variant_id}', [DiscountController::class, 'discountProductShow'])->name('stock.variant.branch');
    Route::get('discount-status-change/{discount}', [DiscountController::class, 'discountStatusChange'])->name('discount.status.change');
});