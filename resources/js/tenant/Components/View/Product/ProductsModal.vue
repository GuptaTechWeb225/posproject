<template>
   <app-modal
      modal-id="product-edit-modal"
      modal-size="extra-large"
      modal-alignment="top"
      @close-modal="closeModal"
   >
      <!-- Modal header -->
      <template slot="header">
         <h5 class="modal-heading">
            {{ $t('edit_product') }}
         </h5>

         <button class="close outline-none" @click.prevent="closeModal">
            <app-icon name="x" />
         </button>
      </template>

      <!-- Modal body -->
      <template slot="body">
         <form class="product-edit-form" ref="form" :data-url="''">
            <product-info-group
               :selectableLists="selectableLists"
               :relevantFormData="formData.productInfo"
            />

            <product-specifications-group
               :selectableLists="selectableLists"
               :relevantFormData="formData.productSpecifications"
               @activate-specifications-modal="handleSpecificationsModalActivation"
            />

            <product-details-group
               :selectableLists="selectableLists"
               :relevantFormData="formData.productDetails"
            />
         </form>

      </template>

      <!-- Modal Footer -->
      <template slot="footer" class="justify-content-start">
         <app-submit-button btn-class="mr-2" />
         <app-cancel-button btn-class="btn-secondary" @click.prevent="closeModal"/>
      </template>
   </app-modal>
</template>

<script>
import { FormMixin } from '../../../../core/mixins/form/FormMixin';
import ProductDetailsGroup from './ModalInputGroups/ProductDetailsGroup.vue';
import ProductSpecificationsGroup from './ModalInputGroups/ProductSpecificationsGroup.vue';
import ProductInfoGroup from './ModalInputGroups/ProductInfoGroup.vue';

export default {
   name: 'app-product-edit-modal',
   mixins: [FormMixin],
   components: {
      'product-details-group': ProductDetailsGroup,
      'product-specifications-group': ProductSpecificationsGroup,
      'product-info-group': ProductInfoGroup,
   },
   data() {
      return {
         selectableLists: {
            productGroupList: [
               { id: 1, name: 'Selectable group 1' },
               { id: 2, name: 'Selectable group 2' },
            ],
            productCategoryList: [
               { id: 1, name: 'Selectable category 1' },
               { id: 2, name: 'Selectable category 2' },
            ],
            productSubCategoryList: [
               { id: 1, name: 'Selectable sub category 1' },
               { id: 2, name: 'Selectable sub category 2' },
            ],
            productBrandList: [
               { id: 1, name: 'Selectable brand 1' },
               { id: 2, name: 'Selectable brand 2' },
            ],
            productUnitList: [
               { id: 1, name: 'Selectable unit 1' },
               { id: 2, name: 'Selectable unit 2' },
            ],
            productTypesList: [
               { id: 0, value: this.$t('single_product') },
               { id: 1, value: this.$t('variant_product') },
            ],
            productWarrantyTypesList: [
               { id: 0, value: this.$t('hour_s') },
               { id: 1, value: this.$t('day_s') },
               { id: 2, value: this.$t('month_s') },
               { id: 3, value: this.$t('year_s') },
            ],
            productTaxList: [
               { id: 0, value: this.$t('tax1') },
               { id: 1, value: this.$t('tax2') },
            ],
         },
         formData: {
            productInfo: {
               productName: '',
               productDescription: '',
               productImage: '',
               productGallery: '',
            },
            productSpecifications: {
               productGroupId: '',
               productCategoryId: '',
               productSubCategoryId: '',
               productBrandId: '',
               productUnitId: '',
            },
            productDetails: {
               productUpc: '',
               productWarrantyDuration: '',
               productWarrantyDurationType: 'Months',
               productTax: '',
               productLowStockReminderQuantity: '',
            },
         },
      };
   },
   methods: {
      closeModal() {
         this.$emit('modal-close');
      },
      handleSpecificationsModalActivation(specificationModalToActivate) {
        this.$emit('activate-specifications-modal', specificationModalToActivate);
      }
   },
};
</script>

<style>
.opacity-50 {
  opacity: 0.5 !important;
}
</style>
