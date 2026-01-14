<?php


namespace App\Services\Tenant\Product;


use App\Helpers\Core\Traits\FileHandler;
use App\Models\Core\File\File;
use App\Models\Pos\Product\Product\Variant;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\TenantService;

class VariantService extends TenantService
{
    use FileHandler;

    private ProductService $productService;

    public function __construct(Variant $variant, ProductService $productService)
    {
        $this->model = $variant;
        $this->productService = $productService;
    }

    public function relations(): array
    {
        return [
            'attributesVariations:id,name,attribute_id',
            'attributesVariations.attribute:id,name',
            'tax:id,percentage,is_default',
            'status:id,name',
            'photos',
            'thumbnail',

            'product' => function ($product) {
                $product->select(['id','product_type']);
             },
            'product.thumbnail',

        ];
    }

    public function updateVariantInfo($options = []): static
    {
        $attributes = count($options) ? $options : request()->all();
        $this->model
            ->fill($this->getFillAble($attributes))
            ->save();

        return $this;
    }

    public function update()
    {
        $this->model->fill($this->getAttributes());
        $this->model->save();
        return $this;
    }

    public function storeVariantGallery()
    {
        $images = [];

        if ($this->getAttr('photos')) {
            foreach ($this->getAttr('photos') as $key => $image) {
                $images[] = [
                    'path' => $this->storeFile($image, 'variant'),
                    'fileable_type' => Variant::class,
                    'fileable_id' => $this->model->id,
                    'type' => $key === 'default' ? 'variant_default_image' : 'variant',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($key === 'default') {
                    $this->deletePreviousDefaultImage();
                }
            }

            $this->prepareDeletePhotos();

            $this->model->attachments()->insert($images);
        }

        if ($this->getAttr('photos') == null) {
            $this->prepareDeletePhotos();
        }

        return $this->model->attachments;
    }

    public function prepareDeletePhotos()
    {
        $paths = $this->getModel()->attachments()->pluck('path')->filter(function ($path) {
            return !in_array($path, request()->get('dont_delete', []));
        })->toArray();

        $this->deletePhotos($paths);
    }

    public function deletePhotos($paths = [])
    {
        $this->deleteMultipleFile($paths);

        return $this->model->attachments()
            ->whereIn('path', $paths)
            ->delete();
    }

    public function checkVariantIsUsed(): static
    {
        return $this;
    }

    public function deleteVariant(): VariantService
    {
        if (isset($this->model->photos)) {
            foreach ($this->model->photos as $photo) {
                $this->productService->deleteImage($photo->path);
            }
            $this->model->photos()->delete();
        }

        //delete variant thumbnails......
        if ($this->model->thumbnail) {
            $this->productService->deleteImage($this->model->thumbnail);
            $this->model->thumbnail()->delete();
        }

        $this->model->attributesVariations()->detach();
        $this->model->delete();

        return $this;
    }

    private function deletePreviousDefaultImage()
    {
        $attachment = $this->model
            ->attachments()
            ->where('type', 'variant_default_image')
            ->first();

        $this->deleteImage(optional($attachment)->path);

        optional($attachment)->delete();
    }

    public function changeStatus(): bool
    {
        $this->getModel()->update([
            'status_id' => $this->getAttr('status')
                ? resolve(StatusRepository::class)->productActive()
                : resolve(StatusRepository::class)->productInactive()
        ]);

        return true;
    }

    public function updateVariantImages(): static
    {
        $this->storeVariantThumbnailImage();
        $this->storeVariantGalleryImages();
        return $this;
    }

    private function storeVariantThumbnailImage(): static
    {
        $variant_thumbnail = $this->getAttr('variant_thumbnail');

        if (isset($variant_thumbnail)) {
            //unlink and remove old thumbnail
            $oldThumbnailPath = File::query()->where([
                'fileable_type' => Variant::class,
                'fileable_id' => $this->model->id,
                'type' => 'variant_thumbnail'
            ])->first();

            if (isset($oldThumbnailPath->path)) {
                $this->deleteImage($oldThumbnailPath->path);
                $this->model->thumbnail()->delete();
            }

            //create new image thumbnail
            $thumbnail = [
                'path' => $this->storeFile($variant_thumbnail, 'variant-product'),
                'fileable_type' => Variant::class,
                'fileable_id' => $this->model->id,
                'type' => 'variant_thumbnail',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->model->thumbnail()->create($thumbnail);
        }

        return $this;
    }

    private function storeVariantGalleryImages(): static
    {
        $variant_gallery = $this->getAttr('variant_gallery');

        if (isset($variant_gallery)) {
            $variantsImages = [];

            foreach ($variant_gallery as $gallery) {
                $variantsImages[] = [
                    'path' => $this->storeFile($gallery, 'variant-product'),
                    'fileable_type' => Variant::class,
                    'fileable_id' => $this->model->id,
                    'type' => 'variant_product',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $this->model->photos()->insert($variantsImages);
        }

        return $this;
    }
}