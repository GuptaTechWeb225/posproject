import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {CASH_REGISTER, COUNTERS} from "../../Config/ApiUrl-CPB";
import {mapGetters} from "vuex";
import {urlGenerator} from "../../../common/Helper/AxiosHelper";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: COUNTERS,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                tableShadow: false,
                customerGroup: null,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('branch'),
                        type: 'object',
                        key: 'branch_or_warehouse',
                        modifier: (value) => {
                            return value.name;
                        }
                    },
                    {
                        title: this.$t('created_by'),
                        type: 'component',
                        key: 'created_by',
                        componentName: 'app-counter-created-by',
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value) => {
                            return `<span class="badge badge-pill badge-${value.class}">${value.translated_name}</span>`;
                        }
                    },
                    {
                        title: this.$t('action_by'),
                        type: 'component',
                        key: 'created_by',
                        componentName: 'app-counter-action-by',
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
                        title: this.$t('close'),
                        icon: 'x-square',
                        name: 'close-counter',
                        modifier: (row) => row.status.name === 'status_open'
                    },
                    {
                        title: this.$t('info'),
                        icon: 'info',
                        name: 'counter-info',
                    },
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-tenant-group-modal',
                        modalId: 'app-modal',
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: COUNTERS,
                        name: 'delete',
                        modifier: (row) => this.$can('delete_tenant_groups') && !parseInt(row.is_default)
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
                        title: this.$t('branch'),
                        type: 'search-and-select-filter',
                        key: 'branch_or_warehouse',
                        settings: {
                            url: urlGenerator('app/selectable/branches'),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_name',
                            loader: 'app-pre-loader'
                        },
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
    }
}
