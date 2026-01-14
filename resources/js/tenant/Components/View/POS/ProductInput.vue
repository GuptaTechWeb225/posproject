<template>
    <div :class="`product-input ${ viewMode === 'primary' ? 'col-md-7 custom-resize' : 'col-md-6' } col-12 d-flex`">
        <!-- <app-input -->
        <!--     v-show="viewMode === 'primary'" -->
        <!--     class="mr-2" -->
        <!--     v-if="productOptions" -->
        <!--     style="flex: 1;" -->
        <!--     type="search-and-select" -->
        <!--     :placeholder="$placeholder('product')" -->
        <!--     :options="productOptions" -->
        <!--     v-model="selectedProduct" -->
        <!--     :key="productInputKey" -->
        <!-- /> -->

        <app-input
            class="mr-2"
            v-if="viewMode === 'primary'"
            :autofocus="viewMode === 'primary'"
            @keyup="handleSearchTermInput"
            style="flex: 1;"
            v-model="searchTerm"
            type="text"
            :placeholder="$placeholder('product')"
        />

        <app-input
            v-if="viewMode !== 'primary'"
            :autofocus="viewMode !== 'primary'"
            class="mr-2"
            style="flex: 1;"
            type="text"
            v-model="productUpc"
            @keyup.enter="handleBarcodeScan"
            :placeholder="$placeholder('product_upc')"
        />
        <button class="btn btn-primary" @click="filterBtnClick">
            <app-icon name="filter"/>
        </button>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {SALES_VIEW_PRODUCTS} from "../../../Config/ApiUrl-CP";
import {mapActions, mapGetters, mapMutations} from "vuex";
import _ from "lodash";

export default {
    mixins: [FormHelperMixins],
    name: 'ProductInput',
    props: ['viewMode', 'filterQueryParams'],
    data() {
        return {
            searchTerm: '',
            productInputKey: 0,
            productUpc: '',
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId', 'saleViewProducts']),
        productOptions() {
            if (!this.getBranchOrWarehouseId) return null;
            return {
                url: urlGenerator(`${SALES_VIEW_PRODUCTS}`),
                query_name: "search",
                params: {
                    branch_or_warehouse_id: this.getBranchOrWarehouseId
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (data) => ({id: data.variant.id, value: data.variant.name}) 
            }
        },
    },
    methods: {
        ...mapActions(['addItemToCart', 'setProducts']),
        ...mapMutations(['SET_PRODUCTS', 'SET_IS_ON_LAST_PAGE_OF_PRODUCTS']),
        handleSearchTermInput: _.debounce(async function(e) {
            this.$emit('product-search', this.searchTerm)
            this.SET_PRODUCTS(null)
            const categoryId = this.filterQueryParams?.category_id
            const brandId = this.filterQueryParams?.brand_id
            const salesViewProducts =
                await axiosGet(`${SALES_VIEW_PRODUCTS}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}&search=${this.searchTerm}${categoryId ? '&category_id=' + categoryId : ''}${brandId ? '&brand_id=' + brandId : ''}`)
            const productsFromApiResponse = salesViewProducts.data.data;
            this.SET_PRODUCTS(productsFromApiResponse)
            this.SET_IS_ON_LAST_PAGE_OF_PRODUCTS(false)

            if(e?.keyCode !== 13) return;
            if (!productsFromApiResponse.length) return;
            const matchingVariant = productsFromApiResponse.find(product => product.variant.upc.toString() === this.searchTerm.toString())
            if (!matchingVariant) return;
            this.addItemToCart(matchingVariant) 
            this.searchTerm = ''
            this.handleSearchTermInput()

        }, 500),
        async handleBarcodeScan() {
            const salesViewProducts =
                await axiosGet(`${SALES_VIEW_PRODUCTS}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}&search=${this.productUpc}`)
            const productsFromApiResponse = salesViewProducts.data.data;

            const itemData = productsFromApiResponse.find(product => product.variant.upc.toString() === this.productUpc.toString())
            if (itemData) {
                if (itemData.available_qty <= 0) return this.$toastr.e(this.$t('item_out_of_stock'));
                this.clearProductUpc();
                return this.addItemToCart(itemData)
            };
            this.$toastr.e(this.$t('item_not_found'));
            return setTimeout(() => {
                this.clearProductUpc();
            }, 1500)
        },
        clearProductUpc() {
            this.productUpc = '';
        },
        filterBtnClick() {
            this.$emit("filter-btn-click");
        }
    }
}
</script>
