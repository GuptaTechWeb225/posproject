import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {EXPENSE_AREAS} from "../../Config/ApiUrl-CP";
import {numberWithCurrencySymbol} from "../../Helper/Helper";
import {textTruncate} from "../../../common/Helper/Support/TextHelper";

export default {
    mixins: [DatatableHelperMixin],
    data() {

        return {
            options: {
                name: this.$t('tenant_groups'),
                url: EXPENSE_AREAS,
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
                        title: this.$t('description'),
                        type: 'custom-html',
                        key: 'description',
                        modifier: (value) => {
                            return value ? `<p class="mb-0">${textTruncate(value, 80)}</p>` : '-';
                        },
                    },
                    {
                        title: this.$t('total_expense_in_this_area'),
                        type: 'custom-html',
                        key: 'total_amount',
                        titleAlignment: 'right',
                        modifier: (value) => {
                            return `<p class="text-right mb-0">${numberWithCurrencySymbol(value ?? 0)}</p>`
                        },
                    },
                    {
                        title: this.$t('add_to_report'),
                        type: 'component',
                        key: 'is_add_to_report',
                        isVisible: true,
                        componentName: "app-expense-added-to-report"
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
                        modifier: () => this.$can('update_expense_areas'),
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: EXPENSE_AREAS,
                        name: 'delete',
                        modifier: (row) => {
                            return this.$can('delete_expense_areas') && !parseInt(row.is_default)
                        },
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
    }
}
