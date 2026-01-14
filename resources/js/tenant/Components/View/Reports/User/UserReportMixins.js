import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {USER_REPORT} from "../../../../Config/ApiUrl-CP";
import SelectableStatusMixin from "../../../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapGetters} from "vuex";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('sales_report'),
                url: USER_REPORT,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'custom-html',
                        key: 'full_name',
                        modifier: full_name => `<p class="pb-0">${full_name ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_sales'),
                        type: 'custom-html',
                        key: 'orders_count',
                        titleAlignment: 'right',
                        modifier: total_sales => `<p class="text-right pb-0">${total_sales ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_purchase'),
                        type: 'custom-html',
                        key: 'lots_count',
                        titleAlignment: 'right',
                        modifier: total_purchase => `<p class="text-right pb-0">${total_purchase ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_sale_return'),
                        type: 'custom-html',
                        key: 'return_orders_count',
                        titleAlignment: 'right',
                        modifier: total_sale_return => `<p class="text-right pb-0">${total_sale_return ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_internal_transfer'),
                        type: 'custom-html',
                        key: 'internal_transfers_count',
                        titleAlignment: 'right',
                        modifier: total_internal_transfer => `<p class="text-right pb-0">${total_internal_transfer ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_adjustment'),
                        type: 'custom-html',
                        key: 'adjustments_count',
                        titleAlignment: 'right',
                        modifier: total_adjustment => `<p class="text-right pb-0">${total_adjustment ?? '-'}</p>`
                    },
                    {
                        title: this.$t('action'),
                        type: 'component',
                        componentName: 'app-user-details-button',
                        key: 'id',
                    },
                ],
                actionType: "dropdown",
                actions: [],
                filters: [
                    {
                        title: this.$t('date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                search: true,
            }
        }
    },
    methods: {
        updateUrl() {
            this.options.url = `${USER_REPORT}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.$hub.$emit(`reload-${this.table_id}`)
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted() {
        this.updateUrl();
    },
    watch: {
        getBranchOrWarehouseId(new_id) {
            this.updateUrl();
            this.$hub.$emit(`reload-${this.table_id}`)
        },
    },
}
