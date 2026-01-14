import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {SMS_TEMPLATE} from "../../Config/ApiUrl-CPB";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: SMS_TEMPLATE,
                showHeader: true,
                tableShadow: false,
                tablePaddingClass: 'p-0',
                columns: [
                    {
                        title: this.$t('content'),
                        type: 'custom-html',
                        key: 'content',
                        modifier:(content) => content
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
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: SMS_TEMPLATE,
                        name: 'delete',
                        modifier: (row) => this.$can('delete_tenant_groups') && !parseInt(row.is_default)

                    }
                ],
                filters: [

                ],
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
