<template>
    <div class="py-5">
        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         :style="'width:' +calculateBarWidth(4)+'%'" aria-valuenow="0" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="mail" class="primary-text-color mr-2"/>
                    {{ $t('email_setup') }}
                </h5>
            </div>
            <div class="card-body">

                <div class="form-group row align-items-center">
                    <label for="emailSettingsProvider" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t("supported_mail_services") }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input
                            id="emailSettingsProvider"
                            type="select"
                            v-model="environment.provider"
                            :list="providerList"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label for="emailSettingsFromName" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t("email_sent_from_name") }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input
                            id="emailSettingsFromName"
                            type="text"
                            v-model="environment.from_name"
                            :placeholder="$t('type_email_sent_from_name')"
                            :required="true"
                        />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsFromEmail" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t("email_sent_from_email") }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input
                            id="emailSettingsFromEmail"
                            type="email"
                            v-model="environment.from_email"
                            :placeholder="$t('type_email_sent_from_email')"
                            :required="true"
                        />
                    </div>
                </div>


                <!-- For Amazon SES -->
                <fieldset v-if="environment.provider === 'amazon_ses'">
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsHostname" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("hostname") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsHostname"
                                type="text"
                                v-model="environment.hostname"
                                :placeholder="$t('type_host_name')"
                                :required="true"
                            />
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="emailSettingsAccessKeyId" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("access_key_id") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsAccessKeyId"
                                type="text"
                                v-model="environment.access_key_id"
                                :placeholder="$t('type_access_key_id')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsSecretAccessKey" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("secret_access_key") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsSecretAccessKey"
                                type="text"
                                v-model="environment.secret_access_key"
                                :placeholder="$t('type_secret_access_key')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsApiRegion" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("region") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsApiRegion"
                                type="text"
                                v-model="environment.region"
                                :placeholder="$t('region')"
                            />
                        </div>
                    </div>
                </fieldset>

                <!-- For Mailgun -->
                <fieldset v-else-if="environment.provider === 'mailgun'">
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsDomainName" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("domain_name") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsDomainName"
                                type="text"
                                v-model="environment.domain_name"
                                :placeholder="$t('type_domain_name')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsApiKey" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("api_key") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsApiKey"
                                type="text"
                                v-model="environment.api_key"
                                :placeholder="$t('type_api_key')"
                                :required="true"
                            />
                        </div>
                    </div>
                </fieldset>

                <!-- For SMTP -->

                <fieldset v-else-if="environment.provider === 'smtp'">

                    <div class="form-group row align-items-center">
                        <label for="user_name" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("user_name") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="user_name"
                                type="text"
                                v-model="environment.smtp_user_name"
                                :placeholder="$t('user_name')"
                                :required="true"
                            />
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="emailSettingsSmtpHost" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("smtp_host") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsSmtpHost"
                                type="text"
                                v-model="environment.smtp_host"
                                :placeholder="$t('type_smtp_host')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsSmtpPort" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("port") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsSmtpPort"
                                type="number"
                                v-model="environment.smtp_port"
                                :placeholder="$t('type_port_number')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsEmailPassword" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("password_to_access") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsEmailPassword"
                                type="password"
                                v-model="environment.email_password"
                                :show-password="true"
                                :placeholder="$t('type_password_to_access')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsEncryptionType" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("encryption_type") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsEncryptionType"
                                type="select"
                                v-model="environment.encryption_type"
                                :placeholder="$t('type_encryption_type')"
                                :list="encryptionType"
                                :required="true"
                            />
                        </div>
                    </div>
                </fieldset>

            </div>
        </div>

        <div class="form-group mt-5">
            <app-submit-button :loading="loading"
                               @click="submitData"
                               btn-class="btn-block"
                               :label="setLabel()"/>
        </div>

        <div class="form-group mt-3" v-if="isActive.skipButton">
            <button
                class="btn btn-light btn-block"
                type="button"
                @click.prevent="skipStep">
               <span class="w-100">
                    <loader v-if="skipButtonLoading" :loader-color="'white-color'"/>
                    <template v-if="!skipButtonLoading">
                          {{ $t('skip') }}
                    </template>
                </span>
            </button>
        </div>
    </div>
</template>

<script>

import {FormMixin} from "../core/mixins/form/FormMixin";
import {axiosPost, urlGenerator} from "../Helpers/AxiosHelper";
import {
    APP_LOG_IN,
    EMAIL_SETTING_UPDATE,
    EMAIL_SETUP_SKIP,
    SET_UP_BROADCAST
} from "../Config/ApiUrl";
import Preloader from "../core/components/preloders/Preloader";
import {ProgressionBarMixin} from "./Mixins/ProgressionBarMixin";

export default {
    name: "EmailSetupWizard",
    mixins: [FormMixin, ProgressionBarMixin],
    components: {
        'loader': Preloader
    },
    props: {
        appName: {},
        installerConfig: {
            include_option: {
                email_set_up: '',
                pusher_set_up: ''
            },
            skip_option: {
                email_set_up: '',
                pusher_set_up: ''
            }
        }
    },
    created() {
        let parseData = JSON.parse(this.installerConfig);
        this.isActive.skipButton = parseData?.skip_option?.email_set_up;
    },
    data() {
        return {
            isActive: {
                skipButton: false
            },
            loading: false,
            skipButtonLoading: false,
            errors: {},
            environment: {
                region: "us-east-1",
                from_email: '',
                from_name: '',
                provider: '',
                encryption_type: '',
                email_password: '',
                smtp_port: '',
                smtp_host: '',
                smtp_user_name: '',
                api_key: '',
                secret_access_key: '',
                domain_name: '',
                access_key_id: '',
            },
            providerList: [
                {id: "", value: this.$t("choose_one")},
                {id: "amazon_ses", value: this.$t("amazon_ses")},
                {id: "mailgun", value: this.$t("mailgun")},
                {id: "smtp", value: this.$t("smtp")},
                {id: "sendmail", value: this.$t("sendmail")},
            ],
            encryptionType: [
                {id: "", value: this.$t("choose_one")},
                {id: "tls", value: this.$t("tls")},
                {id: "ssl", value: this.$t("ssl")},
            ],
        }
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        submitData() {
            this.submitFromFixin('post', EMAIL_SETTING_UPDATE, this.environment);
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            setTimeout(() => {
                window.location = this.redirectUrl();
            }, 1000);
        },
        afterFinalResponse() {
            this.loading = false;
        },
        afterError() {
            this.loading = false;
        },
        skipStep() {
            this.skipButtonLoading = true;
            axiosPost(EMAIL_SETUP_SKIP, {}).then((response) => {
                this.$toastr.s('', response.data.message);
                setTimeout(() => {
                    window.location = this.redirectUrl();
                }, 1000);
            });
        },
        redirectUrl() {
            let installerConfig = JSON.parse(this.installerConfig) ? JSON.parse(this.installerConfig) : null;
            return installerConfig?.include_option?.pusher_set_up ? urlGenerator(SET_UP_BROADCAST) : urlGenerator(APP_LOG_IN)
        },
        setLabel() {
            let installerConfig = JSON.parse(this.installerConfig);
            return installerConfig?.include_option?.pusher_set_up ? this.$t('save_&_next') : this.$t('save_&_install')
        }
    }
}
</script>
