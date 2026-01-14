import {LOT, STOCK} from "../../Config/ApiUrl-CP";
import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {SelectableBranches} from "../../../store/Tenant/Mixins/BranchMixin";
import {SelectableSuppliers} from "../../../store/Tenant/Mixins/SupplierSelectableMixin";
import {SelectableReceivedBy} from "../../../store/Tenant/Mixins/ReceivedByMixin";
import {urlGenerator} from "../../../common/Helper/AxiosHelper";
import SelectableStatusMixin from "../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapActions, mapGetters} from "vuex";
import {formatDateToLocal} from "../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableBranches, SelectableSuppliers, SelectableReceivedBy, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('lot'),
                url: LOT,
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [ 
                    {
                        title: this.$t('purchase_date'),
                        type: 'custom-html',
                        key: 'purchase_date',
                        modifier: (purchase_date) => purchase_date ? `<p class="pb-0">${formatDateToLocal(purchase_date)}</p>` : '-',
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<p class="pb-0">${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></span>` : ''
                    },
                    {
                        title: this.$t('lot_reference_no'),
                        type: 'custom-html',
                        key: 'reference_no',
                        titleAlignment: 'right',
                        modifier: (refNo) => `<p class="text-right pb-0">${refNo ?? '-'}</p>`,
                    },
                    {
                        title: this.$t('supplier'),
                        type: 'component',
                        key: 'supplier',
                        componentName: 'app-supplier-link'
                    },
                    {
                        title: this.$t('total_unit'),
                        type: 'custom-html',
                        key: 'total_unit',
                        titleAlignment: 'right',
                        modifier: (totalUnit) => `<p class="text-right pb-0">${totalUnit ?? '-'}</p>`,
                    },
                    {
                        title: this.$t('status'),
                        type: 'component',
                        componentName: 'app-lot-status',
                        key: 'statusId',
                        modifier: (status) => status && status
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('view'),
                        icon: 'show',
                        type: 'show',
                        name: 'view',
                        modifier: (row) => true
                    },
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'edit',
                        name: 'edit',
                        modifier: (row) => this.$can('update_lot') && row.status.translated_name.toLowerCase() !== 'delivered',
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: LOT,
                        name: 'delete',
                        modifier: row => this.$can('delete_customer_groups') && !parseInt(row.is_default)

                    }
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

    computed:{
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted(){
        this.updateUrl(LOT);
    },
    watch: {
        getBranchOrWarehouseId(){
            this.updateUrl(LOT);
        },
    },
}
