<template>
    <div class="py-5">

        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         :style="'width:' +calculateBarWidth(2)+'%'" aria-valuenow="0" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="card card-with-shadow border-0 mb-primary">

            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <app-icon name="database" class="primary-text-color mr-2"/>
                    {{ $t('database_configuration') }}
                </h5>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <div class="col-12">
                        <app-note
                            :title="$t('database_configuration_requirements')"
                            content-type="html"
                            :notes="databaseRequirementNote"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('db_connection') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="select"
                            v-model="environment.database_connection"
                            :list="[
                               {id: 'mysql', value: $t('mysql')},
                               {id: 'pgsql', value: $t('pgsql')},
                               {id: 'sqlsrv', value: $t('sqlsrv')},
                            ]"
                            :placeholder="$t('app_environment')"
                            :error-message="$errorMessage(errors, 'app_environment')"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_hostname') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="text"
                            v-model="environment.database_hostname"
                            :placeholder="$t('database_hostname')"
                            :error-message="$errorMessage(errors, 'database_hostname')"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_port') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="text"
                            v-model="environment.database_port"
                            :placeholder="$t('database_port')"
                            :error-message="$errorMessage(errors, 'database_port')"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_name') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="text"
                            v-model="environment.database_name"
                            :placeholder="$t('database_name')"
                            :error-message="$errorMessage(errors, 'database_name')"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_username') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="text"
                            v-model="environment.database_username"
                            :placeholder="$t('database_username')"
                            :error-message="$errorMessage(errors, 'database_username')"
                            autocomplete="off"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_password') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            :show-password="true"
                            type="password"
                            v-model="environment.database_password"
                            :placeholder="$t('database_password')"
                            :error-message="$errorMessage(errors, 'database_password')"
                            autocomplete="off"
                        />
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group mt-5">
            <app-submit-button
                :loading="loading"
                @click="submitData"
                btn-class="btn-block"
                :label="$t('save_&_next')"
            />
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
import {
    ADDITIONAL_REQUIREMENT,
    APP_INSTALL_ADMIN_INFO, DATABASE_CONFIGURATION,
    GENERATE_PURCHASE_CODE_URL,
    GET_DATABASE_HOSTNAME,
    PURCHASE_CODE
} from "../Config/ApiUrl";
import EnvironmentWizardMixin from "./Mixins/PurchaseCodeWizardMixin";
import {axiosGet, urlGenerator} from "../Helpers/AxiosHelper";
import {optional} from "../Helpers/Helpers";
import Loader from "../core/components/preloders/Loader";
import {ProgressionBarMixin} from "./Mixins/ProgressionBarMixin";

export default {
    name: "DatabaseWizard",
    mixins: [FormMixin, ProgressionBarMixin],
    props: {
        appName: {},
        installerConfig: {
            include_option: {}
        }
    },
    components: {
        'loader': Loader
    },
    data() {
        return {
            environment: {
                database_connection: 'mysql',
                database_hostname: 'localhost',
                database_port: '3306',
                database_name: '',
                database_username: '',
                database_password: '',
            },
            purchase_code_error_occurs: false,
            isActivePurchaseCodeDiv: true,
            isActiveDatabaseDiv: false,
            errors: {},
            loading: false,
            isPurchaseCode: '',
            error_logs: {},
            backLoading: false,
            isActiveCopy: false,
            isActivePurchaseErrorModal: false,
            databaseRequirementNote: '<ul>' +
                '<li>' + '<b>Format: </b>' + this.$t('should_not_contain') + ' ' + 'white space,' + ' ' + '#' + '.' + '</li>' +
                '<li>' + this.$t('make_sure_your_database_credentials_are_correct') + '</li>' +
                '</ul>',
        }
    },

    methods: {

        submitData() {
            this.loading = true;
            this.submitFromFixin('post', 'setup/store-database-config', this.environment);
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            setTimeout(() => {
                window.location.href = urlGenerator(APP_INSTALL_ADMIN_INFO);
            }, 1000);

        },

        afterError(response) {
            this.message = '';
            this.loading = false;
            this.errors = optional(response.data, 'errors') || {};
            if (response?.status != 422)
                this.$toastr.e(response.data.message || response.statusText)
        },

        back() {
            this.backLoading = true;
            window.location = urlGenerator(PURCHASE_CODE);
        },
    },
}
</script>