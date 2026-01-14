<template>
    <div>

        <app-table
            id="invoice-template-table"
            :options="options"
            @action="triggerActions"
        />

        <app-invoice-template-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"

        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('invoice-template-table')"
            @cancelled="cancelled"
        />

    </div>
</template>

<script>
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import InvoiceTemplateMixin from "../../Mixins/InvoiceTemplateMixin";
import {INVOICE_TEMPLATE} from "../../../Config/ApiUrl-CPB";
import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
export default {
    name: "InvoiceTemplate",

    mixins: [HelperMixin, InvoiceTemplateMixin, DatatableHelperMixin],
    props:['id'],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
        }
    },

    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, (component) => {
            this.openModal()
        })

    },
    methods: {
        triggerActions(row, action, active) {

            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${INVOICE_TEMPLATE}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
    },
    beforeDestroy() {
        this.$hub.$off('headerButtonClicked-' + this.id);
    }
}
</script>
