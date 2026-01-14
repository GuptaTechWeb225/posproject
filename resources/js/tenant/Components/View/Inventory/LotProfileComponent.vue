<template>
    <div v-if="rowDataValue" class="d-flex">
        <div class="mr-2">
            <app-avatar
                :key="rowDataValue.id"
                :border="true"
                :img="img"
                avatar-class="avatars-w-60"
                :status="checkStatus"
                :title="rowDataValue.name"
            />
        </div>
        <div>
            <p class="text-primary item-link pb-0 cursor-pointer mb-0" v-if="!disableRedirect" @click="handleProductNameClick">{{ rowDataValue.name }}</p>
            <p class="mb-0" v-else>{{ rowDataValue.name }}</p>
            <small class="d-block">{{ numberWithCurrencySymbol(sellingPrice) }}</small>
            <small class="d-block text-muted">#{{ upc }}</small>
        </div>
    </div>
</template>

<script>
import {PRODUCT_DETAILS} from "../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../Helper/Helper";
import {numberWithCurrencySymbol} from "../../../Helper/Helper";

export default {
    name: "LotProfileComponent",
    props: ["rowData", "tableId", "disableRedirect"],
    mounted() {
        if (this.rowData.variant)
            this.rowData.variant.upc ? this.upc = this.rowData.variant.upc : '';

        if (this.rowData.variant)
            this.rowData.variant.selling_price ? this.sellingPrice = this.rowData.variant.selling_price : '';

        if (this.rowData.status)
            this.rowData.status.name ? this.status = this.rowData.status.name : '';
    },
    data() {
        return {
            status: this.rowDataValue?.status.name,
            brand: this.rowDataValue?.brand ? this.rowData.brand.name : "",
            numberWithCurrencySymbol,
            PRODUCT_DETAILS,
            basePath: 'images/product/default_product.png',
            upc: '',
            sellingPrice: '',
        }
    },
    computed: {
        img() {
           let product_type = this.rowData?.product?.product_type;
           if (product_type === 'single'){
             return this.rowData?.product?.thumbnail?.full_url ?? this.defaultImage();
           }

           if (product_type === 'variant') {
              return this.rowData?.thumbnail?.full_url ?? this.defaultImage();
           }
        },
        rowDataValue() {
            if (this.rowData.variant) return {...this.rowData, name: this.rowData.variant.name};
            return {...this.rowData};
        },
        checkStatus() {
            return this.status === 'status_active' ? 'success' : 'secondary';
        }
    },
    methods: {
        defaultImage() {
            if (this.rowData.photos && this.rowData.photos.length) return this.rowData.photos.find(item => item.type === "product_default_image")
            return urlGenerator('images/product/default_product.png');
        },
        handleProductNameClick() {
            return window.location.replace(urlGenerator(this.PRODUCT_DETAILS + this.rowDataValue.variant_id));
        },
        getBrand() {
            return this.rowData?.brand?.name ? this.rowData?.brand?.name : '';
        }
    },
}
</script>

<style>
.item-link:hover {
    color: skyblue;
}
</style>
