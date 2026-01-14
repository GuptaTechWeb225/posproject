import {TAX} from "../../Config/ApiUrl-CPB";
import GroupBy from "../View/Tax/GroupBy";

export default {
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: TAX,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('type'),
                        type: 'custom-html',
                        key: 'type',
                        modifier: (value) => {
                            return value === 'single_tax' ? this.$t("single_tax") : this.$t("multi_tax");
                        }
                    },
                    {
                        title: this.$t('percentage'),
                        type: 'custom-html',
                        key: 'percentage',
                        titleAlignment: 'right',
                        modifier: percentage => `<p class="text-right">${percentage ?? '-'}%</p>`,
                    },
                    {
                        title: this.$t('group_by'),
                        type: 'component',
                        key: 'tax_taxes',
                        componentName: GroupBy,
                    },
                    {
                        title: this.$t('default'),
                        type: 'custom-html',
                        key: 'is_default',
                        modifier: (value) => `<small class="text-capitalize bg-${+value === 0 ? 'danger' : 'success'} px-3 py-1 text-white" style="border-radius: 8rem;">${+value === 0 ? this.$t('no') : this.$t('yes')}</small>`
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                filters: [
                    {
                        title: this.$t('created_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    {
                        title: this.$t('type'),
                        type: "drop-down-filter",
                        key: "type",
                        option: [
                            {id: 'single_tax', name: this.$t('single_tax')},
                            {id: 'multi_tax', name: this.$t('multi_tax')},
                        ],
                        listValueField: 'name'
                    }
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'edit',
                        component: 'tax-create-edit-modal',
                        modalId: 'tax-create-edit-modal',
                        modifier: row => {
                            return this.$can('update_tax')
                        },
                    },
                    {
                        title: this.$t('make_default'),
                        type: 'default_status_change',
                        name: 'make_default',
                        modifier: (row) => +row.is_default !== 1,
                    }, 
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: TAX,
                        name: 'delete',
                        modifier: row => {
                            return this.$can('delete_tax') && !parseInt(row.is_default)
                        },
                    }
                ],
            },
        }
    },
}
