import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {
    TOP_SELLING_PRODUCT_REPORT
} from "../../../../Config/ApiUrl-CP";
import SelectableStatusMixin from "../../../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('sales_report'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'custom-html',
                        key: 'name',
                        modifier: (name) => `<p class="pb-0">${name}</p>`,
                    },
                    {
                        title: this.$t('quantity'),
                        type: 'custom-html',
                        key: 'total_quantity',
                        titleAlignment: 'right',
                        modifier: (quantity) => `<p class="text-right pb-0">${quantity}</p>`,
                    },
                    {
                        title: this.$t('total_amount'),
                        type: 'custom-html',
                        key: 'total_amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right pb-0">${numberWithCurrencySymbol(value)}</p>`
                    },
                ],
                actionType: "dropdown",
                actions: [],
                filters: [
                    {
                        title: this.$t('issue_date'),
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

    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted() {
        this.updateUrl(TOP_SELLING_PRODUCT_REPORT);
    },
    watch: {
        getBranchOrWarehouseId() {
            this.updateUrl(TOP_SELLING_PRODUCT_REPORT);
            this.$hub.$emit(`reload-${this.table_id}`)
        },
    },
}
