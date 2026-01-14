<template>
    <modal id="stock-create-edit-modal"
           modal-id="stock-create-edit-modal"
           v-model="showModal"
           :loading="loading"
           :scrollable="false"
           :preloader="preloader"
           size="large"
           :title="generateModalTitle('stock')"
           @close-modal="closeModal"
           @submit="submit">

        <form
            ref="form"
            class="row"
            :data-url='selectedUrl ? selectedUrl : LOT'
            enctype="multipart/form-data">
          <!-- PRODUCT -->
          <div class="product-name-input mb-3 col-12">
            <label for="product-input">{{ $t('product_name') }}</label>
            <app-input
                id="product-input"
                type="select"
                name="product"
                :placeholder="$t('search_and_select')"
                v-model="formData.product"
            />
          </div>

          <div class="row no-gutters col-12">
            <!-- BRAND -->
            <div class="product-brand-input mb-3 col-12 col-lg-6 pr-3">
              <label for="brand-input">{{ $t('brand') }}</label>
              <app-input
                  id="brand-input"
                  type="select"
                  name="brand"
                  :placeholder="$t('choose_a_brand')"
                  v-model="formData.brand"
              />
            </div>

            <!-- CATEGORY -->
            <div class="product-category-input mb-3 col-12 col-lg-6">
              <label for="category-input">{{ $t('category') }}</label>
              <app-input
                  id="category-input"
                  type="select"
                  name="category"
                  :placeholder="$t('choose_a_category')"
                  v-model="formData.category"
              />
            </div>

            <!-- VARIANT -->
            <div class="product-variant-input mb-3 col-12 col-lg-6 pr-3">
              <label for="variant-input">{{ $t('variant') }}</label>
              <app-input
                  id="variant-input"
                  type="select"
                  name="variant"
                  :placeholder="$t('choose_a_variant')"
                  v-model="formData.variant"
              />
            </div>

            <!-- QUANTITY -->
            <div class="product-quantity-input mb-3 col-12 col-lg-6">
              <label for="quantity-input">{{ $t('quantity') }}</label>
              <app-input
                  id="quantity-input"
                  type="text"
                  name="quantity"
                  :placeholder="$t('choose_a_quantity')"
                  v-model="formData.quantity"
              />
            </div>
          </div>

          <!-- SELLING_PRICE -->
          <div class="product-selling_price-input mb-3 col-12">
            <label for="selling_price-input">{{
                $t('selling_price')
              }}</label>
            <app-input
                id="selling_price-input"
                type="text"
                name="selling_price"
                :placeholder="$t('search_and_select')"
                v-model="formData.sellingPrice"
            />
          </div>

          <!-- SKU -->
          <div class="product-sku-input mb-3 col-12">
            <label for="sku-input">{{ $t('SKU') }}</label>
            <div class="row no-gutters">
              <app-input
                  id="sku-input"
                  type="text"
                  class="col-10 col-lg-11"
                  name="sku"
                  :placeholder="$t('search_and_select')"
                  v-model="formData.sku"
              />
              <button class="btn default-base-color col-2 col-lg-1">
                <app-icon class="size-19 primary-text-color" name="refresh-cw" />
              </button>
            </div>
          </div>

          <!-- VARIANT THUMBNAIL -->
          <div class="product-variant_thumbnail-input mb-4 col-12">
            <label for="variant_thumbnail-input">{{
                $t('variant_thumbnail')
              }}</label>
            <app-input
                id="variant_thumbnail-input"
                type="file"
                :label="$t('choose_file')"
                name="variant_thumbnail"
                :placeholder="$t('search_and_select')"
                v-model="formData.variantThumbNail"
            />
          </div>

          <!-- VARIANT GALLERY -->
          <div class="product-variant_gallery-input mb-3 col-12">
            <label for="variant_gallery-input">{{
                $t('variant_gallery')
              }}</label>
            <app-input
                id="variant_gallery-input"
                type="dropzone"
                :label="$t('add_variant_gallery')"
                name="variant_gallery"
                :placeholder="$t('search_and_select')"
                v-model="formData.variantGallery"
            />
          </div>

        </form>
    </modal>

</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {LOT, SELECTABLE_BRANCH, SELECTABLE_RECEIVED_BY} from "../../../../Config/ApiUrl-CP";
import DateFunction from "../../../../../core/helpers/date/DateFunction";
import {formatDateForServer, formatDateTimeForServer} from "../../../../Helper/DateTimeHelper";

export default {
    name: "StockAddEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            showNote: true,
            formData: {
              product: '',
              brand: '',
              category: '',
              variant: '',
              quantity: '',
              sellingPrice: '',
              sku: '',
              variantThumbnail: '',
              variantGallery: '',
            },
            LOT,
            SELECTABLE_BRANCH,
            SELECTABLE_RECEIVED_BY,
        }
    },
    methods: {
        submit() {
            if (this.formData.arrival_date)
                this.formData.arrival_date = DateFunction.getDateFormatForBackend(this.formData.arrival_date);

            let submittedData = {...this.formData};

            submittedData.arrival_date = formatDateForServer(submittedData.arrival_date);
            submittedData.received_at = formatDateTimeForServer(this.formData.received_at);

            this.save(submittedData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#stock-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'stock-table');
        },
        afterSuccessFromGetEditData({data}) {

            this.preloader = false;
            this.formData.expense_date = data.expense_date ? new Date(data.expense_date) : '';
            this.formData = data;


            //show attachment in dropzone
            this.formData.attachments = _.map(this.formData.attachments, 'path');
        },
        closeModal() {
          this.$emit('modal-close');
        }
    },
}
</script>