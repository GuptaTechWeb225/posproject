import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {BRANCH_WAREHOUSE_REPORT} from "../../../../Config/ApiUrl-CP";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";
import {expense_area} from "../../../../../store/Tenant/Mixins/ExpenseAreaMixin";
import {mapGetters} from "vuex";

export default {
    mixins: [DatatableHelperMixin, expense_area],
    data() {
        return {
            options: {
                name: this.$t("expense"),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'custom-html',
                        key: 'name',
                        modifier: (name, row) => name ? `<p>${name} <span class="text-${ row.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${row.type[0].toUpperCase()})</span></p>` : ''
                    },
                    {
                        title: this.$t('total_order'),
                        type: 'custom-html',
                        key: 'orders_count',
                        titleAlignment: 'right',
                        modifier: total_order => `<p class="text-right pb-0">${total_order ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_purchase'),
                        type: 'custom-html',
                        key: 'lots_count',
                        titleAlignment: 'right',
                        modifier: total_purchase => `<p class="text-right pb-0">${total_purchase ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_return_order'),
                        type: 'custom-html',
                        key: 'return_orders_count',
                        titleAlignment: 'right',
                        modifier: total_return_order => `<p class="text-right pb-0">${total_return_order ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_internal_transfer'),
                        type: 'custom-html',
                        key: 'internal_transfers_count',
                        titleAlignment: 'right',
                        modifier: total_internal_transfer => `<p class="text-right pb-0">${total_internal_transfer ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_adjustments'),
                        type: 'custom-html',
                        key: 'adjustments_count',
                        titleAlignment: 'right',
                        modifier: total_adjustments => `<p class="text-right pb-0">${total_adjustments ?? '-'}</p>`
                    },
                    {
                        title: this.$t('total_sold_amount'),
                        type: 'custom-html',
                        key: 'total_selling_amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right pb-0">${value ? numberWithCurrencySymbol(value) : 0}</p>`
                    }
                ],
                actionType: "default",
                actions: [],
                filters: [
                    {
                        title: this.$t('date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
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


    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted() {
        this.updateUrl(BRANCH_WAREHOUSE_REPORT);
    },
    watch: {
        getBranchOrWarehouseId() {
            this.updateUrl(BRANCH_WAREHOUSE_REPORT);
        },
    },
}
