<template>
    <div>
        <app-table
            id="all-payment-methods-table"
            showFilter
            :options="options"
            @action="triggerActions"
        />

        <payment-method-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('all-payment-methods-table')"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
import PaymentMethodMixin from "../../Mixins/PaymentMethodMixin";
import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
import PaymentMethodCreateEditModal from "./PaymentMethodCreateEditModal";
import {GET_PAYMENT_METHOD_STATUS, PAYMENT_METHOD} from "../../../Config/ApiUrl-CPB";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";

export default {
    name: "PaymentMethods",
    extends: CoreLibrary,
    components: {PaymentMethodCreateEditModal},
    mixins: [PaymentMethodMixin, DatatableHelperMixin],
    props: {
        props: {
            default: ''
        },
        id: {
            type: String
        }
    },
    data() {
        return {
            selectedUrl: '',
            isModalActive: false,
            status: '',
            paymentSetting: '',
        }
    },
    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, (component) => {
            this.isModalActive = true;
            this.selectedUrl = '';
        })
    },
    created() {
        this.getPaymentMethodStatus()
    },
    methods: {
        openModal() {
            this.selectedUrl = '';
            this.isModalActive = true;
        },
        triggerActions(row, action, active) {
            if (action.type === 'edit') {
                this.selectedUrl = `${PAYMENT_METHOD}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        getPaymentMethodStatus() {
            this.axiosGet(GET_PAYMENT_METHOD_STATUS).then(response => {
                let name = this.options.filters.find(element => element.key === 'statusId');
                name.option = response.data.map(status => {
                    return {
                        id: status.id,
                        name: status.translated_name
                    }
                });
            });
        },
    },
    beforeDestroy() {
        this.$hub.$off('headerButtonClicked-' + this.id);
    }
}
</script>
