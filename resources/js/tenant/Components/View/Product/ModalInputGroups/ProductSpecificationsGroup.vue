<template>
   <fieldset class="form-group specification-group mb-4">
      <legend class="form-group-title border-bottom pb-2">
         {{ $t('edit') }}
         {{ $t('specification') }}
      </legend>

      <div class="row align-items-center specification-input product-group-input">
         <label class="col-12 col-lg-3" for="product-group-input">{{ $t('group') }}</label>
         <div class="row no-gutters col-12 col-lg-7 form-group d-flex">
            <app-input
               class="form-input col-10 pr-2 col-lg-11"
               type="search-select"
               id="product-group-input"
               :list="selectableLists.productGroupList"
               :placeholder="
                  $t('search_and_select', {
                     name: $t('group').toLowerCase(),
                  })
               "
               v-model="relevantFormData.productGroupId"
            />
            <button class="btn specification-add-btn default-base-color col-2 col-lg-1" @click.prevent="activateModal('group')">
               <app-icon class="primary-text-color" name="plus" />
            </button>
         </div>
      </div>

      <div class="row specification-input product-category-input">
         <label class="col-12 col-lg-3" for="product-category-input">{{ $t('category') }}</label>
         <div class="row no-gutters col-12 col-lg-7 form-group d-flex">
            <app-input
               class="form-input col-10 pr-2 col-lg-11"
               type="search-select"
               id="product-category-input"
               :list="selectableLists.productCategoryList"
               :placeholder="
                  $t('search_and_select', {
                     name: $t('category').toLowerCase(),
                  })
               "
               v-model="relevantFormData.productCategoryId"
            />
            <button class="btn specification-add-btn default-base-color col-2 col-lg-1" @click.prevent="activateModal('category')">
               <app-icon class="primary-text-color" name="plus" />
            </button>
         </div>
      </div>
      <div class="row specification-input product-sub-category-input">
         <label class="col-12 col-lg-3" for="product-subcategory-input">{{ $t('sub_category') }}</label>
         <div class="row no-gutters col-12 col-lg-7 form-group d-flex">
            <app-input
               class="form-input col-10 pr-2 col-lg-11"
               type="search-select"
               id="product-subcategory-input"
               :list="selectableLists.productSubCategoryList"
               :placeholder="
                  $t('search_and_select', {
                     name: $t('sub_category').toLowerCase(),
                  })
               "
               v-model="relevantFormData.productSubCategoryId"
            />
            <button class="btn specification-add-btn default-base-color col-2 col-lg-1" @click.prevent="activateModal('subCategory')">
               <app-icon class="primary-text-color" name="plus" />
            </button>
         </div>
      </div>
      <div class="row specification-input product-brand-input">
         <label class="col-12 col-lg-3" for="product-brand-list">{{ $t('brand') }}</label>
         <div class="row no-gutters col-12 col-lg-7 form-group d-flex">
            <app-input
               class="form-input col-10 pr-2 col-lg-11"
               type="search-select"
               id="product-brand-list"
               :list="selectableLists.productBrandList"
               :placeholder="
                  $t('search_and_select', {
                     name: $t('brand').toLowerCase(),
                  })
               "
               v-model="relevantFormData.productBrandId"
            />
            <button class="btn specification-add-btn default-base-color col-2 col-lg-1" @click.prevent="activateModal('brand')">
               <app-icon class="primary-text-color" name="plus" />
            </button>
         </div>
      </div>
      <div class="row specification-input product-unit-input">
         <label class="col-12 col-lg-3" for="product-unit-input">{{ $t('unit') }}</label>
         <div class="row no-gutters col-12 col-lg-7 form-group d-flex">
            <app-input
               class="form-input col-10 pr-2 col-lg-11"
               type="search-select"
               id="product-unit-input"
               :list="selectableLists.productUnitList"
               :placeholder="
                  $t('search_and_select', {
                     name: $t('unit').toLowerCase(),
                  })
               "
               v-model="relevantFormData.productUnitId"
            />
            <button class="btn specification-add-btn default-base-color col-2 col-lg-1" @click.prevent="activateModal('unit')">
               <app-icon class="primary-text-color" name="plus" />
            </button>
         </div>
      </div>
      <div class="row specification-input product-variant-input">
         <label class="col-12 col-lg-3" for="product-type-input">{{ $t('product_type') }}</label>
         <app-input
            class="form-input col-12 col-lg-9"
            id="product-type-input"
            type="radio"
            v-model="relevantFormData.productTypesList"
            :list="selectableLists.productTypesList"
         />
      </div>
   </fieldset>
</template>

<script>
import ProductCreateMixin from "../AddProduct/ProductCreateMixin";

export default {
   name: 'product-specifications-group',
   props: ['selectableLists', 'relevantFormData'],
   mixins: [ProductCreateMixin],
   methods: {
      activateModal(modalName = 'placeholdername') {
         this.$emit('activate-specifications-modal', modalName);
      }
   }
};
</script>
