<template>
    <div class="content-wrapper">
        <app-breadcrumb
            v-if="stockData.length"
            :page-title="ucWords($t('stock_overview'))"
            :button="{label: $t('back_to_product_details'), url: PRODUCT_DETAILS + product_id}"
        />

      <app-breadcrumb
          v-else
          :page-title="ucWords($t('stock_overview'))"
          :button="{label: $t('back_to_product_details'), url: PRODUCTS }"
      />

        <app-pre-loader v-if="loading"/>

        <template v-else>
            <div class="d-flex" style="gap: 1rem;" v-if="stockData.length">
                <div class="card card-with-shadow stock-details p-3" style="flex: 1">
                    <img
                        :src="(variantInCurrentBranchStock && variantInCurrentBranchStock.thumbnail) ? urlGenerator( variantInCurrentBranchStock.thumbnail.full_url) : defaultStockThumbnail"
                        alt="stock image"
                        style="height: 14rem;"
                        class="rounded mb-2"
                    />
                    <h5 class="variant-name">{{
                            variantInCurrentBranchStock ? variantInCurrentBranchStock.variant.name : ''
                        }}</h5>
                    <p class="variant-upc text-muted">
                        {{ variantInCurrentBranchStock ? `# ${variantInCurrentBranchStock.variant.upc}` : '' }}</p>
                    <hr/>
                    <p>
                        <span class="text-muted">{{ $t('quantity') }}</span>:
                        <span
                            :class="`value ${variantInCurrentBranchStock ? !variantInCurrentBranchStock.available_qty ? 'text-danger' : 'text-success' : ''}`">{{
                                variantInCurrentBranchStock ? variantInCurrentBranchStock.available_qty : 0
                            }}</span>
                    </p>
                </div>
                <div style="flex: 6">
                    <app-table id="stock-overview-table" :options="options" @action="triggerAction"/>
                </div>
            </div>

            <template v-else>
                <app-empty-data-block :message="$t('empty_data_block_dummy_message')" />
            </template>
        </template>

        <app-internal-transfer-create-edit-modal
            v-if="internalTransferModalActive"
            :modal-title="$t('request_transfer')"
            v-model="internalTransferModalActive"
            :selected-url="selectedUrl"
            :variant-id="variant_id"
            @close="internalTransferModalActive = false"
        />
    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import StockOverViewMixin from "../../../Mixins/StockOverViewMixin";
import {PRODUCT_DETAILS, STOCK_OVERVIEW, PRODUCTS} from "../../../../Config/ApiUrl-CP";
import {ucWords} from "../../../../../common/Helper/Support/TextHelper";
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";
import {urlGenerator} from "../../../../Helper/Helper";
import DropdownAction from "../../../../../core/components/datatable/DropdownAction";
import {mapGetters} from "vuex";

export default {
    name: "StockOverview",
    mixins: [HelperMixin, StockOverViewMixin],
    props: ["variant_id"],
    components: {
        "dropdown-action": DropdownAction
    },
    data() {
        return {
            isModalActive: false,
            loading: true,
            selectedUrl: '',
            ucWords,
            PRODUCT_DETAILS,
            PRODUCTS,
            internalTransferModalActive: false,
            STOCK_OVERVIEW,
            product_id: '',
            selectedBranchOrWarehouseId: 1,
            urlGenerator,
            actions: [
                {
                    title: this.$t('req._for_internal_transfer'),
                    type: 'modal',
                    name: 'internal_transfer_request',
                },
            ],
            stockData: []
        }
    },
    async mounted() {
        try {
            const stockOverview = await axiosGet(STOCK_OVERVIEW + this.variant_id);
            this.stockData = stockOverview.data.data;
            this.product_id = this.stockData[0]?.variant.product_id;
            this.loading = false;
        } catch (e) {
            this.$toastr.e(e)
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
        variantInCurrentBranchStock() {
            const variantInCurrentBranchStock = this.stockData.find(data => (''+data.branch_or_warehouse_id) === (''+this.getBranchOrWarehouseId));
            if (variantInCurrentBranchStock) return variantInCurrentBranchStock;

            const totalStockAcrossBranches = this.stockData?.map(data => data.available_qty).reduce((a, v) => a + v, 0)
            return {...this.stockData[0], available_qty: totalStockAcrossBranches}
        },
        defaultStockThumbnail() {
            return urlGenerator('images/product/default_product.png')
        },
    },
    methods: {
        triggerAction(row, action, active) {
            switch (action.name) {
                case 'internal_transfer_request':
                    this.internalTransferModalActive = true;
                    break;
                case 'remove':
                    break;
            }
        },
    }
}
</script>
