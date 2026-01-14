<template>
    <app-modal
        :modal-id="modalId"
        :title="modalTitle"
        :preloader="preloader"
        modal-size="large"
        :hide-footer="true"
        @close-modal="$emit('closeModal')">

        <template slot="body">
            <app-overlay-loader v-if="preloader"/>

            <div v-else>
                <div class="note note-warning p-4 d-flex align-items-center justify-content-between">
                    <p class="mb-0">{{ $t('please_copy_the_error_log_below_and_contact_support') }}</p>
                    <button
                        type="button"
                        class="btn btn-primary text-white float-right width-90 ml-auto"
                        @click.stop.prevent="getCopiable">
                        <span v-if="!successfullyCopied">
                            <app-icon name="copy" class="size-21"/>
                        </span>
                        <span v-else class="animate__animated animate__fadeInDown">
                            <app-icon name="check" class="size-21"/>
                        </span>
                    </button>
                </div>

                <div class="overflow-auto border custom-scrollbar w-100 height-400 mt-4">
                    <vue-json-pretty :path="'res'" :data="{ key: errorLogs }"></vue-json-pretty>
                    <input type="hidden" id="errorLogs" :value="JSON.stringify({ key: errorLogs })">
                </div>
            </div>
        </template>
    </app-modal>
</template>

<script>

import VueJsonPretty from 'vue-json-pretty';
import 'vue-json-pretty/lib/styles.css';

export default {
    name: 'PurchaseErrorModal',
    props: {
        errorLogs: {}
    },
    components: {
        VueJsonPretty,
    },
    data() {
        return {
            preloader: false,
            modalId: 'purchase-code-error-log-modal',
            modalTitle: 'Error Log',
            successfullyCopied: false,
        }
    },
    methods: {
        getCopiable() {
            let errorLogsToCopy = document.querySelector('#errorLogs');

            errorLogsToCopy.setAttribute('type', 'text')
            errorLogsToCopy.select()

            try {
                let successful = document.execCommand('copy'),
                    msg = successful ? this.$t('error_log_copied_successfully') : this.$t('sorry_you_can_not_copy_the_link');

                this.successfullyCopied = true;
                this.$toastr.s(msg);

            } catch (err) {
                this.successfullyCopied = false;
                this.$toastr.s(this.$t('something_went_wrong'));
            }

            // Unselect the range
            errorLogsToCopy.setAttribute('type', 'hidden')
            window.getSelection().removeAllRanges()
        },
    }
}
</script>