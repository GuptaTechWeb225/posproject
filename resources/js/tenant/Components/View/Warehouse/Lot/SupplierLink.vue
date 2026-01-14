<template>
    <p class="pb-0">
    	 <a class="text-info cursor-pointer" :href="supplierDetailsPagePath" @click="handleRowClick">
        	  {{ value?.name }}
    	 </a>
    </p>
</template>

<script>
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: "SupplierLink",
    props: ['value'],
    methods: {
        handleRowClick() {
           window.location.replace(urlGenerator(this.supplierDetailsPagePath));
        }
    },
    computed: {
        supplierDetailsPagePath() {
            return urlGenerator('supplier/details/' + this.value?.id);
        }
    }
}
</script>

<style scoped>

</style>
