<template>
   <fieldset class="form-group product-information-group mb-4">
      <legend class="form-group-title border-bottom pb-2">
         {{ $t('edit') }}
         {{ $t('product_information') }}
      </legend>

      <div class="row align-items-center mb-3 product-info-input product-name-input">
         <label class="col-12 col-lg-3" for="product-name">{{ $t('name') }}</label>
         <app-input
            class="form-input col-12 col-lg-7"
            id="product-name"
            placeholder="Enter name"
            type="text"
            v-model="relevantFormData.productName"
         />
      </div>

      <div class="row product-info-input mb-3 product-description-input">
         <label class="col-12 col-lg-3" for="product-description">{{ $t('description') }}</label>
         <app-input
            class="form-input col-12 col-lg-7"
            id="product-description"
            placeholder="Enter description"
            type="textarea"
 				    :text-area-rows="5"
            v-model="relevantFormData.productDescription"
         />
      </div>

      <div class="row product-info-input mb-3 product-img-input">
         <label class="col-12 col-lg-3" for="product-img">{{ $t('image') }}</label>
         <app-input
            class="form-input col-12 col-lg-7"
            id="product-img"
            :label="$t('upload_file')"
            type="file"
            v-model="relevantFormData.productImage"
         />
      </div>

      <div class="row product-info-input mb-3 product-gallery-input">
         <label class="col-12 col-lg-3" for="product-img">{{ $t('product_gallery') }}</label>
         <div class="form-input col-12 col-lg-7">
            <app-input
               class="form-input"
               id="product-gallery"
               type="dropzone"
               v-model="relevantFormData.productGallery"
            />
            <small>
               {{ $t('recommended_type_jpg') }}
            </small>
         </div>
      </div>
   </fieldset>
</template>

<script>
export default {
   name: 'product-info-group',
   props: ['selectableLists', 'relevantFormData'],
};
</script>
