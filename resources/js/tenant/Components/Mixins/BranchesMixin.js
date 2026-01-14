import { BRANCHES_OR_WAREHOUSES} from "../../Config/ApiUrl-CPB";

export default {
    data() {
        return {
            options: {
                url: BRANCHES_OR_WAREHOUSES,
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
                        title: this.$t('type'),
                        type: 'custom-html',
                        key: 'type',
                        modifier: (value) =>
                            `<span class="text-capitalize bg-${value === 'branch' ? 'warning' : 'info'} px-3 py-1 text-white" style="border-radius: 8rem;">${value}</span>`,
                    },
                    {
                        title: this.$t('manager'),
                        type: 'custom-html',
                        key: 'manager',
                        modifier: (value) => `<span>${ value ? value.full_name : '--'}</span>`,
                    },
                    {
                        title: this.$t('location'),
                        type: 'custom-html',
                        key: 'location',
                        modifier: (value) => {
                            return value;
                        }
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value) =>
                            `<small class="text-capitalize bg-${value.translated_name === 'Inactive' ? 'danger' : 'success'} px-3 py-1 text-white" style="border-radius: 8rem;">${value.translated_name}</small>`
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                filters: [
              
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
                        component: 'branch-create-edit-modal',
                        modalId: 'branch-create-edit-modal',
                        modifier: row => {
                            return this.$can('update_branch')
                        },
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
                        title: this.$t('manage_users'),
                        type: 'modal',
                        name: 'view_users',
                    } 
                ],
            },
        }
    }
}
