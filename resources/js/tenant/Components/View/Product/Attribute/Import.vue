<template>
    <div class="content-wrapper">
        <app-page-top-section
            :directory="`${$t('product')} | <a href=''>${$t('back_to',
            {destination: $allLabel('import_variant_attribute', true)} )}</a>`"
            :hide-button="true"
            :title="$t('import_variant_attribute')"
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
                        <p class="my-1">- {{ $t('csv_guideline_product_group_field_name') }}</p>
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
                        :error-message="$errorMessage(errors, 'product_variant_attribute')"
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

        <app-attribute-import-preview-modal
            v-if="isPreviewModalActive"
            :variantAttributes="variantAttributes"
            @close="isPreviewModalActive = false"
            @succeed="afterFilteredDataSaved"
        />
    </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {ATTRIBUTE_IMPORT} from "../../../../Config/ApiUrl-CP";
import AppFunction from "../../../../../core/helpers/app/AppFunction";

export default {
    name: "Import",
    mixins: [FormHelperMixins],
    data() {
        return {
            files: [],
            variantAttributes: {},
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
                formData.append('variantAttributes', this.files[0])
            }

            this.submitFromFixin(
                'post', ATTRIBUTE_IMPORT, formData
            )
        },
        afterSuccess({data}) {
            if (data.status == true) {
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
                window.location = AppFunction.getAppUrl('attributes');
            } else {
                this.isPreviewModalActive = true;
            }

            this.variantAttributes = data
            this.dropZoneBoot = false;
            this.files = [];
            setTimeout(() => this.dropZoneBoot = true)
            if (data.variantAttributes.length) {
                this.isPreviewModalActive = true;
            }
        },

        afterError(response) {
            if (response)
                this.$toastr.e(response.data.errors.variantAttributes[0]);
        },

        afterImportSucceed(data) {
            if (data.hasOwnProperty('filtered')) {
                this.variantAttributes = data
                this.isPreviewModalActive = true
            } else {
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
            }
        },

        afterFilteredDataSaved(data) {
            if (data.hasOwnProperty('filtered')) {
                this.brands = data
                this.isPreviewModalActive = true
            } else {
                this.isPreviewModalActive = false;
                $("#importPreviewModal").modal('hide');
                window.location = AppFunction.getAppUrl('attributes');
                this.$toastr.s(data.message)
            }
        },

        sampleDownload() {
            let commas = ''
            let keys = ['name'];

            this.downloadCSV(
                `${keys.join(',')}\n` +
                `John\n` +
                `Bruce\n` +
                `Hank\n` +
                `MdCarol\n` +
                `Arthur\n`
            )
        },

        downloadCSV(csv) {
            let e = document.createElement('a');
            e.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
            e.target = '_blank';
            e.download = `${this.$t('attribute')}.csv`;
            e.click();
        }
    },

}
</script>