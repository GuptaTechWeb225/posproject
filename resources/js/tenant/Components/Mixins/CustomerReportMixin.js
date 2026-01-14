import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import { CUSTOMER_REPORT } from "../../Config/ApiUrl-CPB";
import { axiosGet, urlGenerator} from "../../../common/Helper/AxiosHelper";
import { SelectableBranches } from "../../../store/Tenant/Mixins/BranchMixin";
import { SelectableSuppliers } from "../../../store/Tenant/Mixins/SupplierSelectableMixin";
import { SelectableReceivedBy } from "../../../store/Tenant/Mixins/ReceivedByMixin";
import SelectableStatusMixin from "../../Helper/SelectableOptions/SelectableStatusMixin";
import { mapGetters } from "vuex";
import { numberWithCurrencySymbol } from "../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableBranches, SelectableSuppliers, SelectableReceivedBy, SelectableStatusMixin],
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
                        title: this.$t('name'),
                        type: 'text',
                        key: 'full_name',
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'component',
                        key: 'orders',
                        componentName: 'app-branch-warehouse',
                    },
                    {
                        title: this.$t('email'),
                        type: 'text',
                        key: 'email',
                    },
                    {
                        title: this.$t('customer_group'),
                        type: 'object',
                        key: 'customer_group',
                        modifier: value => value ? value.name : ''
                    },
                    {
                        title: this.$t('total_purchase'),
                        type: 'custom-html',
                        key: 'total_purchase',
                        titleAlignment: 'right',
                        modifier: value => value ? `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                    {
                        title: this.$t('total_due'),
                        type: 'custom-html',
                        key: 'total_due',
                        titleAlignment: 'right',
                        modifier: value => value ? `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                    {
                        title: this.$t('total_paid'),
                        type: 'custom-html',
                        titleAlignment: 'right',
                        modifier: (value, row) => row ? `<p class="text-right mb-0">${numberWithCurrencySymbol(row.total_purchase - row.total_due )}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                ],

                filters: [
                    {
                        title: this.$t('created_date'),
                        type: 'range-picker',
                        key: 'date',
                        option: ['today', 'last7Days', 'lastMonth', 'thisMonth']
                    },
                    {
                        title: this.$t('customers'),
                        type: 'search-and-select-filter',
                        key: "customer",
                        settings: {
                            url: urlGenerator('app/selectable-customers'),
                            modifire: ({id, full_name}) => ({id, full_name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_first_or_last_name',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'full_name'
                    },
                    {
                        title: this.$t('created_by'),
                        type: 'search-and-select-filter',
                        key: "created_by",
                        settings: {
                            url: urlGenerator('app/selectable/users'),
                            modifire: ({id, full_name}) => ({id, full_name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_first_or_last_name',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'full_name'
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
        this.updateUrl(CUSTOMER_REPORT);
    },
    watch: {
        getBranchOrWarehouseId(){
            this.updateUrl(CUSTOMER_REPORT);
        },
    },
}