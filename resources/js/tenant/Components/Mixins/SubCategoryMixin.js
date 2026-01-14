import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {SUB_CATEGORIES} from "../../Config/ApiUrl-CP";
import {createdBy} from "../../../store/Tenant/Mixins/CreatedByMixin";
export default {
    mixins: [DatatableHelperMixin, createdBy],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: SUB_CATEGORIES,
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
                        title: this.$t('categories'),
                        type: 'component',
                        key: 'categories',
                        componentName: 'app-sub-categories'
                    },
                    {
                        title: this.$t('created_by'),
                        type: 'object',
                        key: 'created_by',
                        modifier: value => {
                            return value.full_name
                        }
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        name: 'edit',
                        component: 'app-sub-category-modal',
                        modalId: 'sub-category-modal',
                        modifier: row => {
                            return  this.$can('update_sub_category')
                        },
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        name: 'delete',
                        url: SUB_CATEGORIES,
                        component: 'app-category-modal',
                        modalId: 'category-modal',
                        modifier: row => {
                            return  this.$can('delete_sub_category')
                        },
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
                        title: this.$t('created_by'),
                        type: "drop-down-filter",
                        key: "created_by",
                        option: [],
                    },
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
