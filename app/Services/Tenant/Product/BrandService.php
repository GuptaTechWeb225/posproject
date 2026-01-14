<?php


namespace App\Services\Tenant\Product;

use App\Models\Pos\Product\Brand\Brand;
use App\Services\Tenant\TenantService;
use App\Helpers\Core\Traits\FileHandler;
use App\Helpers\Core\Traits\HasWhen;
use App\Helpers\Core\Traits\Helpers;
use App\Services\Core\Auth\Traits\HasUserActions;

class BrandService extends TenantService
{
    use FileHandler, Helpers, HasWhen, HasUserActions;

    public function __construct(Brand $brand)
    {
        $this->model = $brand;
    }

    public function update()
    {

        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }

    public function uploadBrandLogo($brand_logo)
    {
        if (request()->hasFile('brand_logo')){

            $this->deleteImage(optional($this->model->brandLogo)->path);

            $file_path = $this->isWithOriginalName()->storeFile($brand_logo, 'brand_logo');

            $this->model->brandLogo()->updateOrCreate([
                'type' => 'brand_logo'
            ], [
                'path' => $file_path
            ]);
        }

        return $this;
    }

}