<?php

namespace App\Http\Controllers\Tenant\Products;

use App\Http\Controllers\Controller;
use App\Models\Pos\Product\Product\Product;
use App\Services\Tenant\Product\ProductDetailsService;
use Illuminate\Http\Request;

class ProductDetailsController extends Controller
{

    public function __construct(ProductDetailsService $service)
    {
        $this->service = $service;
    }

    public function show(Product $product): Product
    {
        return $this->service
            ->setModel($product)
            ->productDetails();
    }

    public function uploadProductImage(Request $request, Product $product)
    {
        $images = $this->service
            ->setModel($product)
            ->setAttrs($request->only('photos'))
            ->storeGalleryPhotos();

        return updated_responses('product_gallery', ['photos' => $images]);

    }

    public function productMakeDefault(Request $request, Product $product)
    {
        foreach ($product->photos as $photo) {
            $photo->update([
                'fileable_id' => $request->product_id,
                'type' => $request->file_id === $photo->id ? 'product_default_image' : 'product'
            ]);
        }

        return updated_responses('product', ['product' => $product->photos]);

    }

    public function productChangeStatus(Request $request, Product $product)
    {
        $this->service
            ->setModel($product)
            ->setAttrs($request->only('status'))
            ->changeStatus();

        return status_response('product', $request->status === 'status_inactive' ? 'activate' : 'deactivate');
    }
}
