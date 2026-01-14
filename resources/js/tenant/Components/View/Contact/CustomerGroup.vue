<template>
    <div class="content-wrapper">

        <app-page-top-section :title="ucWords($t('customer_groups'))" icon="briefcase">
            <app-default-button
                @click="openModal()"
                v-if="this.$can('create_customer_groups')"
                :title="$fieldTitle('Add', ucWords($t('group')), true)"/>
        </app-page-top-section>

        <app-table
            id="customer-group-table"
            :options="options"
            @action="triggerActions"
        />

        <app-customer-group-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('customer-group-table')"
            @cancelled="cancelled"
        />

    </div>
</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import {CUSTOMER_GROUP} from "../../../Config/ApiUrl-CPB";
import {ucWords} from "../../../../common/Helper/Support/TextHelper";
import CustomerGroupMixin from "../../Mixins/CustomerGroupMixin";

export default {
    name: "CustomerGroup",
    mixins: [HelperMixin, CustomerGroupMixin],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
            ucWords,
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${CUSTOMER_GROUP}/${row.id}`;
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
