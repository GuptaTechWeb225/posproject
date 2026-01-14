import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {EXPENSES} from "../../Config/ApiUrl-CP";
import {numberWithCurrencySymbol} from "../../Helper/Helper";
import {formatDateToLocal} from "../../../common/Helper/Support/DateTimeHelper";
import {textTruncate} from "../../../common/Helper/Support/TextHelper";
import {expense_area} from "../../../store/Tenant/Mixins/ExpenseAreaMixin";
import {mapGetters} from "vuex";

export default {
    mixins: [DatatableHelperMixin, expense_area],
    data() {
        return {
            options: {
                name: this.$t('expense'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('area_of_expense'),
                        type: 'object',
                        key: 'expense_area',
                        modifier: value => value.name
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<span>${value.name} <span class="text-${ value.type.toLowerCase() === 'branch' ? 'warning' : 'info' }">(${value.type[0].toUpperCase()})</span></span>` : ''
                    },
                    {
                        title: this.$t('amount'),
                        type: 'custom-html',
                        key: 'amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right mb-0">${value ? numberWithCurrencySymbol(value) : '-'}</'p>`
                    },
                    {
                        title: this.$t('expense_date'),
                        type: 'custom-html',
                        key: 'expense_date',
                        isVisible: true,
                        modifier: expense_date => expense_date ? formatDateToLocal(expense_date) : '-'
                    },
                    {
                        title: this.$t('description'),
                        type: 'custom-html',
                        key: 'description',
                        modifier: value => value ? `<p class="mb-0">${textTruncate(value, 80)}</p>` : '-'
                    },
                    {
                        title: this.$t('attachment'),
                        type: 'component',
                        key: 'attachments',
                        componentName: 'app-attachment'
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-tenant-group-modal',
                        modalId: 'app-modal',
                        modifier: () => this.$can('update_expenses'),
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: EXPENSES,
                        name: 'delete',
                        modifier: row => this.$can('delete_expenses') && !parseInt(row.is_default)
                    }
                ],
                filters: [
                    {
                        title: this.$t('expense_date'),
                        type: "range-picker",
                        key: "expense_date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    {
                        title: this.$t('area_of_expense'),
                        type: "multi-select-filter",
                        key: "expense_area",
                        option: [],
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
        this.updateUrl(EXPENSES);
    },
    watch: {
        getBranchOrWarehouseId(){
            this.updateUrl(EXPENSES);
        },
    },
}
