
<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('internal_transfer')"/>
            <button
                v-if="this.$can('create_internal_transfers')"
                type="button"
                class="btn btn-primary btn-with-shadow mb-4"
                @click="openModal()">
                {{ $addLabel('transfer') }}
            </button>
        </div>

        <app-table
            :id="table_id"
            v-if="options.url"
            :options="options"
            @action="triggerActions"
        />

        <app-internal-transfer-create-edit-modal
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
            @confirmed="confirmed('internal-transfer-table')"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>

import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import {INTERNAL_TRANSFER} from "../../../Config/ApiUrl-CP";
import InternalTransferMixin from "../../Mixins/Stock/InternalTransferMiixn";

export default {
    name: "InternalTransfer",
    mixins: [DatatableHelperMixin, HelperMixin, InternalTransferMixin],
    data() {
        return {
            table_id: 'internal-transfer-table',
            isModalActive: false,
            selectedUrl: '',
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${INTERNAL_TRANSFER}/${row.id}`;
                this.isModalActive = true;
            } else if (action.name === 'delete') {
                this.delete_url = INTERNAL_TRANSFER + '/' + row.id;
                this.confirmationModalActive = true;
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
