<template>
    <div class="py-5">
        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="progress"  style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="'width:' +calculateBarWidth(5)+'%'" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="user" class="primary-text-color mr-2"/>
                    {{ $t('broadcasting_configuration') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('broadcast_driver') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            id="provider"
                            type="select"
                            v-model="environment.broadcast_driver"
                            :list="providerList"
                            :required="true"
                        />
                    </div>
                </div>
                <fieldset v-if="environment.broadcast_driver === 'pusher'">
                    <div class="form-group row">
                        <label class="col-md-3 d-flex align-items-center">
                            {{ $t('pusher_app_id') }}
                        </label>
                        <div class="col-md-9">
                            <app-input
                                type="text"
                                v-model="environment.pusher_app_id"
                                :placeholder="$t('pusher_app_id')"
                                :error-message="$errorMessage(errors, 'pusher_app_id')"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 d-flex align-items-center">
                            {{ $t('pusher_app_key') }}
                        </label>
                        <div class="col-md-9">
                            <app-input
                                type="text"
                                v-model="environment.pusher_app_key"
                                :placeholder="$t('pusher_app_key')"
                                :error-message="$errorMessage(errors, 'pusher_app_key')"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 d-flex align-items-center">
                            {{ $t('pusher_app_secret') }}
                        </label>
                        <div class="col-md-9">
                            <app-input
                                type="text"
                                v-model="environment.pusher_app_secret"
                                :placeholder="$t('pusher_app_secret')"
                                :error-message="$errorMessage(errors, 'pusher_app_secret')"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 d-flex align-items-center">
                            {{ $t('pusher_app_cluster') }}
                        </label>
                        <div class="col-md-9">
                            <app-input
                                type="text"
                                v-model="environment.pusher_app_cluster"
                                :placeholder="$t('pusher_app_cluster')"
                                :error-message="$errorMessage(errors, 'pusher_app_cluster')"/>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="form-group mt-5">
            <app-submit-button
                :loading="loading"
                @click="submitData"
                btn-class="btn-block"
                :label="$t('save_&_next')"/>
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
import Preloader from "../core/components/preloders/Preloader";
import {axiosPost, urlGenerator} from "../Helpers/AxiosHelper";
import {APP_LOG_IN, BROADCAST_SETTING_UPDATE, BROADCAST_SKIP, SET_UP_BROADCAST} from "../Config/ApiUrl";
import {ProgressionBarMixin} from "./Mixins/ProgressionBarMixin";

export default {
    name: "PusherSetupWizard",
    mixins: [FormMixin,ProgressionBarMixin],
    components: {
        'loader': Preloader
    },
    props: {
        appName: {},
        installerConfig: {}
    },
    data() {
        return {
            isActive:{
                skipButton: false
            },
            skipButtonLoading: false,
            environment: {
                broadcast_driver: '',
                pusher_app_id: '',
                pusher_app_key: '',
                pusher_app_secret: '',
                pusher_app_cluster: '',

            },
            errors: {},
            loading: false,
            providerList: [
                {id: "", value: this.$t("choose_one")},
                {id: "pusher", value: this.$t("pusher")},
                {id: "ajax", value: this.$t("ajax")},
            ]
        }
    },
    created() {
        let parseData = JSON.parse(this.installerConfig);
        this.isActive.skipButton = parseData?.skip_option?.pusher_set_up;
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        submitData() {
            this.submitFromFixin('post', BROADCAST_SETTING_UPDATE, this.environment);
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            setTimeout(() => {
                window.location = urlGenerator('/admin/users/login')
            },1000);

        },
        afterFinalResponse() {
            this.loading = false;
        },
        afterError(response) {
            this.loading = false;
        },
        skipStep() {
            this.skipButtonLoading = true;
            axiosPost(BROADCAST_SKIP, {}).then((response) => {
                this.$toastr.s('', response.data.message);
                setTimeout(() => {
                    window.location = urlGenerator('/admin/users/login')
                },1000);

            });
        },
    }
}
</script>

