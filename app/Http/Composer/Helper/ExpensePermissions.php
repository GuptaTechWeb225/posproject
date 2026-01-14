<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class ExpensePermissions
{
    use InstanceCreator;

    public function permissions(): array
    {
        return [
            [
                'name' => __t('expenses'),
                'url' => route('support.expense.lists',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_expenses','create_expenses', 'update_expenses', 'delete_expenses'
                ]),
            ],
            [
                'name' => __t('area_of_expense'),
                'url' => route('support.expense.area-of-expense',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_expense_areas','create_expense_areas', 'update_expense_areas', 'delete_expense_areas'
                ]),
            ],
        ];
    }

    public function canVisit(): bool
    {
        return authorize_any([
            'view_expenses', 'create_expenses', 'update_expenses', 'delete_expenses',
            'view_expense_areas', 'create_expense_areas', 'update_expense_areas', 'delete_expense_areas'
        ]);
    }
}
