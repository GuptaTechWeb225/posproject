<?php

namespace Database\Seeders\Product\Traits;

use App\Models\Core\File\File;
use Illuminate\Support\Facades\DB;

trait ThumbnailSeederTrait
{
    public function thumbnailSeeder()
    {
        $thumbnails = [
            [
                'path' => '/images/product/belt.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 1,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/black_full_sleeves.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 2,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/black_t-shirt.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 3,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/black_tie.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 4,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/cotton_full_pant_woman.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 5,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/full_jeans_pant.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 6,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/hat.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 7,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/man_full_pant.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 8,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/narrow_three_quarter_pant.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 9,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/sporty_tracks.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 10,
                'type' => 'product_thumbnail'
            ],

            //for variant 01
            [
                'path' =>'/images/product/red_shirt_man-1.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 11,
                'type' => 'product_thumbnail'
            ],

            //for variant 02
            [
                'path' =>'/images/product/cotton_shor_sleeve.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 12,
                'type' => 'product_thumbnail'
            ],

            //-----------------------------
            // seed variant thumbnail
            //-----------------------------
            [
                'path' => '/images/product/belt.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 1,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/black_full_sleeves.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 2,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/black_t-shirt.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 3,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/black_tie.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 4,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/cotton_full_pant_woman.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 5,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/full_jeans_pant.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 6,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/hat.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 7,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/man_full_pant.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 8,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/narrow_three_quarter_pant.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 9,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/sporty_tracks.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 10,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/sporty_tracks.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 11,
                'type' => 'variant_thumbnail'
            ],

            //variant for product id 11
            [
                'path' =>'/images/product/red_shirt_man-1.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 11,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/red_shirt_man-1.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 12,
                'type' => 'variant_thumbnail'
            ],

            //variant for product id 12
            [
                'path' =>'/images/product/cotton_shor_sleeve.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 13,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/cotton_shor_sleeve.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 14,
                'type' => 'variant_thumbnail'
            ],
            [
                'path' =>'/images/product/cotton_shor_sleeve.png',
                'fileable_type' => 'App\Models\Pos\Product\Product\Variant',
                'fileable_id' => 15,
                'type' => 'variant_thumbnail'
            ],


            //-----------------------------
            //single product
            //-----------------------------
            [
                'path' =>'/images/product/watch.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 13,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/hp.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 14,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/hp_probook.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 15,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/mackbook_pro.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 16,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/dell_inspiron.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 17,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/mobile2.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 18,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/smartwatch.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 19,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/nike_cap.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 20,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/addidas_cap.jpg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 21,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/short_pant.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 22,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/leather_wallet.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 23,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/female_scarf.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 24,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/frame_sunglass.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 25,
                'type' => 'product_thumbnail'
            ],
            [
                'path' =>'/images/product/sunglass.jpeg',
                'fileable_type' => 'App\Models\Pos\Product\Product\Product',
                'fileable_id' => 26,
                'type' => 'product_thumbnail'
            ],

            //-----------------------------
            /// end .............
            //-----------------------------
        ];

        DB::table('files')->insert($thumbnails);
    }
}