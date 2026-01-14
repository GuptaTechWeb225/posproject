import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {COUPONS} from "../../Config/ApiUrl-CPB";
import {formatDateToLocal} from "../../../common/Helper/Support/DateTimeHelper";
export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                tablePaddingClass:'p-0',
                tableShadow: false,
                name: this.$t('tenant_groups'),
                url: COUPONS,
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
                        title: this.$t('start_at'),
                        type: 'custom-html',
                        key: 'start_at',
                        modifier: (value) => {
                            return formatDateToLocal(value, true);
                        }
                    },
                    {
                        title: this.$t('end_at'),
                        type: 'custom-html',
                        key: 'end_at',
                        modifier: (value) => {
                            return formatDateToLocal(value, true);
                        }
                    },
                    {
                        title: this.$t('code'),
                        type: 'text',
                        key: 'code',
                    },
                    {
                        title: this.$t('discount'),
                        type: 'text',
                        key: 'discount',
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
                        component: 'app-coupon-modal',
                        modalId: 'coupon-modal',
                        modifier: row => {
                            return  this.$can('update_coupon')
                        },
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        name: 'delete',
                        url: COUPONS,
                        component: 'app-coupon-modal',
                        modalId: 'coupon-modal',
                        modifier: row => {
                            return  this.$can('delete_coupon')
                        },
                    },

                ],
                filters: [],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                search: true,
            },
        }
    }
}
