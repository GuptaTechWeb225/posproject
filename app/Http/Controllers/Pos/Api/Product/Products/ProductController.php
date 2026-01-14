<?php

namespace App\Http\Controllers\Pos\Api\Product\Products;

use App\Filters\Tenant\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\ProductRequest;
use App\Models\Core\File\File;
use App\Models\Pos\Product\Product\Product;
use App\Services\Tenant\Product\ProductService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function React\Promise\all;

class ProductController extends Controller
{
    public function __construct(ProductService $service, ProductFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): LengthAwarePaginator
    {
        return $this->service
            ->loadProductLists($this->filter);
    }

    public function store(ProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $this->service
                ->setAttrs($request->all())
                ->storeProductInfo()
                ->storeProductVariant()
                ->storeProductGalleryImages()
                ->storeProductThumbnailImage()
                ->storeVariantGalleryImages()
                ->storeVariantThumbnailImage();
        });

        return created_responses('product');
    }

    public function show(Product $product): Product
    {
        return $this->service
            ->setModel($product)
            ->productDetails();
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->service
            ->setModel($product)
            ->setAttrs($request->all())
            ->updateProductInfo(
                $request->only('name','group_id','category_id',
                    'sub_category_id','brand_id','unit_id','product_type','sku','warranty_duration',
                    'warranty_duration_type'
                )
            )->updateProductImages()
            ->updateSingleVariant() //only for single product
            ->updateMultipleVariant() //variant product
            ->UpdateAttributeVariationVariant(); //only for multiple variant

        return updated_responses('product',['product'=>$product]);
    }

    public function destroy(Product $product)
    {
        //delete variant's product with files and attribute variation
        foreach ($product->variants as $variant) {
            //delete variant files/images...
            foreach ($variant->photos as $photo) {
                $this->service->deleteImage($photo->path);
            }
            $variant->photos()->delete();

            //delete stock adjustment variant
            if ($variant->adjustmentVariant) {
                $variant->adjustmentVariant()->delete();
            }

            //delete stock adjustment variant
            if ($variant->lotVariant) {
                $variant->lotVariant()->delete();
            }

            //delete variant thumbnails......
            if ($variant->thumbnail) {
                $this->service->deleteImage($variant->thumbnail->path);
                $variant->thumbnail()->delete();
            }
            $variant->productProducts()->delete();
            $variant->attributesVariations()->detach();

        }

        //delete variant product
        $product->variants()->delete();

       //delete product gallery
        foreach ($product->photos as $photos) {
            $this->service->deleteImage($photos->path);
        }
        $product->photos()->delete();

        //delete product thumbnails
        if ($product->thumbnail) {
            $this->service->deleteImage($product->thumbnail->path);
            $product->thumbnail()->delete();
        }

        //delete products
        $product->delete();

        return deleted_responses('product');
    }


    public function productAttachTags(Request $request, Product $product)
    {
        $product->tags()
            ->attach($request->tag_id);

        return attached_response('product');
    }

    public function productDetachTags(Request $request, Product $product)
    {
        $product->tags()
            ->detach($request->tag_id);

        return detached_response('product');
    }

    public function productAttachStoreTags(Request $request, Product $product): JsonResponse
    {
        return $this->service
            ->setModel($product)
            ->setAttrs($request->all())
            ->storeAttachTag();
    }

    public function productStatusChange(Request $request, Product $product)
    {
        $this->service
            ->setModel($product)
            ->setAttrs($request->only('status'))
            ->changeStatus();

        return updated_responses('status');
    }

    public function productVariantStatusChange(Request $request, $variant_id): bool
    {
        return $this->service
            ->setAttrs($request->only('status'))
            ->changeVariantStatus($variant_id);
    }

    //To delete Image / File by id
    public function deleteProductFile($id)
    {
        $file = File::find($id);
        if (isset($file->path)){
            $this->service->deleteImage($file->path);
            $file->delete();
            return deleted_responses('Product Image');
        }else{
            return failed_responses();
        }
    }
}
