<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('receive_product')"/>
            <button
                type="button"
                class="btn btn-primary btn-with-shadow mb-4"
                @click="openModal()">
                <app-icon name="plus" class="size-20 mr-2"/>
                {{ $addLabel('receive_product') }}
            </button>
        </div>

        <app-table
            id="lot-table"
            :options="options"
            @action="triggerActions"
        />

        <app-lot-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />
        <app-confirmation-modal
            v-if="confirmationModalActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('Lot-table')"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>

import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import LotTableMixin from "../../Mixins/LotTableMixin";
import {LOT} from "../../../Config/ApiUrl-CP";

export default {
    name: "ReceiveProduct",
    mixins: [DatatableHelperMixin, HelperMixin, LotTableMixin],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${LOT}/${row.id}`;
                this.isModalActive = true;
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