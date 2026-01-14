import {axiosGet, urlGenerator} from "../../Helpers/AxiosHelper";
import {
    ADDITIONAL_REQUIREMENT,
    DATABASE_CONFIGURATION,
    GENERATE_PURCHASE_CODE_URL,
    GET_DATABASE_HOSTNAME,
    PURCHASE_CODE_STORE
} from "../../Config/ApiUrl";
import {optional} from "../../Helpers/Helpers";

export default {
    data() {
        return {
            environment: {
                app_name: this.appName,
                environment: 'production',
                app_debug: 'false',
                app_url: window.localStorage.getItem('base_url') ?? window.origin,
                cache_driver: 'file',
                queue_connection: 'sync',
                session_driver: 'file',
                code: '',
            },
            purchase_code_error_occurs: false,
            isActivePurchaseCodeDiv: true,
            isActiveDatabaseDiv: false,
            loading: false,
            backLoading: false,
            errors: {},
            isPurchaseCode: '',
            error_logs: {},
            isActiveCopy: false,
            isActivePurchaseErrorModal: false,
        }
    },
    methods: {
        checkPurchaseCode() {
            this.errors = {};
            this.error_logs = {}
            this.purchase_code_error_occurs = false;
            this.isActiveCopy = false;
            if (this.environment.code === '') {
                this.errors.code = this.$t('this_field_is_required');
                return;
            }

            this.loading = true;
            let url = `${GENERATE_PURCHASE_CODE_URL}`,
                generatedUrl = '';

            this.axiosPost({url, data: this.environment}).then((res) => {
                generatedUrl = res.data;
            }).then(() => {

                delete axios.defaults.headers.common['X-Requested-With'];
                delete axios.defaults.headers.common['X-CSRF-TOKEN'];

                axios.get(generatedUrl).then(response => {

                    if (response?.data?.data === 'Verified') {
                        this.submitFromFixin('post', PURCHASE_CODE_STORE, this.environment);
                    } else {

                        let probableReason = this.$t(response.data.data);
                        this.errorGenerateAndLog(
                            response,
                            generatedUrl,
                            probableReason,
                            this.$t(response.data.data),
                            this.$t('it_seems_due_to')+' '+this.$t(response.data.data).toLowerCase(),
                            'then'
                        );
                    }

                }).catch(({response}) => {

                    if (response?.data?.data === 'Verified') {
                        this.submitFromFixin('post', PURCHASE_CODE_STORE, this.environment);

                    } else if (response?.status === 404) {

                        this.errorGenerateAndLog(
                            response,
                            generatedUrl,
                            this.$t(response?.data?.data),
                            this.$t(response?.data?.data),
                            this.$t('it_seems_due_to_purchase_code'),
                            'catch'
                        );

                    } else {

                        let probableReason = response?.status === 404 ? 'Server could not make request to check purchase code' : 'Unknown';
                        this.errorGenerateAndLog(
                            response,
                            generatedUrl,
                            probableReason,
                            this.$t('request_failed'),
                            this.$t('it_seems_due_to_some_network_issue'),
                            'catch'
                        );
                    }
                });
            });
        },

        errorGenerateAndLog(response, generatedUrl, probableReason, errRes, errNote, hook) {
            this.isActiveCopy = true
            this.errors = {};
            this.loading = false;
            this.purchase_code_error_occurs = true;
            this.errors.code = errRes;
            this.errors.note = errNote;

            this.errorLog({
                probable_reason: probableReason,
                errors: this.errors,
                hook: hook,
                request: 'get'
            }, generatedUrl, response)
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            setTimeout(() => {
                window.location.href = urlGenerator(DATABASE_CONFIGURATION);
            },1000);
        },

        afterError(response) {
            this.message = '';
            this.loading = false;
            this.errors = optional(response.data, 'errors') || {};
            if (response?.status != 422)
                this.$toastr.e(response.data.message || response.statusText)
            this.errorLog({probable_reason: 'Could not submit by post request', data: this.environment, hook: 'catch', request: 'post'}, 'setup/environment', response);
        },
        getDatabaseHostName() {
            axiosGet(`${GET_DATABASE_HOSTNAME}`).then(res => {
                this.environment.database_hostname = res.data?.host? res.data.host : 'localhost';
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
                this.errorLog({probable_reason: 'Could not get database hostname', hook: 'catch', request: 'get'}, `${GET_DATABASE_HOSTNAME}`, response);
            });
        },

        back(){
            this.backLoading = true;
            window.location = urlGenerator(ADDITIONAL_REQUIREMENT);
        },

        errorLog(useCase = null, url = null, response = null) {

            this.error_logs.case = useCase;
            this.error_logs.url = url;
            this.error_logs.error_response = response
            this.isActiveCopy = true;

        },

        showPurchaseErrorModal() {
            this.isActivePurchaseErrorModal = true;
        },

        closePurchaseErrorModal() {
            this.isActivePurchaseErrorModal = false
        }
    },
}