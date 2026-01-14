import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {CATEGORIES} from "../../Config/ApiUrl-CP";
import {createdBy} from "../../../store/Tenant/Mixins/CreatedByMixin";
import { FormMixin } from "../../../core/mixins/form/FormMixin";
import SelectableStatusMixin from "../../Helper/SelectableOptions/SelectableStatusMixin";

export default {
    mixins: [DatatableHelperMixin, createdBy, FormMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: CATEGORIES,
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
                        title: this.$t('category_sub_category'),
                        type: 'expandable-column',
                        key: 'sub_categories',
                        className: 'secondary',
                        componentName: 'sub-category-details',
                        showTitle: this.$t('show'),
                        hideTitle: this.$t('hide'),
                        showIcon: 'eye',
                        hideIcon: 'eye-off',
                        modifier: (value) => {
                            const subCategoriesLength = value.length;
                            return {
                                prefixData: `${subCategoriesLength === 0 ? '-' : subCategoriesLength}`,
                                title: this.$t('show'),
                                expandable: subCategoriesLength >= 1,
                                button: subCategoriesLength >= 1,
                                visible: subCategoriesLength >= 1,
                            };
                        }
                    },
                    {
                        title: this.$t('description'),
                        type: 'custom-html',
                        key: 'description',
                        modifier: (value = 'temp value') => {
                           return `<span>${value || ''}</span>`;
                        }
                    }, 
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value) =>
                            `<small class="text-capitalize bg-${value.translated_name === 'Inactive' ? 'danger' : 'success'} px-3 py-1 text-white" style="border-radius: 8rem;">${value.translated_name}</small>`},
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        name: 'edit',
                        component: 'app-category-modal',
                        modalId: 'category-modal',
                        modifier: () => this.$can('update_categories')
                    },
                    {
                        title: this.$t('deactivate'),
                        type: 'status_change',
                        name: 'deactivate',
                        modifier: (value) => value.status.translated_name === 'Active',
                    },
                    {
                        title: this.$t('activate'),
                        type: 'status_change',
                        name: 'activate',
                        modifier: (value) => value.status.translated_name !== 'Active',
                    },
                    {
                        title: this.$t('add_sub_category'),
                        icon: 'edit',
                        type: 'modal',
                        name: 'add_sub_category',
                        component: 'app-sub-category-modal',
                        modalId: 'sub-category-modal',
                        modifier: () => this.$can('create_sub_categories')
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        name: 'delete',
                        url: CATEGORIES,
                        component: 'app-category-modal',
                        modalId: 'category-modal',
                        modifier: () => this.$can('delete_categories')
                    },

                ],
                filters: [
                    {
                        title: this.$t('created_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    this.getStatusFilterOptions('category'),
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                search: true,
            },
        }
    },
}
