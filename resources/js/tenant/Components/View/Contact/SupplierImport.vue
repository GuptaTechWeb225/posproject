<template>
    <div class="content-wrapper">
        <app-page-top-section
            :directory="`${$t('contacts')} | <a href=''>${$t('back_to',
            {destination: $allLabel('customers', true)} )}</a>`"
            :hide-button="true"
            :title="$t('import_suppliers')"
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
                       @click.prevent="sampleDownload">{{ $t('download') }}
                    </a>
                </div>
                <div class="form-group">
                    <app-input
                        v-if="dropZoneBoot"
                        v-model="files"
                        :error-message="$errorMessage(errors, 'supplier')"
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

        <app-supplier-import-preview-modal
            v-if="isPreviewModalActive"
            :suppliers="suppliers"
            @close="isPreviewModalActive = false"
            @succeed="afterFilteredDataSaved"
        />
    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {CUSTOMERS_EXPORT, SUPPLIER_IMPORT} from "../../../Config/ApiUrl-CPB";
import AppFunction from "../../../../core/helpers/app/AppFunction";

export default {
    name: "SupplierImport",
    mixins: [FormHelperMixins],
    data() {
        return {
            files: [],
            suppliers: {},
            isPreviewModalActive: false,
            sampleFileType: [],
            dropZoneBoot: true
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
                formData.append('suppliers', this.files[0])
            }

            this.submitFromFixin(
                'post', SUPPLIER_IMPORT, formData
            )
        },
        afterSuccess({data}) {
            if (data.status == true) {
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
                window.location = AppFunction.getAppUrl('supplier/lists');
            } else {
                this.isPreviewModalActive = true;
            }

            this.suppliers = data
            this.dropZoneBoot = false;
            this.files = [];
            setTimeout(() => this.dropZoneBoot = true)
            if (data.suppliers.length) {
                this.isPreviewModalActive = true;
            }
        },

        afterError(response) {
            if (response)
                this.$toastr.e(response.data.errors.suppliers[0]);
        },

        afterImportSucceed(data) {
            if (data.hasOwnProperty('filtered')) {
                this.suppliers = data
                this.isPreviewModalActive = true
            } else {
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
            }
        },

        afterFilteredDataSaved(data) {
            if (data.hasOwnProperty('filtered')) {
                this.suppliers = data
                this.isPreviewModalActive = true
            } else {
                this.isPreviewModalActive = false;
                $("#importPreviewModal").modal('hide');
                window.location = AppFunction.getAppUrl('supplier/lists');
                this.$toastr.s(data.message)
            }
        },

        sampleDownload() {
            let commas = ''
            let keys = ['first_name', 'last_name', 'email', 'phone_number', 'address', 'company'];

            this.downloadCSV(
                `${keys.join(',')}\n` +
                `John,Doe,email@example.com,82365047,53 Halsey Road GOOLWA,Ekström HB\n` +
                `Bruce,Miller,OzanInci@claimab.com,82365047,53 Halsey Road GOOLWA,Spinka Ltd\n` +
                `Hank,Rearden,crystal18@gutmann.com,82365047,3507 Whitman Court Stamford,Fahey Inc\n` +
                `MdCarol,Miller,Ah-Uaynih@iffymedia.com,82365047,1724 Michigan Avenue Pittsburgh,Rippin Group\n` +
                `Arthur,Dent,ShaunWong@claimab.com,82365047,236 Oliver Street,\n`
            )
        },

        downloadCSV(csv) {
            let e = document.createElement('a');
            e.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
            e.target = '_blank';
            e.download = `${this.$t('suppliers')}.csv`;
            e.click();
        }
    },
}
</script>