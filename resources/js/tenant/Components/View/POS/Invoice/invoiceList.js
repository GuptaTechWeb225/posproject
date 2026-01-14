import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {INVOICE_LIST, INVOICE_VIEW, ORDER_MAX_MIN_PRICE} from "../../../../Config/ApiUrl-CP";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import SelectableStatusMixin from "../../../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";


export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('invoice'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('invoice_number'),
                        type: 'component',
                        key: 'invoice_number',
                        componentName: 'app-invoice-number'
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<span>${value.name} <span class="text-${value.type.toLowerCase() === 'branch' ? 'warning' : 'info'}">(${value.type[0].toUpperCase()})</span></span>` : ''
                    },
                    {
                        title: this.$t('biller'),
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
                        title: this.$t('total_amount'),
                        type: 'custom-html',
                        key: 'grand_total',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value ?? 0)}</p>`,
                    },
                    {
                        title: this.$t('paid_amount'),
                        type: 'custom-html',
                        key: 'paid_amount',
                        titleAlignment: 'right',
                        modifier: (_, row) => `<p class="text-right mb-0">
                                ${(row.paid_amount && row.change_return) ? numberWithCurrencySymbol((row.paid_amount - row.change_return) ?? 0) : numberWithCurrencySymbol(0)}
                                <br><small>${this.$t('paid')} : ${numberWithCurrencySymbol(row.paid_amount)}</small>
                                <br><small>${this.$t('returned')} : ${numberWithCurrencySymbol(row.change_return)}</small>
                            </p>
                        `,
                    },
                    {
                        title: this.$t('due_amount'),
                        type: 'custom-html',
                        key: 'due_amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${numberWithCurrencySymbol(value ?? 0)}</p>`,
                    },
                    {
                        title: this.$t('payment_note'),
                        type: 'component',
                        componentName: 'app-payment-note-column',
                        key: 'payment_note',
                    },
                    {
                        title: this.$t('payment_methods'),
                        type: 'object',
                        key: 'transactions',
                        modifier: (transactions) => transactions.map(transaction => {
                            return transaction?.payment_method?.name ?? ''
                        }).join(', ')
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
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('view'),
                        type: 'view'
                    },
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
                        title: this.$t('issue_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    {
                        title: this.$t('customer'),
                        type: 'search-and-select-filter',
                        key: 'customers',
                        settings: {
                            url: urlGenerator('app/selectable-customers'),
                            modifire: ({id, full_name}) => ({id, full_name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'full_name'
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
            orderMaxMinPriceUrl: ''
        }
    },
    methods: {
        getMaxMinOrderPrice() {
            axiosGet(this.orderMaxMinPriceUrl).then((res) => {
                this.options?.filters.find(({key}) => {
                    if (key === 'range_filter') {
                        Object.assign(this.options.filters[2], res.data)
                    }
                })
            })
        },

        updateOrderMaxMinPriceUrl() {
            this.orderMaxMinPriceUrl = `${ORDER_MAX_MIN_PRICE}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
        },


    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted() {
        this.updateUrl(INVOICE_LIST, this.updateOrderMaxMinPriceUrl);
    },
    watch: {
        getBranchOrWarehouseId() {
            this.updateUrl(INVOICE_LIST, this.updateOrderMaxMinPriceUrl);
            this.getMaxMinOrderPrice();
            this.$hub.$emit(`reload-${this.table_id}`)
        },
    }
}
