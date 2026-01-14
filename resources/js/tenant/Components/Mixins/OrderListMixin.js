import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {ORDER_MAX_MIN_PRICE} from "../../Config/ApiUrl-CP";
import {CUSTOMER_ORDERS} from "../../Config/ApiUrl-CPB";
import SelectableStatusMixin from "../../Helper/SelectableOptions/SelectableStatusMixin";
import {formatDateToLocal, numberWithCurrencySymbol} from "../../Helper/Helper";
import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('sales_report'),
                url: CUSTOMER_ORDERS,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                tableShadow: false,
                numberWithCurrencySymbol,
                columns: [
                    {
                        title: this.$t('invoice_id'),
                        type: 'text',
                        key: 'invoice_number',
                    },
                    {
                        title: this.$t('date'),
                        type: 'custom-html',
                        key: 'ordered_at',
                        modifier: value => value ? `<span>${formatDateToLocal(value)}</span>` : ''
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<span>${value.name} <span class="text-${value.type.toLowerCase() === 'branch' ? 'warning' : 'info'}">(${value.type[0].toUpperCase()})</span></span>` : ''
                    },
                    {
                        title: this.$t('cash_counter'),
                        type: 'custom-html',
                        key: 'cash_register',
                        modifier: value => value ? `${value.name}` : ''
                    },
                    {
                        title: this.$t('sold_by'),
                        type: 'object',
                        key: 'created_by',
                        modifier: (created_by) => (created_by?.full_name)
                    },
                    {
                        title: this.$t('customer'),
                        type: 'object',
                        key: 'customer',
                        modifier: (customer) => (customer?.full_name)
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
                        title: this.$t('discount'),
                        type: 'custom-html',
                        key: 'discount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>`
                    },
                    {
                        title: this.$t('tax_amount'),
                        type: 'custom-html',
                        key: 'total_tax',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>`
                    },

                    {
                        title: this.$t('paid'),
                        type: 'custom-html',
                        key: 'paid_amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>`
                    },
                    {
                        title: this.$t('due'),
                        type: 'custom-html',
                        key: 'due_amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>`
                    },
                    {
                        title: this.$t('grand_total'),
                        type: 'custom-html',
                        key: 'grand_total',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>`
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('due_receive'),
                        type: 'due_receive',
                        modifier: (row) => {
                            if (this.$can('info_due')) {
                                return row.due_amount > 0 ? true : false
                            }else{
                                return false
                            }
                        }
                    }
                ],
                filters: [
                    {
                        title: this.$t('created_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    {
                        title: "Price Range",
                        type: "range-filter",
                        key: "range_filter",
                        maxTitle: "Max usd",
                        minTitle: "Min usd",
                        sign: "$",
                        minRange: 0,
                        maxRange: 0,
                    },
                    this.getStatusFilterOptions('order'),
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                search: true,
            },
            orderMaxMinPriceUrl: ORDER_MAX_MIN_PRICE
        }
    },
    methods: {
        getMaxMinOrderPrice() {
            axiosGet(this.orderMaxMinPriceUrl).then((res) => {
                this.options?.filters.find(({key}) => {
                    if (key === 'range_filter') {
                        Object.assign(this.options.filters[1], res.data)
                    }
                })
            })
        },
        updateUrl() {
            this.options.url = `${CUSTOMER_ORDERS}/${this.props}`
            this.orderMaxMinPriceUrl = `${ORDER_MAX_MIN_PRICE}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.$hub.$emit(`reload-${this.table_id}`)
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted() {
        this.updateUrl();
    },
    watch: {
        getBranchOrWarehouseId(new_id) {
            this.updateUrl();
        },
    },
    created() {
        this.orderMaxMinPriceUrl = `${ORDER_MAX_MIN_PRICE}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
        this.getMaxMinOrderPrice();
    }
}
