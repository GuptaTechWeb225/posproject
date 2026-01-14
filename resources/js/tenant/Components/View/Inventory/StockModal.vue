<template>
   <app-modal
      modal-id="stock-modal"
      modal-size="extra-large"
      modal-alignment="top"
      @close-modal="closeModal"
   >
      <!-- Modal header -->
      <template slot="header">
         <h5 class="modal-heading">
            {{ $t('add_stock') }}
         </h5>

         <button class="close outline-none" @click="closeModal">
            <app-icon name="x" />
         </button>
      </template>

      <!-- Modal body -->
      <template slot="body">
         <div class="user-input">
            <form action="" class="row stock-form">
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

               <!-- BRAND -->
               <div class="product-brand-input mb-3 col-12 col-lg-6">
                  <label for="brand-input">{{ $t('brand') }}</label>
                  <app-input
                     id="brand-input"
                     type="select"
                     name="brand"
                     :placeholder="$t('search_and_select')"
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
                     :placeholder="$t('search_and_select')"
                     v-model="formData.category"
                  />
               </div>

               <!-- VARIANT -->
               <div class="product-variant-input mb-3 col-12 col-lg-6">
                  <label for="variant-input">{{ $t('variant') }}</label>
                  <app-input
                     id="variant-input"
                     type="select"
                     name="variant"
                     :placeholder="$t('search_and_select')"
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
                     :placeholder="$t('search_and_select')"
                     v-model="formData.quantity"
                  />
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
                     <button class="btn btn-primary col-2 col-lg-1">
                        <app-icon name="refresh-cw" />
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
         </div>
      </template>

      <!-- Modal Footer -->
      <template slot="footer" class="justify-content-start">
         <app-submit-button btn-class="mr-2" />
         <app-cancel-button btn-class="btn-secondary" />
      </template>
   </app-modal>
</template>

<script>
import { FormMixin } from '../../../../core/mixins/form/FormMixin';

export default {
   name: 'app-stock-modal',
   mixins: [FormMixin],
   data() {
      return {
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
      };
   },
   methods: {
      closeModal() {
         this.$emit('modal-close');
      },
   },
};
</script>
