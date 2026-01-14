<template>
    <section id="app-inventory-stock" class="content-wrapper">
        <!-- top page section -->
        <app-page-top-section :title="$t('stock')">
            <div class="dropdown d-inline-block btn-dropdown"
                 v-if="this.$can('import_stock')"
            >
                <button
                    class="btn btn-success dropdown-toggle ml-0 mr-2"
                    aria-expanded="false"
                    aria-haspopup="true"
                    data-toggle="dropdown"
                    type="button"
                >
                    {{ $t('action') }}
                </button>

                <!-- temporary action dropdown -->
                <div class="dropdown-menu">
                    <a class="dropdown-item d-flex align-items-center p-3"
                       v-if="this.$can('import_stock')"
                       href="#" @click="openImportStockDialog">
                        <app-icon class="size-15 mr-2" name="download"/>
                        {{ $t('import_stock') }}
                    </a>

                </div>
            </div>

            <app-default-button
                class="btn btn-primary"
                v-if="this.$can('create_lot')"
                :title="$addLabel('lot')"
                :url="('/lot/add')"/>
        </app-page-top-section>

        <app-table v-if="options.url" :id="table_id" :options="options" @action="triggerAction" />

        <app-stock-create-edit-modal
            :value="showModal"
            @input="handleInputEvent"
            v-if="showModal"
        />

        <app-activation-alert-popup
            v-if="activationModalActive"
            @activation-popup-close="handleActivationPopupClose"
        />
        <app-stock-adjustment-create-edit-modal
            v-if="stockAdjustmentModalActive"
            v-model="stockAdjustmentModalActive"
            :item-to-adjust="itemToAdjust"
            @close="stockAdjustmentModalActive = false"
        />

        <!--     import stock -->
        <stock-import-modal v-if="importDialog" @modal-close="closeImportDialog"/>

    </section>
</template>

<script>
import StockTableMixin from '../../Mixins/Stock/StockTableMixin';
import StockModal from './StockModal';
import StockImportModal from "./StockImportModal";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {STOCK_ADJUSTMENTS} from "../../../Config/ApiUrl-CP";

export default {
    name: 'app-inventory-stock',
    mixins: [StockTableMixin],
    components: {
        'app-stock-modal': StockModal,
        StockImportModal
    },
    data() {
        return {
            importDialog: false,
            table_id: 'stock-table',
            stocks: '',
            showModal: false,
            stockAdjustmentModalActive: false,
            activationModalActive: false,
            itemToAdjust: '',
        };
    },
    methods: {
        openImportStockDialog() {
            return window.location.replace(urlGenerator('/import/stock'));
        },
        closeImportDialog() {
            this.importDialog = false
        },
        triggerAction(row, action, active) {
            switch (action.name) {
                case "show_stock_overview":
                    const {variant: {id: variant_id}} = row;
                    return window.location.replace(urlGenerator(`inventory/stock/overview/variant/${variant_id}`));
                    break;
                case "stock_adjust":
                    this.itemToAdjust = row.variant_id;
                    this.stockAdjustmentModalActive = true;
                    break;
                default:
                    this.getAction(row, action, active)
            }
        },
        handleInputEvent(emittedData) {
            this.showModal = emittedData;
        },
        handleActivationPopupClose() {
            $('#activation-alert-modal').modal('hide');
            this.activationModalActive = false;
        },
    },
};
</script>
