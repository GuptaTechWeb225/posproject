<template>
    <div class="content-wrapper">
        <app-page-top-section :title="$t('warehouse_stock')">

            <div>
                <div class="dropdown d-inline-block btn-dropdown">

                    <button class="btn btn-success dropdown-toggle ml-0 mr-2"
                            aria-expanded="false"
                            aria-haspopup="true"
                            data-toggle="dropdown"
                            type="button">
                        {{  $t('action') }}
                    </button>

                    <div class="dropdown-menu">
                        <a class="dropdown-item d-flex align-items-center p-3" href="#"  @click="goToPage('stocks/import')">
                            <app-icon class="size-15 mr-2" name="download"/>
                            {{ $t('import_warehouse_stock') }}
                        </a>
                        <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="">
                            <app-icon class="size-15 mr-2" name="upload"/>
                            {{ $t('export_all_data') }}
                        </a>
                        <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="">
                            <app-icon class="size-15 mr-2" name="upload"/>
                            {{ $t('export_filtered_data') }}
                        </a>
                        <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="goToPage('print/barcode')">
                            <app-icon class="size-15 mr-2" name="printer"/>
                            {{ $t('print_bar_code') }}
                        </a>
                        <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="goToPage('stocks/adjustment')">
                            <app-icon class="size-15 mr-2" name="tool"/>
                            {{ $t('stock_adjustment') }}
                        </a>
                    </div>

                </div>
                <button
                    v-if="$can('create_warehouse_stock')"
                    type="button"
                    class="btn btn-primary btn-with-shadow"
                    @click="openModal()">
                    <app-icon name="plus" class="size-20 mr-2"/>
                    {{ $addLabel('warehouse_stock') }}
                </button>
            </div>



        </app-page-top-section>

        <app-table
            id="stock-table"
            :options="options"
            @action="triggerActions"
        />


        <!-- Stock Add/Edit Modal -->
        <app-stock-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />
    </div>
</template>

<script>
import StockTableMixin from "../../../Mixins/Stock/StockTableMixin";
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import {STOCK} from "../../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: "Stock",
    mixins: [HelperMixin, StockTableMixin],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${STOCK}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        goToPage(url){
            window.location = urlGenerator(url);
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
    }
}
</script>