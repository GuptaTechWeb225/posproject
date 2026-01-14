
import {STOCK} from "../../../Config/ApiUrl-CP";
import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import SelectableStatusMixin from "../../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapActions, mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            // noBranchSelected: false,
            options: {
                name: this.$t('stock'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('products'),
                        type: 'component',
                        //variant.thumbnail
                        key: 'name',
                        componentName: 'stock-profile',
                    },
                    {
                        title: this.$t('quantity'),
                        type: 'custom-html',
                        key: 'available_qty',
                        titleAlignment: 'right',
                        modifier: (available_qty) => available_qty ? `<p class="text-right">${available_qty}</p>` :'-',
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<p>${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></p>` : ''
                    },
                    {
                        title: this.$t('avg_purchase_price'),
                        type: 'custom-html',
                        key: 'avg_purchase_price',
                        titleAlignment: 'right',
                        modifier: (avg_purchase_price) => avg_purchase_price ? `<p class="text-right">${numberWithCurrencySymbol(avg_purchase_price)}</p>` : '',
                    },
                    {
                        title: this.$t('selling_price'),
                        type: 'custom-html',
                        key: 'variant',
                        titleAlignment: 'right',
                        modifier: (row) => row.selling_price ? `<p class="text-right">${numberWithCurrencySymbol(row.selling_price)}</p>` : '-'
                    },
                    {
                        title: this.$t('brand'),
                        type: 'custom-html',
                        key: 'variant',
                        modifier: value => `<p>${ value?.product?.brand?.name ?? '-' }</p>` ?? '-'
                    },
                    {
                        title: this.$t('category'),
                        type: 'custom-html',
                        key: 'variant',
                        modifier: value => `<p>${ value?.product?.category?.name ?? '-' }</p>` ?? '-'
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    }
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('show_stock_overview'),
                        name: 'show_stock_overview',
                        modifier:() => this.$can('view_stock_overview')
                    },
                    {
                        title: this.$t('stock_adjust'),
                        name: 'stock_adjust',
                        icon: 'change',
                        type: 'modal',
                        // will be created later
                        component: 'app-stock-adjust-modal',
                        modalId: 'stock-adjust-modal',
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
                        title: this.$t('category'),
                        type: 'search-and-select-filter',
                        key: 'category',
                        settings: {
                            url: urlGenerator('app/selectable-categories'),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_name',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'name'
                    },
                    {
                        title: this.$t('brand'),
                        type: 'search-and-select-filter',
                        key: 'brand',
                        settings: {
                            url: urlGenerator('app/selectable-brands'),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_name',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'name'
                    },
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

    methods: {
        ...mapActions(['setStocksByBranchOrWarehouseId']),
    },
    computed:{
        ...mapGetters(['getBranchOrWarehouseId', 'getBranchOrWarehouseName', 'getStocksByBranchOrWarehouse']),
    },
    mounted(){
        this.updateUrl(STOCK);
    },
    watch: {
        getBranchOrWarehouseId: {
            immediate: false,
            handler() {
                this.updateUrl(STOCK);
            }
        }
    },
}
