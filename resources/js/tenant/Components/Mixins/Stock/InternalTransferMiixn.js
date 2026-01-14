import {INTERNAL_TRANSFER, SELECTABLE_BRANCHES_OR_WAREHOUSES, STOCK} from "../../../Config/ApiUrl-CP";
import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import SelectableStatusMixin from "../../../Helper/SelectableOptions/SelectableStatusMixin";
import { mapGetters } from "vuex";
import {formatDateToLocal} from "../../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('internal_transfer'),
                // temporary
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('date'),
                        type: 'text',
                        key: 'date',
                        modifier: (adjustment_date) => formatDateToLocal(adjustment_date) ?? '--',
                    },
                    {
                        title: this.$t('quantity'),
                        name:'quantity',
                        type: 'custom-html',
                        key: 'total_qty',
                        titleAlignment: 'right',
                        modifier: (value) => value ? `<p class="text-right mb-0">${value}</p>` : '-',
                    },
                    {
                        title: this.$t('reference_no'),
                        type: 'custom-html',
                        key: 'reference_no',
                        titleAlignment: 'right',
                        modifier: (refNo) => `<p class="text-right mb-0">${refNo}</p>`,
                    },
                    {
                        title: this.$t('warehouse_/_store_from'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse_from',
                        modifier: value => value ? `<span>${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></span>` : ''
                    },
                    {
                        title: this.$t('warehouse_/_store_to'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse_to',
                        modifier: value => value ? `<span>${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></span>` : ''
                    },
                    {
                        title: this.$t('status'),
                        type: 'component',
                        componentName: 'app-internal-transfer-status',
                        key: 'statusId',
                        modifier: (status) => status && status
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    }
                ],
                actionType: 'dropdown',
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-stock-create-edit-modal',
                        modalId: 'stock-create-edit-modal',
                        modifier: () => this.$can('update_internal_transfers')
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        // temporary
                        url: STOCK,
                        name: 'delete',
                        modifier: row => this.$can('delete_internal_transfers') && !parseInt(row.is_default)
                    },
                ],
                filters: [
                    // "created" filter
                    {
                        title: this.$t('created_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    this.getStatusFilterOptions('internaltransfer'),
                    {
                        title: this.$t('warehouse_/_store_from'),
                        type: 'search-and-select-filter',
                        key: 'branch_warehouse_from_id',
                        settings: {
                            url: urlGenerator(SELECTABLE_BRANCHES_OR_WAREHOUSES),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'branch_warehouse_from_id',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'name'
                    },
                    {
                        title: this.$t('warehouse_/_store_to'),
                        type: 'search-and-select-filter',
                        key: 'branch_warehouse_to_id',
                        settings: {
                            url: urlGenerator(SELECTABLE_BRANCHES_OR_WAREHOUSES),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'branch_warehouse_to_id',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'name'
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
    computed:{
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted(){
        this.updateUrl(INTERNAL_TRANSFER);
    },
    watch: {
        getBranchOrWarehouseId(){
            this.updateUrl(INTERNAL_TRANSFER);
        },
    },
}
