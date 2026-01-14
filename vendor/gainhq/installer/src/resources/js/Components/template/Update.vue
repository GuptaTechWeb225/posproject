<template>
    <div v-if="!loading">
        <app-note
            :class="'mb-primary'"
            :title="$fieldTitle('update')"
            content-type="html"
            :notes="[Object.keys(no_update_message).length ? `${no_update_message.message_1}
          ${no_update_message.message_2 ? '\n' : ''} ${no_update_message.message_2}` : $t('update_warning_details')]"
        />

        <div v-if="afterUpdateInstruction"
             class="d-flex align-items-center justify-content-between mb-3 mt-3">
            <a @click.prevent="redirectHomePage()" href="">{{ afterUpdateInstruction }}</a>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3" v-for="update in updates.result" >
            {{ update.version }}
            <div class="btn-group btn-group-action d-inline-block">
                <a href="https://salex.gainhq.com/documentation/change-log.html"
                   target="_blank">{{ $t('change_logs') }}</a>
                <button type="button"
                        class="btn"
                        data-toggle="tooltip"
                        :title="$t('update')"
                        data-placement="top"
                        @click="updateApp(update.version)">

                    <app-icon name="download" />
                </button>
            </div>

        </div>

        <app-update-confirmation-modal v-if="confirmationModalActive"
                                       modal-id="app-confirmation-modal"
                                       @confirmed="confirmed()"
                                       @cancelled="cancelled"
                                       :message="$t('this_will_update_entire_app')"/>

    </div>
    <app-overlay-loader v-else/>
</template>

<script>
import {axiosGet, axiosPost, urlGenerator} from "../../Helpers/AxiosHelper";
import {APP_UPDATE, APP_UPDATE_INSTALL, GET_UPDATE_URL} from "../../Config/ApiUrl";

export default {
    name: "Update",
    props: ["props"],
    data() {
        return {
            updates: {},
            loading: true,
            confirmationModalActive: false,
            selectedVersion: '',
            no_update_message: {},
            afterUpdateInstruction: '',
        }
    },
    created() {
        this.getUpdates();
    },
    mounted() {
        setTimeout(function () {
            $('[data-toggle="tooltip"]').tooltip();
        }, 6000);
    },
    methods: {
        updateApp(version) {
            this.selectedVersion = version;
            this.confirmationModalActive = true;
        },
        confirmed() {
            this.loading = true;
            let url = '', purchase_response = '';
            axiosGet(GET_UPDATE_URL).then(response => {
                url = response.data;
            }).then(() => {
                delete axios.defaults.headers.common['X-Requested-With'];
                delete axios.defaults.headers.common['X-CSRF-TOKEN'];
                axios.get(url).then(response => {
                    purchase_response = response
                }).then(() => {
                    axiosPost(`${APP_UPDATE_INSTALL}` + '/' + this.selectedVersion, {}).then(({data}) => {
                        this.afterUpdateInstruction = this.$t('action_text_with_home_link');
                        this.$toastr.s('', data.message);
                        this.getUpdates(false);
                        this.confirmationModalActive = false;
                    }).catch(({response}) => {
                        this.$toastr.e('', response.data.message);
                    });
                });
            }).finally(() => {
                this.loader = false;
            });
        },
        cancelled() {
            this.confirmationModalActive = false;
            this.selectedVersion = '';
        },
        getUpdates(showError = true) {
            let url = '', purchase_response = '';
            axiosGet(GET_UPDATE_URL).then(response => {
                url = response.data;
            }).then(() => {
                delete axios.defaults.headers.common['X-Requested-With'];
                delete axios.defaults.headers.common['X-CSRF-TOKEN'];
                axios.get(url).then(response => {
                    purchase_response = response.data.data
                }).then(() => {
                    if (purchase_response === 'Verified') {
                        axiosGet(APP_UPDATE).then(response => {
                            this.updates = response.data;
                        }).catch(error => {
                            try {
                                this.no_update_message = JSON.parse(error.response.data.message)
                            } catch (e) {
                                if (showError) {
                                    this.no_update_message.message_1 = error.response.data.message;
                                    this.no_update_message.message_2 = '';
                                }
                            }
                            this.updates = {result: []};
                        })
                    }
                });
            }).finally(() => {
                this.loading = false;
            })
        },
        redirectHomePage() {
            window.location.replace(urlGenerator('/'));
        },
    },
}
</script>
