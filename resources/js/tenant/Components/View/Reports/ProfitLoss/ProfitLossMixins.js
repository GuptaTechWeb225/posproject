import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {PROFIT_LOSS_REPORT} from "../../../../Config/ApiUrl-CP";
import SelectableStatusMixin from "../../../../Helper/SelectableOptions/SelectableStatusMixin";
import {mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            activeFilters: null,
            filters: [
                {
                    title: this.$t('choose_filter_group'),
                    type: "drop-down-filter",
                    name: 'always_active',
                    key: "search-select",
                    option: [
                        {id: 'year', name: 'Year'},
                        {id: 'month', name: 'Month'},
                        {id: 'date', name: 'Date'},
                        {id: 'customer', name: 'Customer'},
                        {id: 'invoice_id', name: 'Invoice id'},
                    ],
                    listValueField: 'name'
                },
                {
                    title: this.$t('date'),
                    name: 'date',
                    id: 'date',
                    type: "range-picker",
                    key: "date",
                    showFilter: false,
                    option: ["today", "thisMonth", "last7Days", "thisYear"]
                },
                {
                    title: this.$t('choose_year'),
                    name: 'year',
                    id: 'month',
                    type: "drop-down-filter",
                    key: "year",
                    option: [
                        {id: 2020, name: '2020'},
                        {id: 2021, name: '2021'},
                        {id: 2022, name: '2022'},
                        {id: 2023, name: '2023'},
                    ],
                    listValueField: 'name'
                },
            ]
        }
    },
    methods: {
        handleFilterChange(filters) {
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
        options() {
            return {
                name: this.$t('sales_report'),
                url: PROFIT_LOSS_REPORT,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('group'),
                        type: 'text',
                        key: 'group_by',
                    },
                    {
                        title: this.$t('grand_total'),
                        type: 'custom-html',
                        key: 'total_sell_amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${value ? numberWithCurrencySymbol(value) : 0}</p>`
                    },
                    {
                        title: this.$t('profit_amount'),
                        type: 'custom-html',
                        key: 'net_profit',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${value ? numberWithCurrencySymbol(value) : 0}</p>`
                    }
                ],
                actionType: "dropdown",
                actions: [],
                filters: this.computedFilters,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                search: true,
            }
        },
        computedFilters() {
            if (!this.activeFilters) return this.filters;
            return this.filters.filter(filter => {
                if (filter.name === 'always_active') return true;
                return this.activeFilters.search_select === filter.id
            });
        }
    },
    mounted() {
        this.updateUrl(PROFIT_LOSS_REPORT);
        this.$hub.$on('filter-change', (filters) => {
            this.activeFilters = filters;
        });
    },
    watch: {
        getBranchOrWarehouseId() {
            this.updateUrl(PROFIT_LOSS_REPORT);
            this.$hub.$emit(`reload-${this.table_id}`)
        },
    },
    created() {
    }
}
