<template>
   <fieldset class="form-group details-group mb-4">
      <legend class="form-group-title border-bottom pb-2">
         {{ $t('edit') }}
         {{ $t('item_details') }}
      </legend>
      <div class="row detail-input product-upc-input mb-3">
         <label class="col-12 col-lg-3 text-sm" for="product-upc-input">{{ $t('upc') }}</label>
         <div class="row no-gutters col-12 col-lg-7 form-input">
            <app-input
               id="product-upc-input"
               type="text"
               class="col-10 pr-2 col-lg-11"
               v-model="relevantFormData.productUpc"
            />

            <button
               class="btn upc-generate-btn default-base-color col-2 col-lg-1"
               type="button"
               :title="$t('get_a_new_upc')"
            >
               <app-icon class="size-19 primary-text-color" name="refresh-cw" />
            </button>
         </div>
      </div>

      <div class="row detail-input product-warranty-duration-input mb-3">
         <label class="text-sm col-12 col-lg-3" for="product-warranty-duration-input">{{
            $t('warranty_duration')
         }}</label>
         <div class="row no-gutters col-12 col-lg-7 form-input">
            <app-input
               id="product-warranty-duration-input"
               type="number"
               class="col-9 pr-2 col-lg-8"
               :placeholder="$placeholder('warranty_duration')"
               v-model="relevantFormData.productWarrantyDuration"
            />

            <app-input
                class="col-3 col-lg-4"
               type="select"
               :list="selectableLists.productWarrantyTypesList"
               v-model="relevantFormData.productWarrantyDurationType"
            />
         </div>
      </div>

      <div class="detail-input product-tax-input mb-3 row">
         <label for="product-tax-input" class="text-sm col-12 col-lg-3">{{ $t('tax') }}</label>
         <app-input
            class="form-input col-12 col-lg-7"
            id="product-tax-input"
            type="select"
            :list="selectableLists.productTaxList"
            v-model="relevantFormData.productTax"
         />
      </div>
      <div class="detail-input product-low-stock-reminder-quantity row">
         <label for="product-low-stock-reminder-quantity" class="text-sm col-12 col-lg-3">
            {{ $t('low_stock_reminder_quantity') }}
         </label>
         <app-input
            class="form-input col-12 col-lg-7"
            type="number"
            :placeholder="$placeholder('low_stock_reminder_quantity')"
            v-model="relevantFormData.productLowStockReminderQuantity"
         />
      </div>
   </fieldset>
</template>

<script>
export default {
   name: 'product-details-group',
   props: ['selectableLists', 'relevantFormData'],
};
</script>
