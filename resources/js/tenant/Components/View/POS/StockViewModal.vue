<template>
    <app-modal
        modal-id="stock-view-modal"
        v-model="showModal"
        modal-size="large"
        :loading="loading"
        :preloader="preloader"
        @close-modal="handleStockViewModalClose">

        <div class="modal-header">
            <h4> {{ $t('view_product_stocks') }} </h4>
        </div>

        <div class="modal-body">
            <div class="row no-gutters">
                <app-input
                    class="mr-2"
                    v-if="productOptions"
                    style="flex: 1;"
                    type="search-and-select"
                    :placeholder="$placeholder('product')"
                    @input="handleProductInput"
                    :options="productOptions"
                    v-model="variant_id"
                />
            </div>

            <div class="col-12 my-4 row no-gutters align-items-center" v-if="stockData.length">
                <div class="labels col-12 row border-bottom no-gutters">
                    <p class="text-muted col-md-4">{{ $t('product') }}</p>
                    <p class="text-muted col-md-4">
                        {{ $t('store') }}</p>
                    <p class="text-muted col-md-4 align-items-center text-right">{{ $t('quantity') }}</p>
                </div>

                <div
                    class="values row no-gutters align-items-baseline justify-content-between col-md-12 border-bottom py-2"
                    v-for="(item, index) in stockData"
                    :key="index">
                    <div class="col-md-4">
                        <app-avatar
                            :border="true"
                            :img="item.thumbnail?.path ? urlGenerator(item.thumbnail?.path) : urlGenerator('images/product/default_product.png')"
                            avatar-class="avatars-w-50"
                        />
                    </div>
                    <p class="col-md-4 pb-0">
                        <span style="width: 10px; height: 10px;" class="d-inline-block rounded-circle mr-1 bg-success"></span>
                        {{ item.branchOrWarehouse?.name }}
                    </p>
                    <p class="col-md-4 text-right text-success pb-0">{{ item.available_qty }}</p>
                </div>
            </div>

            <template v-else>
                <app-empty-data-block :message="$t('please_select_a_product_to_view_stock_data')"/>
            </template>

        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" @click="handleStockViewModalClose">{{ $t('close') }}</button>
        </div>

    </app-modal>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {SALES_VIEW_PRODUCTS, SELECTABLE_INVOICES, STOCK, STOCK_OVERVIEW} from "../../../Config/ApiUrl-CP";
import {mapGetters} from "vuex";

export default {
    name: "StockViewModal",
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            variant_id: '',
            urlGenerator,
            stockData: []
        }
    },
    methods: {
        async handleProductInput() {
            if (!this.variant_id) {
                this.stockData = [];
                return;
            };
            try {
                const stockOverview = await axiosGet(STOCK_OVERVIEW + this.variant_id);
                this.stockData = stockOverview.data.data;
            } catch (e) {
                this.$toastr.e(e)
            }
        },
        handleStockViewModalClose() {
            $("#stock-view-modal").modal("hide");
            this.$emit('close');
        },
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
        productOptions() {
            if (!this.getBranchOrWarehouseId) return null;
            return {
                url: urlGenerator(SALES_VIEW_PRODUCTS), // temporary
                query_name: "search",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (data) => ({id: data.variant.id, value: data.variant.name}),
            }
        },
    }
}
</script>
