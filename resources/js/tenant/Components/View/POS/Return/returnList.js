import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {ORDER_MAX_MIN_PRICE, RETURN_ORDER_LIST, RETURN_ORDER_MAX_MIN_PRICE} from "../../../../Config/ApiUrl-CP";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import SelectableStatusMixin from "../../../../Helper/SelectableOptions/SelectableStatusMixin";
import {toUpper} from "lodash";
import {mapGetters} from "vuex";
import { numberWithCurrencySymbol } from "../../../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('return'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('invoice_number'),
                        type: 'component',
                        componentName: 'app-return-invoice-number',
                        key: 'order',
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<p class="pb-0">${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></p>` : ''
                    },
                    {
                        title: this.$t('reference_no'),
                        type: 'custom-html',
                        key: 'reference_number',
                        titleAlignment: 'right',
                        modifier: (refNo) => `<p class="text-right pb-0">${refNo ?? '-'}</p>`,
                    },
                    {
                        title: this.$t('biller'),
                        type: 'custom-html',
                        key: 'created_by',
                        modifier: (created_by) => `<p class="pb-0">${ (created_by?.full_name) }</p>`
                    },
                    {
                        title: this.$t('return_type'),
                        type: 'custom-html',
                        key: 'type',
                        modifier: (type) => `<p class="pb-0">${ (type.toUpperCase()) }</p>`
                    },
                    {
                        title: this.$t('customer'),
                        type: 'custom-html',
                        key: 'customer',
                        modifier: (customer) => `<p class="pb-0">${ (customer?.full_name) }</p>`
                    },
                    {
                        title: this.$t('total_paid'),
                        type: 'custom-html',
                        key: 'sub_total',
                        titleAlignment: 'right',
                        modifier: (total_paid) => `<p class="text-right pb-0">${numberWithCurrencySymbol(total_paid ?? 0)}</p>`,
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
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                search: true,
            },
            returnOrderMaxMinPriceUrl:''
        }
    },
    methods: {
        getMaxMinOrderPrice() {
            axiosGet(this.returnOrderMaxMinPriceUrl).then((res) => {
                this.options?.filters.find(({key}) => {
                    if (key === 'range_filter') {
                        Object.assign(this.options.filters[2], res.data)
                    }
                })
            })
        },
        updateReturnOrderMaxMinPriceUrl() {
            this.returnOrderMaxMinPriceUrl = `${ORDER_MAX_MIN_PRICE}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
        }, 
    },

    computed:{
        ...mapGetters(['getBranchOrWarehouseId']),
    },

    mounted(){
        this.updateUrl(RETURN_ORDER_LIST, this.updateReturnOrderMaxMinPriceUrl);
    },

    watch: {
        getBranchOrWarehouseId(){
            this.updateUrl(RETURN_ORDER_LIST, this.updateReturnOrderMaxMinPriceUrl);
            this.getMaxMinOrderPrice();
            this.$hub.$emit(`reload-${this.table_id}`)
        },
    },

    created() {
        this.getMaxMinOrderPrice();
    }
}










