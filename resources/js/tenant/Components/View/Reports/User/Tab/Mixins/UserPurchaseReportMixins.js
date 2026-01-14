import DatatableHelperMixin from "../../../../../../../common/Mixin/Global/DatatableHelperMixin";
import {USER_PURCHASE_REPORT} from "../../../../../../Config/ApiUrl-CP";
import SelectableStatusMixin from "../../../../../../Helper/SelectableOptions/SelectableStatusMixin";
import {formatDateTimeToLocal, numberWithCurrencySymbol} from "../../../../../../Helper/Helper";
import {urlGenerator} from "../../../../../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";

export default {
    mixins: [DatatableHelperMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('purchase_report'),
                url: USER_PURCHASE_REPORT,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                tableShadow: false,
                numberWithCurrencySymbol,
                columns: [
                    {
                        title: this.$t('reference_no'),
                        type: 'text',
                        key: 'reference_no',
                    },
                    {
                        title: this.$t('Date'),
                        type: 'object',
                        key: 'created_at',
                        modifier: value => formatDateTimeToLocal(value),
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<span>${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></span>` : ''
                    },
                    {
                        title: this.$t('supplier'),
                        type: 'custom-html',
                        key: 'supplier',
                        modifier: (supplier) => supplier && `<span class="text-primary">${supplier.name}</span>`,
                    },
                    {
                        title: this.$t('total_items'),
                        type: 'text',
                        key: 'total_unit',
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
                        modifier: value => value ? `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                    {
                        title: this.$t('other_cost'),
                        type: 'custom-html',
                        key: 'other_charge',
                        titleAlignment: 'right',
                        modifier: value => value ? `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
                    },
                    {
                        title: this.$t('grand_total'),
                        type: 'custom-html',
                        key: 'total_amount',
                        titleAlignment: 'right',
                        modifier: value => value ? `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>` : `<p class="text-right mb-0">${numberWithCurrencySymbol(0)}</p>`
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
                actionType: "dropdown",
                actions: [],
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
            this.options.url = `${USER_PURCHASE_REPORT}/${this.props.id}`
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
    }
}
