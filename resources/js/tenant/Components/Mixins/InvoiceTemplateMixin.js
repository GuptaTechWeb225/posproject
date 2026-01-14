import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {INVOICE_TEMPLATE} from "../../Config/ApiUrl-CPB";
import {toUpper} from "lodash";
export default {
    mixins: [DatatableHelperMixin],
    data() {

        return {
            options: {
                name: this.$t('tenant_groups'),
                url: INVOICE_TEMPLATE,
                showHeader: true,
                showCount: true,
                showClearFilter: true,
                tableShadow:false,
                tablePaddingClass:'p-0',
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
                        modifier: (value) => value ? this.$t(value) : '-',
                    },
                    {
                        title: this.$t('default'),
                        type: 'custom-html',
                        key: 'is_default',
                        modifier: (value) => {
                            return parseInt(value) === 1 ? this.$t("yes") : this.$t("no");
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
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        name: 'delete',
                        url: INVOICE_TEMPLATE,
                        modifier: (row) => this.$can('delete_tenant_groups') && !parseInt(row.is_default),
                    },

                ],

                filters: [
                    {
                        title: this.$t('email'),
                        type: "",
                        key: "email",
                        option: []
                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                search: true,
            },
        }

    }
}
