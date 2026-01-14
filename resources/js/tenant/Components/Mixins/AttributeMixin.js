import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {createdBy} from "../../../store/Tenant/Mixins/CreatedByMixin";
import {ATTRIBUTE} from "../../Config/ApiUrl-CP";

export default {
    mixins: [DatatableHelperMixin, createdBy],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: ATTRIBUTE,
                showCount: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('variations'),
                        type: 'component',
                        key: 'variants',
                        componentName: 'variant-value',
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-tenant-group-modal',
                        modalId: 'app-modal',
                        modifier: () => this.$can('update_attributes'),
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: ATTRIBUTE,
                        name: 'delete',
                        modifier: (row) => this.$can('delete_attributes') && !parseInt(row.is_default)
                    }
                ],
                filters: [],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                search: true,
            },
        }
    },
}