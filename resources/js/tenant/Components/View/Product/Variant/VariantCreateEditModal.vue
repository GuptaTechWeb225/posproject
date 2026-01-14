<template>
    <modal id="variant-modal"
           v-model="showModal"
           :loading="loading"
           :preloader="preloader"
           :title="generateModalTitle('variant')"
           size="large"
           @submit="submitData">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : VARIANT'
            @submit.prevent="submitData">

            <div class="row no-gutters mb-0 pb-0">
                <div class="form-group col-lg-6 pr-3">
                    <app-form-group
                        v-model="formData.name"
                        :error-message="$errorMessage(errors, 'name')"
                        :label="$t('variant_name')"
                        :placeholder="$placeholder('name')"
                        :required="true"
                    />
                </div>

                <div class="form-group col-lg-6">
                    <label>{{ $t('upc') }}</label>
                    <div class="row no-gutters">
                        <div class="col-10 col-md-11">
                            <app-overlay-loader v-if="showUpcLoader"/>
                            <app-input
                                :class="`margin-right-8 ${showUpcLoader && 'temp-disable'}`"
                                :placeholder="$placeholder('upc')"
                                :required="true"
                                :key="dropzoneKey"
                                @keyup="handleSingleProductUpcChange(e, variantId)"
                                v-model="formData.upc"
                                :error-message="$errorMessage(errors, 'upc')"
                            />
                            <small class="text-danger text-xs"
                                   v-if="!upcIsUnique">{{ $t('the_upc_you_entered_is_not_unique') }}</small>
                        </div>
                        <div class="col-2 col-md-1">
                            <button type="button"
                                    data-toggle="tooltip"
                                    :title="$t('get_a_new_upc')"
                                    data-placement="top"
                                    :key="'refresh-cw'"
                                    class="btn default-base-color btn-block h-100 p-1"
                                    @click="generateVariantUpc">
                                <app-icon class="size-19 primary-text-color" name="refresh-cw"/>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <label> {{ $t('variant_thumbnail') }} </label>
                <app-input
                    :label="$t('upload_file')"
                    type="custom-file-upload"
                    :generate-file-url="false"
                    v-model="formData.variant_thumbnail"
                    @change="handleVariantThumbnailChange"
                    :error-message="$errorMessage(errors, 'variant_thumbnail')"
                />
            </div>

            <div class="form-group">
                <label> {{ $t('variant_gallery') }} </label>
                <app-input
                    type="dropzone"
                    class="dropzone-product-gallery mb-2"
                    v-model="formData.variant_gallery"
                    :key="dropzoneKey"
                    @file-removed="handleDropzoneFileRemove"
                />
            </div>

            <dropzone-img-remove-alert-message v-if="Boolean(formData.variant_gallery?.length)" />

            <app-form-group
                :label="$t('description')"
                type="textarea"
                v-model="formData.description"
                :placeholder="$placeholder('description_here')"
            />

            <div class="form-group row no-gutters">
                <div class="form-group col-lg-6 pr-3">
                    <label for="">{{ $t('tax') }}*</label>
                    <app-input
                        type="search-and-select"
                        :placeholder="$placeholder('tax')"
                        :options="taxOptions"
                        :key="ssKey"
                        v-model="formData.tax_id"
                        :error-message="$errorMessage(errors, 'tax_id')"
                    />
                </div>

                <app-form-group
                    v-model="formData.stock_reminder_quantity"
                    class="col-lg-6"
                    type="number"
                    :error-message="$errorMessage(errors, 'stock_reminder_quantity')"
                    :label="$t('low_stock_reminder_quantity') + '*'"
                    :placeholder="$placeholder('low_stock_reminder_quantity')"
                />
            </div>
            <small class="text-size-13 text-muted">
                {{ $t("allowed_file_types_for_product") }}
            </small>

        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {SELECTABLE_TAX} from "../../../../Config/ApiUrl-CPB";
import {collection, formDataAssigner, randomAlphanumeric} from "../../../../Helper/Helper";
import {GENERATE_UPC, PRODUCT_IMAGE_DELETE, VARIANT} from "../../../../Config/ApiUrl-CP";
import {axiosDelete, axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import ProductUpcValidateMixin from "../AddProduct/ProductUpcValidateMixin";
import DropzoneImgRemoveAlertMessage from "../AddProduct/DropzoneImgRemoveAlertMessage";

export default {
    name: "VariantCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin, ProductUpcValidateMixin],
    props: {
        productId: {}
    },
    components: {
        'dropzone-img-remove-alert-message': DropzoneImgRemoveAlertMessage
    },
    data() {
        return {
            discountInput: true,
            dropzoneKey: 0,
            ssKey: 0, // search and select key
            variantThumbnailChanged: false,
            formData: {
                product_id: this.productId,
                stock_reminder_quantity: '',
                tax_id: '',
                description: '',
                variant_gallery: [],
                variant_thumbnail: '',
                upc: '',
            },
            variant_thumbnail: '',
            variant_photos: [],
            SELECTABLE_TAX,
            VARIANT,
            selectableList: {
                taxes: [],
                attributes: [],
            },
            image: '',
            taxOptions: {
                url: urlGenerator(SELECTABLE_TAX),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader",
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.productId)
            },
        }
    },
    computed: {
        variantId() {
            const selectedUrlSplit = this.selectedUrl.split('/');
            return selectedUrlSplit[selectedUrlSplit.length - 1];
        }
    },
    methods: {
        handleDropzoneFileRemove(file) {
            const fileToRemove = this.variant_photos.find(photo => photo.path === file.dataURL);
            if (!fileToRemove) return;
            const { id: fileToRemoveId } = fileToRemove;
            axiosDelete(PRODUCT_IMAGE_DELETE + fileToRemoveId)
                .then(res => this.$toastr.s('', res.data.message))
                .catch(err => this.$toastr.e('', err.message));
        },
        handleVariantThumbnailChange() {
            this.variantThumbnailChanged = true;
        },
        generateVariantUpc() {
            this.upcIsUnique = true;
            axiosGet(GENERATE_UPC).then(({data: UPC}) => this.formData.upc = UPC);
        },
        async setVariantThumbnail() {
            if (!this.formData.variant_thumbnail) return;
            const response = await fetch(this.formData.variant_thumbnail); const blob = await response.blob();
            this.formData.variant_thumbnail = new File([blob], 'image.jpg', {type: blob.type});
        },
        submitData() {
            this.loading = true;
            this.message = '';
            this.errors = {};

            if (!this.formData.tax_id)  {
                this.$toastr.e(this.$t('please_select_a_tax'));
                this.loading = false;
                return;
            }
            if (!this.formData.stock_reminder_quantity) {
                this.$toastr.e(this.$t('please_specify_a_stock_reminder_quantity'));
                this.loading = false;
                return;
            }
            this.formData.variant_gallery = this.formData.variant_gallery.filter(vgi => 'upload' in vgi);

            const formData = new FormData();

            for (const key in this.formData) {
                if(key === 'variant_thumbnail'){
                    if (this.variantThumbnailChanged)
                        formData.append(key, this.formData[key])
                } else {
                    formData.append(key, this.formData[key] )
                }
            }

            this.formData.variant_gallery.forEach((img, i) => formData.append(`variant_gallery[${i}]`, img));
            formData.delete('variant_gallery');

            if (this.selectedUrl) formData.append('_method', 'patch')

            return this.submitFromFixin(
                'post',
                this.selectedUrl ? this.selectedUrl : VARIANT,
                formData
            );
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#variant-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'variant-table');
            this.$hub.$emit('reload-product-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            if (this.formData.description === 'null') this.formData.description = '';
            this.variant_photos = response.data.photos.length ? response.data.photos : [];
            this.formData.variant_thumbnail = response.data.thumbnail? response.data.thumbnail.full_url : '';
            this.formData.variant_gallery = response.data.photos.length ? collection(response.data.photos).pluck('full_url') : [];
            this.dropzoneKey += 1;
            this.ssKey += 1;

            this.setVariantThumbnail();
        },
    },
}
</script>
