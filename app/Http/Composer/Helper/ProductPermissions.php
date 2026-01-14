<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class ProductPermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('product_list'),
                'url' => route('support.products.list',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_products', 'update_products', 'delete_products'
                ]),
            ],
            [
                'name' => __t('add_product'),
                'url' => route('support.products.create',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'create_products'
                ]),
            ],
            [
                'name' => __t('groups'),
                'url' => route('support.group.lists',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_groups',
                    'create_groups',
                    'update_groups',
                    'delete_groups'
                ]),
            ],
            [
                'name' => __t('categories'),
                'url' => route('support.category.lists',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_categories',
                    'create_categories',
                    'update_categories',
                    'delete_categories',
                    'change_category_status'
                ]),
            ],
            [
                'name' => __t('brands'),
                'url' => route('support.brands',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_brands',
                    'create_brands',
                    'update_brands',
                    'delete_brands'
                ]),
            ],
            [
                'name' => __t('variant_attributes'),
                'url' => route('support.attributes',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_attributes',
                    'create_attributes',
                    'update_attributes',
                    'delete_attributes',
                    'import_attributes'
                ]),
            ],
            [
                'name' => __t('units'),
                'url' => route('support.unit.lists',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_unit',
                    'create_unit',
                    'update_unit',
                    'delete_unit',
                    'change_unit_status'
                ]),
            ],
        ];
    }

    public function canVisit(): bool
    {
        return authorize_any([
            'products_manage', //without defining route
            'view_groups', 'categories_manage', 'view_brands', 'create_brands',
            'update_brands', 'delete_brands', 'change_unit_status', 'view_unit',
            'view_attributes'
        ]);
    }
}
