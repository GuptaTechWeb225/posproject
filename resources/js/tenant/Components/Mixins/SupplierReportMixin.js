import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {SUPPLIER_REPORT} from "../../Config/ApiUrl-CPB";
import {axiosGet, urlGenerator} from "../../../common/Helper/AxiosHelper";
import {SelectableBranches} from "../../../store/Tenant/Mixins/BranchMixin";
import {SelectableSuppliers} from "../../../store/Tenant/Mixins/SupplierSelectableMixin";
import {SelectableReceivedBy} from "../../../store/Tenant/Mixins/ReceivedByMixin";
import SelectableStatusMixin from "../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../Helper/Helper";

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
                        key: 'name',
                    },
                    {
                        title: this.$t('supplier_no'),
                        type: 'text',
                        key: 'supplier_no',
                    }, 
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'component',
                        key: 'lots',
                        componentName: 'app-branch-warehouse',
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
                        title: this.$t('total_purchase'),
                        type: 'custom-html',
                        key: 'total_purchase',
                        titleAlignment: 'right',
                        modifier: value => value ? `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                ],

                filters: [ 
                    {
                        title: this.$t('supplier'),
                        type: 'search-and-select-filter',
                        key: 'supplier',
                        settings: {
                            url: urlGenerator('app/selectable-suppliers'),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_name',
                            loader: 'app-pre-loader',
                            modifire: (value) => (value ? {
                                id: value.id,
                                name: value?.name
                            } : null),
                        },
                        listValueField: 'name',
                    }, 
                    this.getStatusFilterOptions('supplier'),
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
    created() {
    }, 
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted() {
        this.updateUrl(SUPPLIER_REPORT);
    },
    watch: {
        getBranchOrWarehouseId() {
            this.updateUrl(SUPPLIER_REPORT);
        },
    },
}