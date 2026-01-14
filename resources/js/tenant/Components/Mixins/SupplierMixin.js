import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {SUPPLIERS} from "../../Config/ApiUrl-CPB";
import {ucWords} from "../../../common/Helper/Support/TextHelper";
export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: SUPPLIERS,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('image'),
                        type: 'component',
                        key: 'profile_picture',
                        componentName: 'app-supplier-img'
                    },
                    {
                        title: this.$t('name'),
                        type: 'component',
                        key: 'name',
                        componentName: 'app-supplier-action'
                    }, 
                    {
                        title: this.$t('phone_number_s'),
                        type: 'component',
                        key: 'phone_numbers',
                        componentName: 'app-customer-contact'
                    },
                    {
                        title: this.$t('email_s'),
                        type: 'component',
                        key: 'emails',
                        componentName: 'app-supplier-contact'
                    },
                    {
                        title: this.$t('address_s'),
                        type: 'component',
                        key: 'addresses',
                        componentName: 'app-supplier-address-info'
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
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        name: 'edit',
                        modifier: row => {
                            return  this.$can('update_suppliers')
                        },
                    },
                    {
                        title: this.$t('deactivate'),
                        type: 'modal',
                        name: 'deactivate',
                        component: '',
                        modalId: 'supplier-modal',
                        modifier: ({status: {name: status}}) => {
                            return status === 'status_active' && this.$can('change_status_supplier')
                        },
                    },
                    {
                        title: this.$t('activate'),
                        type: 'modal',
                        name: 'activate',
                        component: '',
                        modalId: 'supplier-modal',
                        modifier: ({status: {name: status}}) => {
                            return status !== 'status_active' && this.$can('change_status_supplier')
                        },
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        name: 'delete',
                        url: SUPPLIERS,
                        modifier: row => {
                            return  this.$can('delete_suppliers')
                        },
                    },

                ],
                filters: [],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                search: true,
            },
        }
    }
}
