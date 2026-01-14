import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import { PRODUCT_STOCK_REPORT } from "../../Config/ApiUrl-CPB";
import {axiosGet, urlGenerator} from "../../../common/Helper/AxiosHelper";
import {SelectableBranches} from "../../../store/Tenant/Mixins/BranchMixin";
import {SelectableSuppliers} from "../../../store/Tenant/Mixins/SupplierSelectableMixin";
import {SelectableReceivedBy} from "../../../store/Tenant/Mixins/ReceivedByMixin";
import {mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableBranches, SelectableSuppliers, SelectableReceivedBy],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                customerGroup: null,
                columns: [
                    {
                        title: this.$t('variant_name'),
                        type: 'custom-html',
                        key: 'variant',
                        modifier: value => `<p class="pb-0">${value ? value.name : '-'}</p>`
                    },
                    {
                        title: this.$t('upc'),
                        type: 'custom-html',
                        key: 'variant',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right pb-0">${value.upc ?? '-'}</p>`
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<p class="pb-0">${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></p>` : ''
                    },
                    {
                        title: this.$t('available_qty'),
                        type: 'custom-html',
                        key: 'available_qty',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right pb-0">${value ? value : 0}</p>`,
                    },
                    {
                        title: this.$t('avg_purchase_price'),
                        type: 'custom-html',
                        key: 'avg_purchase_price',
                        titleAlignment: 'right',
                        modifier: value => value ? `<p class="text-right pb-0">${numberWithCurrencySymbol(value)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                    {
                        title: this.$t('selling_price'),
                        type: 'custom-html',
                        key: 'variant',
                        titleAlignment: 'right',
                        modifier: value => value ? `<p class="text-right pb-0">${numberWithCurrencySymbol(value.selling_price)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                    {
                        title: this.$t('stock_worth'),
                        type: 'custom-html',
                        key: '',
                        titleAlignment: 'right',
                        modifier: (value,row) => row ? `<p class="text-right pb-0">${numberWithCurrencySymbol(row.available_qty * row.variant.selling_price)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                ],
                filters: [
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'search-and-select-filter',
                        key: 'branch_or_warehouse',
                        settings: {
                            url: urlGenerator('app/selectable-branches-or-warehouses'),
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
    created() {},
    computed:{
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted(){
        this.updateUrl(PRODUCT_STOCK_REPORT);
    },
    watch: {
        getBranchOrWarehouseId(){
            this.updateUrl(PRODUCT_STOCK_REPORT);
        },
    },
}