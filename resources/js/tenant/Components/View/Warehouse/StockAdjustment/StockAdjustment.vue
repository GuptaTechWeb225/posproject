<template>
    <div class="content-wrapper">
        <div v-if="this.$can('create_stock_adjustments')" class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('stock_adjustment')"/>
            <button
                type="button"
                class="btn btn-primary btn-with-shadow mb-4"
                @click="openModal()">
                {{ $t('add_adjustment') }}
            </button>
        </div>

        <app-table
            :id="table_id"
            :v-if="options.url"
            :options="options"
            @action="triggerActions"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="trash-2"
            :message="deleteMessage"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('stock-adjustment-table')"
            @cancelled="cancelled"
        />

        <app-stock-adjustment-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />
    </div>
</template>

<script>

import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import {DELETE_STOCK_ADJUSTMENT, STOCK_ADJUSTMENTS} from "../../../../Config/ApiUrl-CP";
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import StockAdjustmentMixin from "../../../Mixins/Stock/StockAdjustmentMixin";

export default {
    name: "StockAdjustment",
    mixins: [DatatableHelperMixin, HelperMixin, StockAdjustmentMixin],
    data() {
        return {
            table_id: 'stock-adjustment-table',
            isModalActive: false,
            confirmationModalActive: false,
            selectedUrl: '',
            deleteMessage: '',
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${STOCK_ADJUSTMENTS}/${row.id}`;
                this.isModalActive = true;
            } else if (action.title === this.$t('delete')) {
                this.confirmationModalActive = true;
                this.deleteMessage = 'This deletion will change the current stock!'
                this.delete_url = DELETE_STOCK_ADJUSTMENT + row.id;
            } else {
                this.getAction(row, action, active)
            }
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
    }
}
</script>