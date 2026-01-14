<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('customers')" icon="briefcase">

            <app-default-button
                @click="openModal()"
                v-if="this.$can('create_customers')"
                :title="$fieldTitle('add', 'customer', true)"/>


        </app-page-top-section>

        <app-table
            id="customer-table"
            :options="options"
            @action="triggerActions"
        />

        <app-customer-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @afterSubmitSuccess="filterInitiate"
            @close="isModalActive = false"

        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('customer-table')"
            @cancelled="cancelled"
        />
    </div>

</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CustomerMixin from "../../Mixins/CustomerMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {CUSTOMERS, CUSTOMERS_EXPORT} from "../../../Config/ApiUrl-CPB";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";

export default {
    name: "Customers",
    mixins: [HelperMixin, CustomerMixin],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
            totalCustomer: '',
            urlGenerator,
        }
    },
    extends: CoreLibrary,
    methods: {
        triggerActions(row, action, active) {

            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${CUSTOMERS}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },

        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },

        exportCustomers() {
            window.location = CUSTOMERS_EXPORT;
        },
    }
}
</script>
