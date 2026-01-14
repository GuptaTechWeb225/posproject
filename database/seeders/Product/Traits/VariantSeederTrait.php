<?php

namespace Database\Seeders\Product\Traits;

use App\Models\Pos\Product\Product\Variant;
use App\Repositories\Core\Status\StatusRepository;

trait VariantSeederTrait
{
    public function variantSeeder()
    {
        $activeProductStatusId = resolve(StatusRepository::class)->productActive();

        $variants = [
            [
                'product_id' => 1,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 12345678,
                'selling_price' => 250,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Leather belt',
                'description' => 'description'
            ],
            [
                'product_id' => 2,
                'tax_id' => 3,
                'stock_reminder_quantity' => 4,
                'upc' => 21345678,
                'selling_price' => 580,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Sleeves',
                'description' => 'description'
            ],
            [
                'product_id' => 3,
                'tax_id' => 3,
                'stock_reminder_quantity' => 1,
                'upc' => 21435678,
                'selling_price' => 1200,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'T Shirt',
                'description' => 'description'
            ],
            [
                'product_id' => 4,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 21435687,
                'selling_price' => 950,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Black tie',
                'description' => 'description'
            ],
            [
                'product_id' => 5,
                'tax_id' => 3,
                'stock_reminder_quantity' => 1,
                'upc' => 21438056,
                'selling_price' => 1500,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Cotton women full pant',
                'description' => 'description'
            ],
            [
                'product_id' => 6,
                'tax_id' => 3,
                'stock_reminder_quantity' => 1,
                'upc' => 21884380,
                'selling_price' => 1250,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Full jeans pant',
                'description' => 'description'
            ],
            [
                'product_id' => 7,
                'tax_id' => 3,
                'stock_reminder_quantity' => 1,
                'upc' => 21431156,
                'selling_price' => 350,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Hat',
                'description' => 'description'
            ],
            [
                'product_id' => 8,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 21439956,
                'selling_price' => 1850,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Full pant for man',
                'description' => 'description'
            ],
            [
                'product_id' => 9,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 21430056,
                'selling_price' => 950,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Three quarter pant',
                'description' => 'description'
            ],
            [
                'product_id' => 10,
                'tax_id' => 3,
                'stock_reminder_quantity' => 8,
                'upc' => 1465006,
                'selling_price' => 970,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Sporty Track pants',
                'description' => 'description'
            ],

            //-----------------------
            //for variant product 01
            //-----------------------
            [
                'product_id' => 11,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 8266242588,
                'selling_price' => 355.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'T Shirt for man-Red-m',
                'description' => 'description'
            ],
            [
                'product_id' => 11,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 3517915784,
                'selling_price' => 370.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'T Shirt for man-Red-L',
                'description' => 'description'
            ],

            //-----------------------
            //for variant product 02
            //-----------------------
            [
                'product_id' => 12,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 8091567543,
                'selling_price' => 250.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Cotton Short Sleeve T-shirt-L-white',
                'description' => ''
            ],
            [
                'product_id' => 12,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 3874800541,
                'selling_price' => 350.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Cotton Short Sleeve T-shirt-L-Red',
                'description' => ''
            ],
            [
                'product_id' => 12,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 5325914896,
                'selling_price' => 230.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Cotton Short Sleeve T-shirt-m-white',
                'description' => ''
            ],


            //-----------------------
            //single product
            //-----------------------
            [
                'product_id' => 13,
                'tax_id' => 3,
                'stock_reminder_quantity' => 8,
                'upc' => 1365006,
                'selling_price' => 2500,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Watch',
                'description' => 'description'
            ],
            [
                'product_id' => 14,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 1400006,
                'selling_price' => 46200,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'HP 15s-fq3617TU Celeron N4500 15.6" FHD Laptop',
                'description' => 'description'
            ],
            [
                'product_id' => 15,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 1500006,
                'selling_price' => 87000,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'HP Probook 450 G8 Core i5 11th Gen 512GB SSD 15.6 inch FHD Laptop',
                'description' => 'description'
            ],
            [
                'product_id' => 16,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 1600006,
                'selling_price' => 120000,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'MacBook Pro',
                'description' => 'description'
            ],
            [
                'product_id' => 17,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 10700006,
                'selling_price' => 70000,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Dell Inspiron 15-5570 8th Gen Core i3 Price in Bangladesh 2023',
                'description' => 'description'
            ],
            [
                'product_id' => 18,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 1800006,
                'selling_price' => 35000,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Smart phone',
                'description' => 'description'
            ],
            [
                'product_id' => 19,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 1900006,
                'selling_price' => 65000,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Smart watch',
                'description' => 'description'
            ],
            [
                'product_id' => 20,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 2000006,
                'selling_price' => 2500,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Man Nike Cap',
                'description' => 'description'
            ],
            [
                'product_id' => 21,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 2100006,
                'selling_price' => 180,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Man Addidas Cap',
                'description' => 'description'
            ],
            [
                'product_id' => 22,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 22500006,
                'selling_price' => 1500,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Jeans Short Pant',
                'description' => 'description'
            ],
            [
                'product_id' => 23,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 2300006,
                'selling_price' => 350,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Leather Wallet',
                'description' => 'description'
            ],
            [
                'product_id' => 24,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 2400006,
                'selling_price' => 2500,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Female Scarf',
                'description' => 'description'
            ],
            [
                'product_id' => 25,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 2500006,
                'selling_price' => 650,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Polarized Sunglasses Men Women Frame Sun Glasses',
                'description' => 'description'
            ],
            [
                'product_id' => 26,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 2600006,
                'selling_price' => 800,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Premium Frame Sunglass',
                'description' => 'description'
            ],
        ];

        Variant::query()->insert($variants);
    }

}