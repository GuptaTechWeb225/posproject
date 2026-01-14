import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {STOCK_OVERVIEW} from "../../Config/ApiUrl-CP";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: STOCK_OVERVIEW + this.variant_id,
                showCount: false,
                showClearFilter: false,
                showFilter: false,
                showSearch: false,
                showHeader: true,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                search: false,
                actionType: "dropdown",
                columns: [
                    {
                        title: this.$t('store'),
                        type: 'custom-html',
                        key: 'branchOrWarehouse',
                        modifier: (value, rowData) =>
                            `<p class="col-md-3 pb-0 d-inline">
                                <span style="width: 10px; height: 10px;" class="d-inline-block rounded-circle d-inline-block mr-1 bg-${rowData.available_qty ? 'success' : 'danger'}"></span>
                                ${value.name}
                            </p>`
                    },
                    {
                        title: this.$t('stock_quantity'),
                        type: 'custom-html',
                        key: 'available_qty',
                        modifier: (value) => `<span class="text-${value ? 'success' : 'danger'}">${value}</span>`
                    },
                    {
                        title: this.$t('last_update'),
                        type: 'text',
                        key: 'updated_at',
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                actions: [
                    {
                        title: this.$t('req._for_internal_transfer'),
                        type: 'modal',
                        name: 'internal_transfer_request',
                    }
                ],
            },
        }
    },
}
