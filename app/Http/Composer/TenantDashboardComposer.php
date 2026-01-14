<?php


namespace App\Http\Composer;


use App\Helpers\Settings\SettingParser;
use App\Http\Composer\Helper\ContactPermissions;
use App\Http\Composer\Helper\ExpensePermissions;
use App\Http\Composer\Helper\InventoryPermissions;
use App\Http\Composer\Helper\PosPermissions;
use App\Http\Composer\Helper\ProductPermissions;
use App\Http\Composer\Helper\ReportPermissions;
use App\Http\Composer\Helper\SettingPermissions;
use App\Http\Composer\Helper\LogoIcon;
use Illuminate\View\View;

class TenantDashboardComposer
{
    public function compose(View $view)
    {

        ['logo' => $logo, 'icon' => $icon] = LogoIcon::new(true)
            ->logoIcon();

        $view->with([
            'permissions' => [
                [
                    'name' => __t('dashboard'),
                    'url' => route('tenant.dashboard', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                    'icon' => 'pie-chart',
                    'permission' => authorize_any([
                        'dashboard_tenant'
                    ])
                ],
                [
                    'name' => __t('products'),
                    'icon' => 'gift',
                    'id' => 'Products',
                    'subMenu' => ProductPermissions::new(true)->permissions(),
                    'permission' => ProductPermissions::new(true)->canVisit()
                ],
                [
                    'name' => __t('inventory'),
                    'icon' => 'database',
                    'id' => 'Inventory',
                    'subMenu' => InventoryPermissions::new(true)->permissions(),
                    'permission' => InventoryPermissions::new(true)->canVisit()
                ],
                [
                    'name' => __t('pos'),
                    'icon' => 'shopping-bag',
                    'id' => 'Pos',
                    'subMenu' => PosPermissions::new(true)->permissions(),
                    'permission' => PosPermissions::new(true)->canVisit()
                ],
                [
                    'name' => __t('expenses'),
                    'icon' => 'dollar-sign',
                    'id' => 'Expense',
                    'subMenu' => ExpensePermissions::new(true)->permissions(),
                    'permission' => ExpensePermissions::new(true)->canVisit()
                ],
                [
                    'name' => __t('contacts'),
                    'icon' => 'users',
                    'id' => 'Contacts',
                    'subMenu' => ContactPermissions::new(true)->permissions(),
                    'permission' => ContactPermissions::new(true)->canVisit()
                ],
                [
                    'name' => __t('reports'),
                    'icon' => 'activity',
                    'id' => 'Reports',
                    'subMenu' => ReportPermissions::new(true)->permissions(),
                    'permission' => ReportPermissions::new(true)->canVisit()
                ],
                [
                    'name' => __t('users_roles'),
                    'url' => $this->userUrl(),
                    'permission' => authorize_any(['view_users', 'view_roles']),
                    'icon' => 'user-check'
                ],
                [
                    'name' => __t('settings'),
                    'id' => 'app-general-settings',
                    'icon' => 'settings',
                    'subMenu' => SettingPermissions::new(true)->permissions(),
                    'permission' => SettingPermissions::new(true)->canVisit()
                ],

            ],
            'settings' => SettingParser::new(true)->getSettings(),
            'top_bar_menu' => [
                [
                    'optionName' => __t('my_profile'),
                    'optionIcon' => 'user',
                    'url' => route('user.profile', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name])
                ],
                [
                    'optionName' => __t('log_out'),
                    'optionIcon' => 'log-out',
                    'url' => request()->root() . '/admin/log-out'
                ],
            ],
            'logo' => $logo,
            'logo_icon' => $icon
        ]);
    }

    public function userUrl()
    {
        return route(
            'support.tenant.users',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }

    public function reportUrl()
    {
        return route(
            'support.reports',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }

    public function settingUrl()
    {
        return route(
            'support.tenant.settings',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }

}
