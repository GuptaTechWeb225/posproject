import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {CASH_COUNTER_REPORT} from "../../../../Config/ApiUrl-CP";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";
import {mapGetters} from "vuex";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            numberWithCurrencySymbol,
            options: {
                name: this.$t('cash_counter'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('cash_counter'),
                        type: 'object',
                        key: 'cash_register',
                        modifier: (row) => row.name
                    },
                    {
                        title: this.$t('payment_method'),
                        type: 'custom-html',
                        key: 'payment_method',
                        modifier: (value) => {
                            return  value?.alias ? `<span class="badge badge-pill badge-${value?.alias === 'credit' ? 'warning' : 'success'}">${value?.name}</span>` : '';
                        }
                    },
                    {
                        title: this.$t('sold_by'),
                        type: 'object',
                        key: 'sold_by',
                        modifier: (row) => row.full_name
                    },
                    {
                        title: this.$t('sold_to'),
                        type: 'object',
                        key: 'order',
                        modifier: (row) => row?.customer?.full_name
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        modifier: (value) => {
                            return `<span class="badge badge-pill badge-${value.class}">${value.translated_name}</span>`;
                        }
                    },
                    {
                        title: this.$t('opening_amount'),
                        type: 'custom-html',
                        key: 'opening_balance',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value || 0)}</p>`
                    },
                    {
                        title: this.$t('sale_amount'),
                        type: 'custom-html',
                        key: 'cash_sales',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value || 0)}</p>`
                    },
                    {
                        title: this.$t('closing_amount'),
                        type: 'custom-html',
                        key: 'closing_balance',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value || 0)}</p>`
                    },
                    {
                        title: this.$t('difference'),
                        type: 'custom-html',
                        key: 'difference',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value || 0)}</p>`
                    }
                ],
                actionType: "default",
                actions: [],
                filters: [
                    {
                        title: this.$t('date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    }
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


    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted() {
        this.updateUrl(CASH_COUNTER_REPORT);
    },
    watch: {
        getBranchOrWarehouseId() {
            this.updateUrl(CASH_COUNTER_REPORT);
        },
    },
}
