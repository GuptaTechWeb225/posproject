<template>
    <modal id="variant-create-edit-modal"
           v-model="showModal"
           :title="$t('variant_details')"
           @submit="submitData" :loading="loading"
           :preloader="preloader">

        <form ref="form" @submit.prevent="submitData">

            <div class="form-group mb-3">
                <label> {{ $t('variant_thumbnail') }} </label>
                <app-input
                    type="custom-file-upload"
                    v-model="formData.variant_thumbnail"
                    @change="handleVariantThumbnailChange"
                    :generate-file-url="false"
                    :label="$t('upload_file')"
                    :error-message="$errorMessage(errors, 'variant_thumbnail')"
                />
            </div>

            <template v-if="dropzoneBoot">
                <div class="form-group mb-3">
                    <label> {{ $t('variant_gallery') }} </label>
                    <app-input
                        type="dropzone"
                        class="dropzone-product-gallery mb-2"
                        v-model="formData.variant_gallery"
                        :error-message="$errorMessage(errors, 'variant_gallery')"
                        :generate-file-url="true"
                        @file-removed="handleDropzoneFileRemove"
                    />
                </div>

                <template v-if="product_id">
                    <dropzone-img-remove-alert-message v-if="Boolean(formData.variant_gallery.length)"/>
                </template>
            </template>

            <app-form-group
                :label="$t('description')"
                class="mb-3"
                type="textarea"
                v-model="formData.description"
                :placeholder="$placeholder('description_here')"
            />

            <div class="row mb-3">
                <div class="form-group col-lg-6">
                    <label for="">{{ $t('tax_/_vat') }}</label>

                    <app-input
                        type="search-and-select"
                        :placeholder="$placeholder('tax')"
                        :options="taxOptions"
                        v-model="formData.tax_id"
                        :error-message="$errorMessage(errors, 'tax_id')"
                    />
                </div>

                <div class="form-group col-lg-6 mb-3">
                    <label for="">{{ $t('low_stock_reminder_(LSR)') }}</label>
                    <app-input
                        v-model="formData.stock_reminder_quantity"
                        type="number"
                        :error-message="$errorMessage(errors, 'stock_reminder_quantity')"
                        :label="$t('low_stock_reminder_quantity')"
                        :placeholder="$placeholder('low_stock_reminder_quantity')"
                    />
                </div>
            </div>

        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {SELECTABLE_TAX} from "../../../../Config/ApiUrl-CPB";
import {axiosDelete, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {collection} from "../../../../Helper/Helper";
import DropzoneImgRemoveAlertMessage from "./DropzoneImgRemoveAlertMessage";
import {PRODUCT_IMAGE_DELETE} from "../../../../Config/ApiUrl-CP";

export default {
    name: "VariantDetailsCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    components: {'dropzone-img-remove-alert-message': DropzoneImgRemoveAlertMessage},
    props: {
        productId: {},
        selectedUrl: {
            type: String
        },
        variant: {
            required: true,
        },
        variantGalleryBackup: {
            type: Array
        }
    },
    created() {
        if (this.product_id) return setTimeout(() => {
            if (this.formData.variant_gallery.every(img => typeof img  === 'string')) {
                this.dropzoneBoot = true;
                return this.emitData();
            }
            this.formData.variant_gallery = [];
            this.formData.variant_gallery = this.variantGalleryBackup[0];
            this.dropzoneBoot = true;
            return this.emitData();
        }, 1000);
        this.formData.variant_gallery = [];
        this.dropzoneBoot = true;

        this.emitData();
    },
    data() {
        return {
            dropzoneBoot: null,
            SELECTABLE_TAX,
            selectableList: {
                productTypes: [
                    {id: 'single', value: this.$t('single_product')},
                    {id: 'variant', value: this.$t('variant_product')}
                ],
                warranty_duration_type: [
                    {id: 'hour_s', value: this.$t('hour_s')},
                    {id: 'day_s', value: this.$t('day_s')},
                    {id: 'month_s', value: this.$t('month_s')},
                    {id: 'year_s', value: this.$t('year_s')},
                ],
            },
            formData: {
                variant_thumbnail: this.variant.variant_thumbnail,
                variant_gallery: this.variant.variant_gallery,
                status_id: this.variant.status_id,
                tax_id: this.variant.tax_id,
                description: this.variant.description,
                stock_reminder_quantity: this.variant.stock_reminder_quantity,
                warranty_duration: this.variant.warranty_duration,
                warranty_duration_type: this.variant.warranty_duration_type,
                variant_photos: this.variant.photos
            },
            taxOptions: {
                url: urlGenerator(SELECTABLE_TAX),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader",
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.product_id)
            },
        }
    },
    async mounted() {
        if (!this.formData.variant_thumbnail) return;
        if (!this.formData.variant_thumbnail.full_url) return;
        const response = await fetch(this.formData.variant_thumbnail.full_url);
        const blob = await response.blob();
        this.formData.variant_thumbnail = new File([blob], 'image.jpg', {type: blob.type});
    },
    methods: {
        emitData() {
            this.$emit('variant-data-update', {...this.formData, variantIndex: this.variant.variantIndex});
        },
        handleDropzoneFileRemove(file) {
            if (!this.product_id) return;
            const fileToRemove = this.formData.variant_photos.find(photo => photo.path === file.dataURL);
            if (!fileToRemove) return;
            const {id: fileToRemoveId} = fileToRemove;
            axiosDelete(PRODUCT_IMAGE_DELETE + fileToRemoveId)
                .then(res => this.$toastr.s('', res.data.message))
                .catch(err => this.$toastr.e('', err.message));
        },
        handleVariantThumbnailChange() {
            this.$emit('variant-thumbnail-change', this.variant.variantIndex);
        },
        submitData() {
            this.emitData();
            this.afterSuccess();
        },
        afterSuccess() {
            $('#variant-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.variant.variantThumbnailChanged = true;
        },
        afterSuccessFromGetEditData({data}) {
            this.formData = {...this.formData, ...data};
            this.preloader = false;
        },
    },
    computed: {
        product_id() {
            return eval(this.productId);
        },
        attachmentErrorLength() {
            return Object.keys(this.errors).filter(item => {
                return item.includes('variant_gallery');
            }).length > 0
        }
    },
}
</script>

