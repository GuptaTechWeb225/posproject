<template>
   <div id="sub-category-details" class="w-90 mx-auto my-3">
      <div class="table-header d-flex align-items-center mb-4">
         <app-icon class="size-32 header-icon d-block mr-2" name="columns" />
         <p class="header-text pb-0 mb-0">
            {{ data.name }} {{ $t('has') }}
            {{ data.sub_categories.length }} {{ $t(`${data.sub_categories.length > 1 ? 'sub_categories' : 'sub_category' }`) }}
         </p>
      </div>

      <div class="sub-category-display row">
         <app-sub-category
             v-for="(subCategory, i) in data.sub_categories" :key="i"
             :subCategory="subCategory"
             :tableId="tableId"
         />
      </div>
   </div>
</template>

<script>
export default {
   name: 'sub-category-details',
   props: ['data', 'tableId'],
};
</script>

<style>
.w-90 {
   width: 90%;
}
</style>
