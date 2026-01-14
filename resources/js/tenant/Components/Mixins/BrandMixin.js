import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {BRAND} from "../../Config/ApiUrl-CP";
import {createdBy} from "../../../store/Tenant/Mixins/CreatedByMixin";

export default {
    mixins: [DatatableHelperMixin, createdBy],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: BRAND,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('brand'),
                        type: 'component',
                        key: 'name',
                        componentName: 'brand-profile',
                    },
                    {
                        title: this.$t('description'),
                        type: 'text',
                        key: 'description',
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                        isVisible: true
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-tenant-group-modal',
                        modalId: 'app-modal',
                        modifier: () => this.$can('update_brands')
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: BRAND,
                        name: 'delete',
                        modifier: (row) => this.$can('delete_brands') && !parseInt(row.is_default)
                    }
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
                orderBy: 'desc',
            },
        }
    },
}
