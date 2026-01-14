<?php

use App\Http\Controllers\Pos\Api\Product\Products\ImportController;
use App\Http\Controllers\Pos\Api\Product\Products\ProductController;
use App\Http\Controllers\Tenant\Products\ProductDetailsController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route){

    $route->apiResource('products', ProductController::class);

    $route->delete('/product-image/delete/{id}',[ProductController::class,'deleteProductFile'])
        ->name('delete.product-image');

    $route->post('products/{product}/attach-tags', [ProductController::class, 'productAttachTags'])
        ->name('products.attach-tags');

    $route->post('products/{product}/detach-tags', [ProductController::class, 'productDetachTags'])
        ->name('products.detach-tags');

    $route->post('products/{product}/attach-store-tags', [ProductController::class, 'productAttachStoreTags'])
        ->name('products.attach-store-tags');

    $route->patch('products/{product}/change-status', [ProductController::class, 'productStatusChange'])
        ->name('products.change-status');

    $route->patch('products/change-variant-status/{id}', [ProductController::class, 'productVariantStatusChange'])
        ->name('products.change-variant-status');

    $route->post('products/{product}/attach-tags', [ProductController::class, 'productAttachTags'])
        ->name('products.attach-tags');

    $route->post('products/{product}/detach-tags', [ProductController::class, 'productDetachTags'])
        ->name('products.detach-tags');

    $route->post('products/{product}/attach-store-tags', [ProductController::class, 'productAttachStoreTags'])
        ->name('products.attach-store-tags');

    // Product Details
    $route->get('products/{product}/details', [ProductDetailsController::class, 'show'])
        ->name('manage.products');

    $route->post('products/{product}/photo-upload', [ProductDetailsController::class, 'uploadProductImage'])
        ->name('product.upload');

    $route->post('products/{product}/make-default', [ProductDetailsController::class, 'productMakeDefault'])
        ->name('make.default');

    $route->post('products/{product}/change-product-status', [ProductDetailsController::class, 'productChangeStatus'])
        ->name('product.change-status');
});