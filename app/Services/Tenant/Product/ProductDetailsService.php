<?php


namespace App\Services\Tenant\Product;


use App\Helpers\Core\Traits\FileHandler;
use App\Models\Pos\Product\Product\Product;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\TenantService;

class ProductDetailsService extends TenantService
{
    use FileHandler;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    private function productDetailsRelations(): array
    {
        return [
            'category:id,name',
            'duration:id,type',
            'subCategory:id,name',
            'group:id,name',
            'brand:id,name',
            'unit:id,name',
            'createdBy:id,first_name,last_name',
            'photos',
            'thumbnail',
            'status',
            'variants:id,product_id,upc,selling_price,name,tax_id,stock_reminder_quantity,status_id,description',
            'variants.tax' => function($tax){
                $tax->select(['id','percentage']);
            },
        ];
    }

    public function productDetails(): Product
    {
        return $this->model->load($this->productDetailsRelations());
    }

    public function storeGalleryPhotos()
    {
        $images = [];
        $oldImage = 0;

        if ($this->getAttr('photos')) {

            $count = $this->model->photos()->where('type', 'product_default_image')->count();
            $index = intval(request()->get('default_index') < count($this->getAttr('photos')) ? request()->get('default_index') : 0);

            foreach ($this->getAttr('photos') as $key => $image) {

                $images[] = [
                    'path' => $this->storeFile($image, 'product'),
                    'fileable_type' => Product::class,
                    'fileable_id' => $this->model->id,
                    'type' => $count == 0 && $key === $index ? 'product_default_image' : 'product',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $this->model->photos()->insert($images);
            $oldImage = 1;
        }

        if ($oldImage === 0) {
            $this->prepareDeletePhotos();
            $this->makeFirstImageDefault();
        }

        return $this->model->photos;
    }

    public function prepareDeletePhotos()
    {
        $paths = $this->model->photos()->pluck('path')->filter(function ($path) {
            return !in_array($path, request()->get('dont_delete', []));
        })->toArray();

        $this->deletePhotos($paths);
    }

    public function deletePhotos($paths = [])
    {
        $this->deleteMultipleFile($paths);

        return $this->model->photos()
            ->whereIn('path', $paths)
            ->delete();
    }

    public function makeFirstImageDefault()
    {
        $countProductDefaultImage = $this->model->photos()
                ->count() &&
            $this->model->photos()
                ->where('type', 'product_default_image')
                ->count();

        if ($countProductDefaultImage == 0) {
            $this->model->photos()->first()->update([
                'type' => 'product_default_image'
            ]);
        }
    }

    public function changeStatus()
    {
        $this->getModel()->update([
            'status_id' => $this->getAttr('status') === 'status_inactive'
                ? resolve(StatusRepository::class)->productActive()
                : resolve(StatusRepository::class)->productInactive()
        ]);

        return true;
    }
}