<template>
    <div class="content-wrapper">
        <app-page-top-section
            :directory="`${$t('contacts')} | <a href=''>${$t('back_to',
            {destination: $allLabel('customers', true)} )}</a>`"
            :hide-button="true"
            :title="$t('import_customers')"
            icon="message-circle"
        />
        <div class="card border-0 card-with-shadow">
            <div class="card-body">
                <div class="mb-primary">
                    <div class="note-title d-flex">
                        <app-icon name="book-open"/>
                        <h6 class="card-title pl-2">{{ $t('csv_format_guideline') }}</h6>
                    </div>
                    <div class="note note-warning p-4">
                        <p class="my-1">- {{ $t('csv_guideline_1') }}</p>
                        <p class="my-1">- {{ $t('csv_guideline_1.5') }}</p>
                        <p class="my-1">- {{ $t('csv_guideline_2') }}</p>
                        <p class="my-1">- {{ $t('csv_guideline_3') }}</p>
                    </div>
                </div>


                <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-3">
                    {{ $t('csv_download_label') }}
                    <a class="ml-2 text-primary cursor-pointer"
                       @click.prevent="sampleDownload"> {{ $t('download') }}
                    </a>
                </div>

                <div class="form-group">
                    <app-input
                        v-if="dropZoneBoot"
                        v-model="files"
                        :error-message="$errorMessage(errors, 'customer')"
                        :maxFiles="1"
                        :required="true"
                        type="dropzone"
                    />
                </div>

                <div class="row">
                    <div class="col-12 mt-4">
                        <app-submit-button :loading="loading" @click="submitData"/>
                        <app-cancel-button btn-class="ml-2" @click=""/>
                    </div>
                </div>

            </div>
        </div>

        <app-customer-import-preview-modal
            v-if="isPreviewModalActive"
            :customers="customers"
            @close="isPreviewModalActive = false"
            @succeed="afterFilteredDataSaved"
        />
    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {CUSTOMERS_IMPORT} from "../../../Config/ApiUrl-CPB";
import AppFunction from "../../../../core/helpers/app/AppFunction";

export default {
    name: "CustomerImport",
    mixins: [FormHelperMixins],
    data() {
        return {
            files: [],
            customers: {},
            isPreviewModalActive: false,
            sampleFileType: [],
            dropZoneBoot: true,
        }
    },
    methods: {
        submitData() {
            this.fieldStatus.isSubmit = true;
            this.loading = true;
            this.message = '';
            this.errors = {};

            let formData = new FormData;

            if (this.files.length) {
                formData.append('customers', this.files[0])
            }

            this.submitFromFixin(
                'post', CUSTOMERS_IMPORT, formData
            )
        },

        afterSuccess({data}) {
            if (data.status == true) {
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
                window.location = AppFunction.getAppUrl('customer/lists');
            } else {
                this.isPreviewModalActive = true;
            }

            this.customers = data
            this.dropZoneBoot = false;
            this.files = [];
            setTimeout(() => this.dropZoneBoot = true)
            if (data.customers.length) {
                this.isPreviewModalActive = true;
            }

        },

        afterError(response) {
            if (response)
                this.$toastr.e(response.data.errors.customers[0]);
        },

        afterImportSucceed(data) {
            if (data.hasOwnProperty('filtered')) {
                this.customers = data
                this.isPreviewModalActive = true
            } else {
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
            }
        },

        afterFilteredDataSaved(data) {
            if (data.hasOwnProperty('filtered')) {
                this.customers = data
                this.isPreviewModalActive = true
            } else {
                $("#importPreviewModal").modal("hide")
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
                window.location = AppFunction.getAppUrl('customer/lists');
            }
        },

        sampleDownload() {
            let commas = ''
            let keys = ['first_name', 'last_name', 'tin', 'customer_group'];

            this.downloadCSV(
                `${keys.join(',')}\n` +
                `John,Doe,584556,default\n` +
                `Hobart,,7495623,silver\n` +
                `James,MaCgil,7495623,gold\n` +
                `Jesse,Pinkman,5451202,\n` +
                `Jesse,Todd,54156156,default\n`
            )
        },

        downloadCSV(csv) {
            let e = document.createElement('a');
            e.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
            e.target = '_blank';
            e.download = `${this.$t('customers')}.csv`;
            e.click();
        }
    },
}
</script>