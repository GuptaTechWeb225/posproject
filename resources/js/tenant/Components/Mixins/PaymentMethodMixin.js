import {PAYMENT_METHOD} from "../../Config/ApiUrl-CPB";

export default {
    data() {
        return {
            options: {
                url: PAYMENT_METHOD,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                tableShadow:false,
                tablePaddingClass:'p-0',
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value) => {
                            return `<span class="badge badge-pill badge-${value.class}">${value.translated_name}</span>`;
                        }
                    },
                    {
                        title: this.$t('created_by'),
                        type: 'custom-html',
                        key: 'created_by',
                        modifier: (value) => {
                            return `<span>${value.full_name}</span>`;
                        }
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
                        title: this.$t('alias'),
                        type: 'text',
                        key: 'alias'
                    },
                    {
                        title: this.$t('rounded_to'),
                        type: 'custom-html',
                        key: 'rounded_to',
                        modifier: (value) => {
                            return this.$t(value)
                        }
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
                        "title": this.$t('status'),
                        "type": "radio",
                        "key": "statusId",
                        "header": {
                            "description": this.$t('data_able_status_filter_description')
                        },
                        "option": [],
                        listValueField: 'name'
                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'edit',
                        component: 'tax-create-edit-modal',
                        modalId: 'tax-create-edit-modal',
                        modifier: row => {
                            return this.$can('update_payment_method')
                        },
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: PAYMENT_METHOD,
                        name: 'delete',
                        modifier: row => {
                            const defaultPaymentMethods = ["credit", "cash"];
                            if (defaultPaymentMethods.includes(row.alias.toLowerCase())) return false;
                            return this.$can('delete_payment_method')
                        },
                    }
                ],
            },
        }
    }
}
