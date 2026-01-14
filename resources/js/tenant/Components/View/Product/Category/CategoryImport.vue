<template>
    <div class="content-wrapper">
        <app-page-top-section
            :directory="`${$t('category')} | <a href=''>${$t('back_to',
            {destination: $allLabel('categories', true)} )}</a>`"
            :hide-button="true"
            :title="$t('import_categories')"
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
                        :error-message="$errorMessage(errors, 'category')"
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

        <app-category-import-preview-modal
            v-if="isPreviewModalActive"
            :categories="categories"
            @close="isPreviewModalActive = false"
            @succeed="afterFilteredDataSaved"
        />
    </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {CATEGORIES_IMPORT, CATEGORY_LIST} from "../../../../Config/ApiUrl-CP";
import AppFunction from "../../../../../core/helpers/app/AppFunction";

export default {
    name: "CategoryImport",
    mixins: [FormHelperMixins],
    data() {
        return {
            files: [],
            categories: {},
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
                formData.append('categories', this.files[0])
            }

            this.submitFromFixin(
                'post', CATEGORIES_IMPORT, formData
            )
        },

        afterSuccess({data}) {
            if (data.status == true) {
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
                window.location = AppFunction.getAppUrl(CATEGORY_LIST);
            } else {
                this.isPreviewModalActive = true;
            }

            this.categories = data
            this.dropZoneBoot = false;
            this.files = [];
            setTimeout(() => this.dropZoneBoot = true)
            if (data.categories.length) {
                this.isPreviewModalActive = true;
            }

        },

        afterError(response) {
            if (response)
                this.$toastr.e(response.data.errors.categories[0]);
        },

        afterImportSucceed(data) {
            if (data.hasOwnProperty('filtered')) {
                this.categories = data
                this.isPreviewModalActive = true
            } else {
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
            }
        },

        afterFilteredDataSaved(data) {
            if (data.hasOwnProperty('filtered')) {
                this.categories = data
                this.isPreviewModalActive = true
            } else {
                $("#importPreviewModal").modal("hide")
                this.isPreviewModalActive = false;
                this.$toastr.s(data.message)
                window.location = AppFunction.getAppUrl(CATEGORY_LIST);
            }
        },

        sampleDownload() {
            let commas = ''
            let keys = ['name'];

            this.downloadCSV(
                `${keys.join(',')}\n` +
                `John\n` +
                `Hobart\n` +
                `James\n` +
                `Jesse\n` +
                `John\n`
            )
        },

        downloadCSV(csv) {
            let e = document.createElement('a');
            e.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
            e.target = '_blank';
            e.download = `${this.$t('categories')}.csv`;
            e.click();
        }
    },
}
</script>