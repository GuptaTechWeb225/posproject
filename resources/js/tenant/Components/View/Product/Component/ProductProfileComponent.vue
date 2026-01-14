<template>
    <div v-if="rowDataValue" class="d-flex align-items-center">
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
            <a :href="urlGenerator(PRODUCT_DETAILS+rowDataValue.id)" @click="handleProductNameClick">{{ rowDataValue.name }}</a>
            <small class="d-block text-muted">{{ smallText }}</small>
        </div>
    </div>
</template>

<script>
import {PRODUCT_DETAILS} from "../../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../../Helper/Helper.js";

export default {
    name: "ProductProfileComponent",
    props: ['rowData', 'tableId'],
    mounted() {
        if (this.rowData.variant)
            this.rowData.variant.upc ? this.smallText = this.rowData.variant.upc : '';

        if (this.rowData.status)
            this.rowData.status.name ? this.status = this.rowData.status.name : '';
    },
    data() {
        return {
            status: this.rowDataValue?.status.name,
            brand: this.rowDataValue?.brand ? this.rowData.brand.name : '',
            PRODUCT_DETAILS,
            urlGenerator,
            basePath: 'images/product/default_product.png',
            smallText: '',
        }
    },
    computed: {
        img() {
            if (this.rowData.thumbnail) return urlGenerator(this.rowData.thumbnail.full_url)
            if (this.rowData.variant) {
                if (this.rowData.variant.thumbnail) return urlGenerator(this.rowData.variant.thumbnail.full_url);
            }
            return this.defaultImage();
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
            return window.location.replace(urlGenerator(this.PRODUCT_DETAILS + this.rowDataValue.id));
        },
        getBrand() {
            return this.rowData?.brand?.name ? this.rowData?.brand?.name : '';
        }
    },
}
</script>
