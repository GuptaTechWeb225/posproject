<template>
    <modal id="due-payment-modal"
           v-model="showModal"
           :loading="loading"
           :preloader="preloader" :title="generateModalTitle('due_receive')"
           @input="closeModal"
           @submit="submitData">

        <form
            ref="form"
            :data-url='DUE_PAYMENT_RECEIVE+orderId'>

            <app-form-group
                v-model="formData.due_amount"
                type="number"
                class="mb-2"
                :label="$t('due_amount')"
                :required="true"
                :error-message="$errorMessage(errors, 'due_amount')"
            />
        </form>

    </modal>

</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {DUE_PAYMENT_INFO, DUE_PAYMENT_RECEIVE} from "../../../../Config/ApiUrl-CP";
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "DuePaymentModal",
    mixins: [FormHelperMixins, ModalMixin],
    props: {
        orderId: {
            require: true
        },
    },
    data() {
        return {
            showNote: true,
            loading: false,
            DUE_PAYMENT_RECEIVE,
            DUE_PAYMENT_INFO,
            formData: {
                due_amount: 0
            },
        }
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {};
            $('#due-payment-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'invoice-list-table');
        },
        getOrderInformation() {
            axiosGet(`${DUE_PAYMENT_INFO}/${this.orderId}`).then(response => {
                this.formData.due_amount = response.data.due_amount
            })
        },
        closeModal(eventData) {
            if (eventData) return;
            this.$emit('modal-close');
        }
    },
    mounted() {
        this.getOrderInformation()
    }
}
</script>
