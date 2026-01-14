import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import { LOT_REPORT} from "../../Config/ApiUrl-CPB";
import {axiosGet, urlGenerator} from "../../../common/Helper/AxiosHelper";
import {SelectableBranches} from "../../../store/Tenant/Mixins/BranchMixin";
import {SelectableSuppliers} from "../../../store/Tenant/Mixins/SupplierSelectableMixin";
import {SelectableReceivedBy} from "../../../store/Tenant/Mixins/ReceivedByMixin";
import SelectableStatusMixin from "../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapGetters} from "vuex";
import {formatDateToLocal, numberWithCurrencySymbol} from "../../Helper/Helper";

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
                        title: this.$t('reference_no'),
                        type: 'custom-html',
                        key: 'reference_no',
                        modifier: reference_no => `<p class="pb-0">${reference_no ?? '-'}</p>`
                    },
                    {
                        title: this.$t('Date'),
                        type: 'custom-html',
                        key: 'created_at',
                        modifier: created_at_date => `<p class="pb-0">${created_at_date ? formatDateToLocal(created_at_date) : '-'}</p>`
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<p>${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></p>` : ''
                    },
                    {
                        title: this.$t('supplier'),
                        type: 'custom-html',
                        key: 'supplier',
                        modifier: (supplier) => supplier && `<p class="text-primary pb-0">${supplier.name}</p>`,
                    },
                    {
                        title: this.$t('total_items'),
                        type: 'custom-html',
                        key: 'total_unit',
                        titleAlignment: 'right',
                        modifier: total_items => `<p class="text-right pb-0">${total_items ?? '-'}</p>`
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
                        title: this.$t('discount_amount'),
                        type: 'custom-html',
                        key: 'discount_amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right pb-0">${numberWithCurrencySymbol(value ?? 0)}</p>`
                    },
                    {
                        title: this.$t('other_cost'),
                        type: 'custom-html',
                        key: 'other_charge',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right pb-0">${numberWithCurrencySymbol(value ?? 0)}</p>`,
                    },
                    {
                        title: this.$t('grand_total'),
                        type: 'custom-html',
                        key: 'total_amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right pb-0">${numberWithCurrencySymbol(value ?? 0)}</p>`,
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
                    this.getStatusFilterOptions('purchase'),

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
        this.updateUrl(LOT_REPORT);
    },
    watch: {
        getBranchOrWarehouseId(new_id){
            this.updateUrl(LOT_REPORT);
        },
    },
}
