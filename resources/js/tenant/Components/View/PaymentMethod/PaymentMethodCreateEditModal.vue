<template>
    <modal id="payment-method-create-edit-modal"
           size="large"
           v-model="showModal"
           :title="generateModalTitle('payment_method')"
           @submit="submitData"
           :loading="loading"
           :preloader="preloader">

        <form ref="form"
              :data-url='selectedUrl ? selectedUrl : url'
              @submit.prevent="submitData">

            <app-form-group
                :page="'modal'"
                :label="$t('name')"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
            />
            <app-form-group
                :page="'modal'"
                :type="'select'"
                :label="$t('type')"
                :required="true"
                :error-message="$errorMessage(errors, 'type')"
                :list="paymentType"
                v-model="formData.type"
            />
            <app-form-group
                :page="'modal'"
                :type="'select'"
                :label="$t('alias')"
                :required="true"
                :error-message="$errorMessage(errors, 'alias')"
                :list="aliasType"
                v-model="formData.alias"
            />
            <app-form-group
                :page="'modal'"
                :type="'select'"
                :label="$t('rounded_to')"
                :required="true"
                :error-message="$errorMessage(errors, 'rounded_to')"
                :list="roundedTo"
                v-model="formData.rounded_to"
            />
            <div class="d-flex justify-content-start">
                <app-form-group
                    class="mr-5"
                    :page="'modal'"
                    :type="'radio'"
                    :label="$t('mark_as_default')"
                    :radio-checkbox-name="'for-default'"
                    :required="true"
                    :error-message="$errorMessage(errors, 'is_default')"
                    :list="[{id:1, value:'Yes'},{id:0, value:'No'}]"
                    v-model="formData.is_default"
                />
                <app-form-group v-if="selectedUrl"
                                :page="'modal'"
                                :type="'radio'"
                                :radio-checkbox-name="'for-status'"
                                :label="this.$t('payment_status')"
                                list-value-field="translated_name"
                                :list="statusList"
                                v-model="formData.status_id"
                />
            </div>
            <template v-if="selectedType">
                <app-form-group v-if="formData.type==='stripe'"
                    :page="'modal'"
                    :label="$t('public_key')"
                    :required="true"
                    :error-message="$errorMessage(errors, 'public_key')"
                    :placeholder="$placeholder($t('public_key'))"
                    v-model="formData.public_key"
                />
                <app-form-group v-else
                    :page="'modal'"
                    :label="$t('client_id')"
                    :required="true"
                    :error-message="$errorMessage(errors, 'client_id')"
                    :placeholder="$placeholder($t('client_id'))"
                    v-model="formData.client_id"
                />
                <app-form-group
                    :page="'modal'"
                    type="password"
                    :show-password="true"
                    :label="$t('secret_key')"
                    :required="true"
                    :error-message="$errorMessage(errors, 'secret_key')"
                    :placeholder="$placeholder($t('secret_key'))"
                    v-model="formData.secret_key"
                />
                <app-form-group v-if="formData.type==='paypal'"
                    :page="'modal'"
                    :type="'radio'"
                    :label="$t('mode')"
                    :radio-checkbox-name="'for-mode'"
                    :required="true"
                    :error-message="$errorMessage(errors, 'mode')"
                    :list="paypalMode"
                    v-model="formData.mode"
                />
            </template>
        </form>

    </modal>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {PAYMENT_METHOD, GET_PAYMENT_METHOD_STATUS} from "../../../Config/ApiUrl-CPB";
import _ from "lodash";

export default {
    name: "PaymentMethodCreatedEditModal",
    extends: CoreLibrary,
    mixins: [FormHelperMixins, HelperMixin, ModalMixin],
    data() {
        return {
            url: PAYMENT_METHOD,
            statusList: [],
            paymentType: [
                {id: '', value: 'Choose One', disabled: true},
                {id: 'cash', value: this.$t('cash')},
            ],
            aliasType: [
                {id: '', value: 'Choose One', disabled: true},
                {id: 'cash', value: this.$t('cash')},
                {id: 'credit', value: this.$t('credit')},
            ],
            roundedTo: [
                {id: '', value: 'Choose One', disabled: true},
                {id: 'none', value: this.$t('none')},
                {id: 'nearest_integer', value: this.$t('nearest_integer')},
                {id: 'nearest_half', value: this.$t('nearest_half')},
            ],
            paypalMode:[
                {id:'live', value:this.$t('live')},
                {id:'sandbox', value:this.$t('sandbox')}
            ],
            formData: {
                is_default: false,
                is_for_client: false,
                public_key: '',
                secret_key: '',
                client_id: '',
                mode: '',
                alias: '',
            },

        }
    },
    created() {
        this.getStatusList();
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {};
            $('#payment-method-create-edit-modal').modal('hide');
            this.$emit('payment-method-created');
            this.toastAndReload(data.message, 'all-payment-methods-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;

            if (response.data.settings){
                let paypal = response.data.settings.find((item) => (item.name === 'client_id'))
                if (paypal) {
                    this.formData.client_id = paypal.value;
                    let mode = response.data.settings.find((item) => (item.name === 'mode'));
                    if (mode)
                        this.formData.mode = mode.value;
                }
                let stripe = response.data.settings.find((item) => (item.name === 'public_key'))
                if (stripe)
                    this.formData.public_key = stripe.value;
                let secretKey = response.data.settings.find((item) => (item.name === 'secret_key'))
                if (secretKey)
                    this.formData.secret_key = secretKey.value;
            }
        },
        getStatusList() {
            this.axiosGet(GET_PAYMENT_METHOD_STATUS).then(response => {
                this.statusList = response.data
            });
        },
    },
    computed:{
        selectedType(){
            if(this.formData.type==='stripe' || this.formData.type==='paypal'){
                if(this.formData.type==='stripe'){
                    this.formData.client_id = '';
                    this.formData.mode = '';
                }else {
                    this.formData.public_key = '';
                }
                return true;
            }
            this.formData.secret_key = '';
            this.formData.public_key = '';
            this.formData.client_id = '';
            this.formData.mode = '';
            return false;
        }
    }
}
</script>
