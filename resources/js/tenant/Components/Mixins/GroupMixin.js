import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {GROUPS} from "../../Config/ApiUrl-CP";
import {createdBy} from "../../../store/Tenant/Mixins/CreatedByMixin";

export default {
    mixins: [DatatableHelperMixin, createdBy],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: GROUPS,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('group_name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('total_items'),
                        type: 'custom-html',
                        key: 'products_count',
                        titleAlignment: 'right',
                        modifier: count => `<p class="text-right mb-0">${count}</p>`,
                    },
                    {
                        title: this.$t('description'),
                        type: 'component',
                        key: 'description',
                        componentName: 'app-group-description'
                    },
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
                        component: 'app-group-modal',
                        modalId: 'group-modal',
                        modifier: () => this.$can('update_groups')
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        name: 'delete',
                        url: GROUPS,
                        component: 'app-group-modal',
                        modalId: 'group-modal',
                        modifier: () => this.$can('delete_groups'),
                    },

                ],
                filters: [
                    {
                        title: this.$t('created_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
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
