<template>
    <div class="py-5">

        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         :style="'width:' +calculateBarWidth(3)+'%'" aria-valuenow="0" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        <div class="card card-with-shadow border-0 mb-primary">

            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="user" class="primary-text-color mr-2"/>
                    {{ $t('admin_login_details') }}
                </h5>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <div class="col-12">
                        <app-note
                            :title="$t('password_requirements')"
                            :notes="[$t('password_requirements_message')]"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('full_name') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="text"
                            v-model="environment.full_name"
                            :placeholder="$t('full_name')"
                            :error-message="$errorMessage(errors, 'full_name')"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('email') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="text"
                            v-model="environment.email"
                            :placeholder="$t('email')"
                            :error-message="$errorMessage(errors, 'email')"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('password') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="password"
                            :show-password="true"
                            v-model="environment.password"
                            :placeholder="$t('password')"
                            :error-message="$errorMessage(errors, 'password')"/>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group mt-5">
            <app-submit-button
                :loading="loading"
                @click="submitData"
                btn-class="btn-block"
                :label="setLabel()"/>
        </div>
        <div class="form-group mt-3">
            <button class="btn btn-light text-center btn-block"
                    type="button"
                    @click.prevent="back()"
                    :disabled="backLoading">
                <span class="w-100">
                    <loader v-if="backLoading" :loader-color="'brand-color'"/>
                    <template v-if="!backLoading">
                         {{ $t('back') }}
                    </template>
                </span>
            </button>
        </div>
    </div>
</template>

<script>

import {FormMixin} from "../core/mixins/form/FormMixin";
import {urlGenerator} from "../Helpers/AxiosHelper";
import {
    APP_INSTALL_ADMIN_INFO,
    APP_LOG_IN,
    DATABASE_CONFIGURATION,
    PURCHASE_CODE,
    SET_UP_EMAIL
} from "../Config/ApiUrl";
import Loader from "../core/components/preloders/Loader";
import {ProgressionBarMixin} from "./Mixins/ProgressionBarMixin";

export default {
    name: "AdminWizard",
    mixins: [FormMixin, ProgressionBarMixin],
    components: {
        'loader': Loader
    },
    props: {
        appName: {},
        installerConfig: {
            include_option: {
                email_set_up: ''
            }
        }
    },
    data() {
        return {
            environment: {
                full_name: '',
                email: '',
                password: '',
            },
            errors: {},
            loading: false,
            backLoading: false,
        }
    },

    methods: {

        submitData() {
            this.loading = true;
            const {first_name, last_name} = this.names;
            const formData = {...this.environment, first_name, last_name}
            this.submitFromFixin('post', 'setup/store-admin-info', formData);
        },

        afterSuccess(response) {
            let installerConfig = JSON.parse(this.installerConfig) ? JSON.parse(this.installerConfig) : null;
            this.$toastr.s('', response.data.message);
            this.loading = false;
            setTimeout(() => {
                window.location = installerConfig?.include_option?.email_set_up ? urlGenerator(SET_UP_EMAIL) : urlGenerator(APP_LOG_IN)
            }, 1000);
        },

        afterFinalResponse() {
            this.loading = false;
        },

        afterError(response) {
            this.errors = response.data.errors;
            this.loading = false;
        },
        back() {
            this.backLoading = true;
            setTimeout(() => {
                window.location = urlGenerator(DATABASE_CONFIGURATION);
            }, 1000);

        },
        setLabel() {
            let installerConfig = JSON.parse(this.installerConfig);
            return installerConfig?.include_option?.email_set_up ? this.$t('save_&_next') : this.$t('save_&_install')
        }
    },
    computed: {
        names() {
            const full_name_spited = this.environment.full_name.split(' ').filter(name => name);
            if (full_name_spited.length) {
                if (full_name_spited.length === 2) {
                    return {
                        first_name: full_name_spited[0],
                        last_name: full_name_spited[1]
                    }
                } else if (full_name_spited.length === 1) {
                    return {
                        first_name: full_name_spited[0],
                        last_name: ''
                    }
                } else if (full_name_spited.length === 3) {
                    return {
                        first_name: `${full_name_spited[0]} ${full_name_spited[1]}`,
                        last_name: full_name_spited[2]
                    }
                } else {
                    return {
                        first_name: full_name_spited[0],
                        last_name: full_name_spited.slice(1, full_name_spited.length).join(' ')
                    }
                }
            }
            return {
                first_name: '',
                last_name: ''
            }
        }
    }

}
</script>