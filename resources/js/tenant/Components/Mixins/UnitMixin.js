import {UNIT} from "../../Config/ApiUrl-CP";
import {createdBy} from "../../../store/Tenant/Mixins/CreatedByMixin";
import StatusQueryMixin from "../../../common/Mixin/Global/StatusQueryMixin";
import SelectableStatusMixin from "../../Helper/SelectableOptions/SelectableStatusMixin";

export default {
    mixins: [createdBy, StatusQueryMixin, SelectableStatusMixin],
    data() {
        return {
            options: {
                url: UNIT,
                showHeader: true,
                showCount: true,
                showClearFilter: true,
                tableShadow:false,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                    }, 
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value) =>
                            `<small class="text-capitalize bg-${value.translated_name === 'Inactive' ? 'danger' : 'success'} px-3 py-1 text-white" style="border-radius: 8rem;">${value.translated_name}</small>`,
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                filters: [
                    {
                        title: this.$t('created_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    this.getStatusFilterOptions('unit'),

                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'edit',
                        component: 'tax-create-edit-modal',
                        modalId: 'tax-create-edit-modal',
                        modifier: () => this.$can('update_unit'),
                    },
                    {
                        title: this.$t('deactivate'),
                        type: 'status_change',
                        name: 'deactivate',
                        modifier: (value) => value.status.translated_name === 'Active',
                    },
                    {
                        title: this.$t('activate'),
                        type: 'status_change',
                        name: 'activate',
                        modifier: (value) => value.status.translated_name !== 'Active',
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: UNIT,
                        name: 'delete',
                        modifier: () => this.$can('delete_unit'),
                    }
                ],
            },
        }
    }
}
