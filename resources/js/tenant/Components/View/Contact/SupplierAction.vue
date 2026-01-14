<template>
    <div>
        <p class="text-primary cursor-pointer" @click="handleSupplierNameClick"> {{ rowData.name }} </p>
        <small class="d-block text-muted my-1">#{{ rowData.supplier_no }}</small>
    </div>
</template>

<script>
import {SUPPLIER_DETAILS} from "../../../Config/ApiUrl-CPB";
import {urlGenerator} from "../../../Helper/Helper";

export default {
    name: "AppSupplierAction",
    props: ['value', 'rowData'],
    data() {
        return {
            SUPPLIER_DETAILS
        }
    },
    methods: {
        handleSupplierNameClick() {
            return window.location.replace(urlGenerator(SUPPLIER_DETAILS + this.rowData.id));
        }
    }
}
</script>
