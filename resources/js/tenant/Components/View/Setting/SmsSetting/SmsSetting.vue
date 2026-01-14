<template>

    <app-overlay-loader v-if="preloader"/>
    <form v-else ref="form" :data-url="SMS_SETTING"
          :class="{'loading-opacity': preloader}">

        <div class="form-group row">
            <label for="smsSettingsSmsDriver" class="col-sm-3 col-form-label">
                {{ $t('sms_driver') }}
            </label>
            <div class="col-sm-9">
                <app-input id="smsSettingsSmsDriver"
                           type="select"
                           v-model="smsSettings.sms_driver"
                           :required="true"
                           :list="driverList"/>
            </div>
        </div>

        <!--For Twilio driver-->
        <fieldset v-if="smsSettings.sms_driver === 'twilio'">
            <div class="form-group row">
                <label for="smsSettingsKey" class="col-sm-3 col-form-label">
                    {{ $t('account_sid') }}
                </label>
                <div class="col-sm-9">
                    <app-input id="smsSettingsKey"
                               type="text"
                               v-model="smsSettings.account_sid"
                               :placeholder="$t('account_sid')"
                               :required="true"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="smsSettingsSecretKey" class="col-sm-3 col-form-label">
                    {{ $t('auth_token') }}
                </label>
                <div class="col-sm-9">
                    <app-input id="smsSettingsSecretKey"
                               type="text"
                               v-model="smsSettings.auth_token"
                               :placeholder="$t('auth_token')"
                               :required="true"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="smsSettingsTestNumber" class="col-sm-3 col-form-label">
                    {{ $t('sms_sent_from') }}
                </label>
                <div class="col-sm-9">
                    <vue-tel-input id="phone"
                                   type="text"
                                   v-model="smsSettings.sms_sent_from"
                                   mode="international"
                                   name="phone"
                                   :placeholder="$t('sms_sent_from')"
                                   class=""></vue-tel-input>
                </div>
            </div>
            <div class="form-group row">
                <label for="smsSettingsTestNumber" class="col-sm-3 col-form-label">
                    {{ $t('auto_send_sms') }}
                </label>
                <div class="col-sm-9 d-flex align-items-left justify-content-between mb-3">
                    <button :key="smsSettings.send_auto_sms === '1' ? 'check-square' : 'square'"
                            class="btn py-2 px-2 padding-x-10" @click="handleAutoGenerateSendAutoSms">
                        <app-icon
                            class="size-19 primary-text-color"
                            :name="smsSettings.send_auto_sms === '1' ? 'check-square' : 'square'"
                        />
                    </button>
                </div>
            </div>


        </fieldset>

        <div :class="`mt-5 ${ $isRtl ? 'text-left' : '' }`">
            <button class="btn btn-primary mr-2" @click.prevent="submit">
                {{ $t('save') }}
            </button>
            <button class="btn btn-secondary">
                {{ $t('cancel') }}
            </button>
        </div>
    </form>
</template>

<script>
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";

import {VueTelInput} from 'vue-tel-input';
import {GET_SMS_SETTING, SMS_SETTING} from "../../../../Config/ApiUrl-CPB";

export default {

    components: {VueTelInput},

    name: "SmsSettings",
    mixins: [FormMixin],
    data() {
        return {
            SMS_SETTING,
            preloader: false,
            smsSettings: {
                sms_driver: 'twilio'
            },
            driverList: [
                {id: 'twilio', value: "Twilio"},
            ],
            showError: false,
        }
    },
    created() {
        this.getData();

    },
    methods: {
        handleAutoGenerateSendAutoSms() {
            this.smsSettings.send_auto_sms = this.smsSettings.send_auto_sms === '1' ? "0" : "1";
        },
        beforeSubmit() {
            this.preloader = true;
        },
        submit() {
            this.save(this.smsSettings);
        },
        afterFinalResponse() {
            this.preloader = false;
        },
        afterSuccess(res) {
            this.$toastr.s(res.data.message);
        },
        afterError(res) {
            this.showError = true;
            this.$toastr.e(res.data.message);
        },
        getData() {
            this.preloader = true;
            this.axiosGet(GET_SMS_SETTING).then(response => {

                this.smsSettings = response.data;

            }).catch(({response}) => {

            }).finally(() => {
                this.preloader = false;
            });
        }
    }
}
</script>
