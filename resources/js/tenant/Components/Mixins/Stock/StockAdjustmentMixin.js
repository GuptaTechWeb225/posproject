import {LOT, STOCK_ADJUSTMENTS} from "../../../Config/ApiUrl-CP";
import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import SelectableStatusMixin from "../../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapGetters} from "vuex";
import {formatDateToLocal} from "../../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('stock_adjustment'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('date'),
                        type: 'custom-html',
                        key: 'adjustment_date',
                        modifier: (adjustment_date) => `<p class="pb-0">${formatDateToLocal(adjustment_date)}</p>` ?? '-',
                    },
                    {
                        title: this.$t('reference_no'),
                        type: 'custom-html',
                        key: 'reference_no',
                        titleAlignment: 'right',
                        modifier: (refNo) => `<p class="text-right pb-0">${refNo}</p>`,
                    },
                    {
                        title: this.$t('note'),
                        type: 'component',
                        key: 'note',
                        componentName: 'app-stock-adjustment-note'
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<p class="pb-0">${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></p>` : ''
                    },
                    {
                        title: this.$t('created_by'),
                        type: 'custom-html',
                        key: 'created_by',
                        modifier: (createdBy) => createdBy ? `<p class="pb-0">${createdBy.full_name}</p>` : '',
                    },
                    {
                        title: this.$t('quantity'),
                        type: 'custom-html',
                        key: 'adjustment_variants',
                        titleAlignment: 'right',
                        modifier: (value) => {
                            let sum = 0;
                            for (const adjustmentVariant of value)
                                adjustmentVariant.adjustment_type === 'addition' ? sum += adjustmentVariant.unit_quantity : sum -= adjustmentVariant.unit_quantity;
                            return `<p class="text-right text-${ sum >= 0 ? 'success' : 'danger'} pb-0">${sum >= 0 ? '+' : ''}${sum}</p>`
                        }
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    }
                ],
                actionType: "dropdown",
                actions: [
                    //stock adjustment update shouldn't possible
                
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        name: 'delete',
                        modifier: () => this.$can('delete_stock_adjustments')
                    },
                ],
                filters: [
                    {
                        title: this.$t('created_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
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
                    {
                        title: this.$t('type'),
                        type: "radio",
                        key: "type",
                        initValue: '',
                        header: {title: '', description: ''},
                        option: [
                            {id: 'branch', name: this.$t('branch')},
                            {id: 'warehouse', name: this.$t('warehouse')}
                        ],
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
    computed:{
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted(){
        this.updateUrl(STOCK_ADJUSTMENTS);
    },
    watch: {
        getBranchOrWarehouseId(){
            this.updateUrl(STOCK_ADJUSTMENTS);
        },
    },
}
