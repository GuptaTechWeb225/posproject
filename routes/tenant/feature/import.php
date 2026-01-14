<?php

use App\Http\Controllers\Pos\Api\Product\import\ProductImportController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\Api\Product\import\LotImportController;

Route::group(['prefix' => 'app'],function (Router $router){
    $router->post('import/products',[ProductImportController::class,'importProducts'])->name('products.import');

    $router->post('import/stocks',[LotImportController::class,'importStocks'])->name('stocks.import');
});

?>