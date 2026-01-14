<?php


namespace App\Services\Tenant\Product;


use App\Helpers\Core\Traits\FileHandler;
use App\Models\Core\File\File;
use App\Models\Pos\Product\AttributeVariationVariant\AttributeVariationVariant;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Tenant\Tag\Tag;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\TenantService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProductService extends TenantService
{
    use FileHandler;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    //used from multiple method
    private function relations(): array
    {
        return [
            'category:id,name',
            'subCategory:id,name',
            'group:id,name',
            'brand:id,name',
            'unit:id,name',
            'createdBy:id,first_name,last_name',
            'photos',
            'thumbnail',
            'status',
            'variants:id,product_id,upc,selling_price,name,tax_id,stock_reminder_quantity,status_id,description',
            'variants.attributesVariations:id,name,attribute_id',
            'variants.attributesVariations.attribute:id,name',
            'variants.tax:id,percentage',
            'variants.status:id,name',
            'variants.photos',
            'variants.thumbnail',
        ];
    }

    public function productDetails(): Product
    {
        return $this->model->load($this->relations());
    }


    public function loadProductLists($filter): LengthAwarePaginator
    {
        $productStatus = resolve(StatusRepository::class)->product("status_active", "status_inactive");

        $products = $this->model
            ->filters($filter)
            ->with($this->relations())
            ->withCount('variants')
            ->orderByDesc('id')
            ->paginate(request()->per_page ?? 15);

        return $products;
    }

    //update product basic info
    public function updateProductInfo($options = []): ProductService
    {
        $attributes = count($options) ? $options : request()->all();

        $this->model
            ->fill($this->getFillAble($attributes))
            ->save();

        return $this;
    }

    //Store new images (if select) during update...
    public function updateProductImages(): ProductService
    {
        $this->storeProductThumbnailImage();
        $this->storeProductGalleryImages();
        $this->storeVariantGalleryImages();
        $this->storeVariantThumbnailImage();

        return $this;
    }

    public function updateSingleVariant(): ProductService
    {
        if ($this->getAttr('product_type') === "single") {
            $this->model
                ->variant()
                ->update($this->productVariantRequest());
        }
        return $this;
    }

    public function updateMultipleVariant(): ProductService
    {
        if ($this->getAttr('product_type') === "variant") {
            if ($this->getAttr('variants')) {
                $var_ids = [];
                foreach ($this->getAttr('variants') as $variant) {
                    if (isset($variant['id'])) {
                        //update existing variant
                        $variantObj = Variant::find($variant['id']);
                        $variantObj->update($variant);
                        $var_ids[] = (int)$variant['id'];
                    } else {
                        //crate new variant
                        $data['product_id'] = $this->model->id;
                        $data['stock_reminder_quantity'] = $variant['stock_reminder_quantity'] ?? null;
                        $data['tax_id'] = $variant['tax_id'] ?? null;
                        $data['name'] = $variant['name'] ?? null;
                        $data['selling_price'] = $variant['selling_price'] ?? null;
                        $data['upc'] = $variant['upc'] ?? null;
                        $data['description'] = $variant['description'] ?? null;
                        $data['status_id'] = $variant['status_id'] ?? null;
                        $newVariant = Variant::query()->create($data);
                        $var_ids[] = $newVariant->id;
                    }
                }

                //for update attribute variations ----------
                $this->setAttribute('variant_ids', $var_ids);
            }
        }
        return $this;
    }

    public function UpdateAttributeVariationVariant(): ProductService
    {
        $variant_ids = $this->getAttribute('variant_ids');
        $variations = $this->getAttr('variations');

        if ($variations) {
            for ($i = 0; $i < count($variant_ids); $i++) {
                $product_variant = Variant::query()->find($variant_ids[$i]);
                $product_variant->attributesVariations()->detach();
                $product_variant->attributesVariations()->attach($variations[$i]);
            }
        }

        return $this;
    }

    public function storeProductInfo(): ProductService
    {
        $this->model = $this->saveProductInfo();
        return $this;
    }

    public function storeProductVariant(): ProductService
    {
        if ($this->getAttr('product_type') === "single") {
            $this->storeSingleVariant();
        } else {
            $this->storeMultipleVariants();
        }
        return $this;
    }

    public function storeProductGalleryImages(): ProductService
    {
        if ($this->getAttr('product_gallery')) {
            $this->storeGalleryImages($this->model);
        }
        return $this;
    }

    public function storeVariantGalleryImages(): ProductService
    {
        $product_variant_ids = $this->getThisProductVariantIds();
        $variants = $this->getAttr('variants');

        if ($variants) {
            for ($i = 0; $i < count($variants); $i++) {

                if (isset($variants[$i]['variant_gallery'])) {
                    $variantData = Variant::find($product_variant_ids[$i]->id);

                    $variantsImages = [];
                    foreach ($variants[$i]['variant_gallery'] as $variant_gallery) {
                        $variantsImages[] = [
                            'path' => $this->storeFile($variant_gallery, 'variant-product'),
                            'fileable_type' => Variant::class,
                            'fileable_id' => $product_variant_ids[$i]->id,
                            'type' => 'variant_product',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    $variantData->photos()->insert($variantsImages);
                }
            }
        }
        return $this;
    }


    public function storeVariantThumbnailImage(): ProductService
    {
        $product_variant_ids = $this->getThisProductVariantIds();
        $variants = $this->getAttr('variants');

        if ($variants) {
            for ($i = 0; $i < count($variants); $i++) {

                if (isset($variants[$i]['variant_thumbnail'])) {
                    $variantData = Variant::find($product_variant_ids[$i]->id);

                    //unlink and remove old thumbnail
                    $oldThumbnailPath = File::query()->where([
                        'fileable_type' => Variant::class,
                        'fileable_id' => $product_variant_ids[$i]->id,
                        'type' => 'variant_thumbnail'
                    ])->first();

                    if (isset($oldThumbnailPath->path)) {
                        $this->deleteImage($oldThumbnailPath->path);
                        $variantData->thumbnail()->delete();
                    }

                    //create new image thumbnail
                    $images = [
                        'path' => $this->storeFile($variants[$i]['variant_thumbnail'], 'variant-product'),
                        'fileable_type' => Variant::class,
                        'fileable_id' => $product_variant_ids[$i]->id,
                        'type' => 'variant_thumbnail',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $variantData->thumbnail()->create($images);
                }
            }
        }

        return $this;
    }

    //is used form multiple method and will return variant id's with asc order
    private function getThisProductVariantIds()
    {
        return Variant::query()
            ->where('product_id', $this->model->id) //product id
            ->select('id')->orderBy('id', 'asc')->get();
    }

    public function storeProductThumbnailImage(): ProductService
    {
        if (request()->hasFile('product_thumbnail')) {
            $this->storeProductThumbnail($this->model);
        }

        return $this;
    }

    private function storeProductThumbnail($model)
    {
        //remove previous thumbnail for update
        if (isset($this->model->thumbnail->path)) {
            $this->deleteImage($this->model->thumbnail->path);
            $this->model->thumbnail()->delete();
        }

        $image = $this->getAttr('product_thumbnail');

        $images = [
            'path' => $this->storeFile($image, 'product'),
            'fileable_type' => Product::class,
            'fileable_id' => $model->id,
            'type' => 'product_thumbnail',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $this->model->thumbnail()->create($images);
    }

    private function storeGalleryImages($model)
    {
        $images = [];
        foreach ($this->getAttr('product_gallery') as $image) {
            $images[] = [
                'path' => $this->storeFile($image, 'product'),
                'fileable_type' => Product::class,
                'fileable_id' => $model->id,
                'type' => 'product',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->model->photos()->insert($images);
    }

    private function storeMultipleVariants(): ProductService
    {
        $variants = [];
        if ($this->getAttr('variants')) {
            foreach ($this->getAttr('variants') as $variant) {
                $variants[] = $this->productVariantRequest($variant);
            }
        }

        //store variant product
        $this->model->variants()->insert($variants);

        //added product variant ids with asc order
        $product_variants = $this->getThisProductVariantIds();

        $this->storeVariantAttributes($product_variants);

        return $this;
    }

    private function storeVariantAttributes($product_variants): ProductService
    {
        $variations = $this->getAttr('variations');
        $attributeVariations = [];
        if ($variations) {
            foreach ($variations as $index => $variation) {
                for ($i = 0; $i < count($variation); $i++) {
                    $attributeVariations[] = [
                        'variant_id' => $product_variants[$index]['id'],
                        'attribute_variation_id' => $variation[$i]['attribute_variation_id']
                    ];
                }
            }
        }

        //store attribute variation variant
        AttributeVariationVariant::query()->insert($attributeVariations);

        return $this;
    }

    private function saveProductInfo()
    {
        return $this->model::query()
            ->create($this->productRequest());
    }


    private function productRequest(): array
    {
        return [
            'name' => $this->getAttr('name'),
            'group_id' => $this->getAttr('group_id'),
            'category_id' => $this->getAttr('category_id'),
            'sub_category_id' => $this->getAttr('sub_category_id'),
            'brand_id' => $this->getAttr('brand_id'),
            'unit_id' => $this->getAttr('unit_id'),
            'duration_id' => $this->getAttr('duration_id'),
            'product_type' => $this->getAttr('product_type'),
            'description' => $this->getAttr('description'),
            'warranty_duration' => $this->getAttr('warranty_duration'),
            'slug' => $this->getUniqueSlug(),
            'status_id' => resolve(StatusRepository::class)->productActive(),
            'created_by' => auth()->id(),
            'note' => $this->getAttr('note'),
        ];
    }


    private function storeSingleVariant(): Model
    {
        return $this->model->variant()
            ->create($this->productVariantRequest());
    }


    //this method will use for both single and variant product
    private function productVariantRequest($variant = null): array
    {
        return [
            'product_id' => $this->model->id,
            'created_by' => auth()->id(),
            'selling_price' => $variant['selling_price'] ?? $this->getAttr('selling_price'),
            'upc' => $variant['upc'] ?? $this->getAttr('upc'),
            'name' => $variant['name'] ?? $this->getAttr('name'),

            'description' => $variant['description'] ?? $this->getAttr('description'),
            'tax_id' => $variant['tax_id'] ?? $this->getAttr('tax_id'),
            'stock_reminder_quantity' => $variant['stock_reminder_quantity'] ?? $this->getAttr('stock_reminder_quantity'),
            'status_id' => $variant['status_id'] ?? $this->getAttr('status_id'),
        ];
    }


    private function getUniqueSlug(): string
    {
        $name = $this->getAttr('name');
        $lastId = $this->model::query()->max('id') + 1;

        return Str::slug($name . $lastId);
    }

    public function storeAttachTag(): JsonResponse
    {
        $tag = Tag::query()->create([
            'name' => $this->getAttr('name'),
            'color' => $this->getAttr('color')
        ]);

        $this->getModel()->tags()->attach($tag->id);

        return response()->json([

            'id' => $tag->id,
            'name' => $tag->name,
            'color' => $tag->color,

        ]);
    }

    public function changeStatus(): bool
    {
        $get_status = $this->getAttr('status');

        if (json_decode($get_status) === true) {
            $status = resolve(StatusRepository::class)->productActive();
        } else {
            $status = resolve(StatusRepository::class)->productInactive();
        }

        $this->getModel()->update([
            'status_id' => $status
        ]);

        return true;
    }

    public function changeVariantStatus($variant_id): bool
    {
        Variant::query()
            ->where('id', $variant_id)
            ->update([
                'is_enabled' => $this->getAttr('status') === true ? 1 : 0
            ]);

        return true;
    }

    /**
     * @param $product
     */
    public function deleteProduct()
    {
        $this->variantDelete()
            ->photosDelete()
            ->productDelete();
    }

    private function variantDelete(): ProductService
    {
        foreach ($this->model->variants as $variant) {
            $variant->productProducts()->delete();
            $variant->attributesVariations()->detach();
        }

        $this->model->variants()->delete();

        return $this;
    }

    private function photosDelete(): ProductService
    {
        foreach ($this->model->photos as $photos) {
            $this->deleteImage($photos->path);
        }
        $this->model->photos()->delete();

        return $this;
    }

    private function productDelete(): ProductService
    {
        $this->model->tags()->delete();
        $this->model->productProducts()->delete();
        $this->model->delete();

        return $this;
    }

}