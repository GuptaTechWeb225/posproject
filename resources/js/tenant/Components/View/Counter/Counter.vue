<template>
    <div class="content-wrapper">

      <app-page-top-section :title="$t('counter')" icon="briefcase">
        <app-default-button
            v-if="this.$can('view_counter')"
            @click="openModal()"
            :title="$fieldTitle('add', 'counter', true)"/>
      </app-page-top-section>

        <app-table
            id="counter-table"
            :options="options"
            @action="triggerActions"
        />

        <app-counter-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"

        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('counter-table')"
            @cancelled="cancelled"
        />

        <cash-register-close-modal
            v-if="cashRegisterCloseModalOpen"
            v-model="cashRegisterCloseModalOpen"
            :cashRegisterToCloseId="cashRegisterToCloseId"
            :cashRegisterBranchWarehouseId="cashRegisterBranchWarehouseId"
            :switching-registers="true"
            @close="handleCashRegisterModalClose"
        />

        <register-info-modal
            v-if="registerModalOpen"
            v-model="registerModalOpen"
            :cashRegisterId="cashRegisterToShowTheInfoOfId"
            @close="handlerRegisterInfoModalClose"
        />
    </div>

</template>

<script>
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CounterMixin from "../../Mixins/CounterMixin";
import {COUNTERS} from "../../../Config/ApiUrl-CPB";
import CounterAddEditModal from "./CounterAddEditModal";

// import CashRegisterCloseModal from "resources/js/tenant/Components/View/POS/CashRegisterCloseModal.vue";
import CashRegisterCloseModal from "../POS/CashRegisterCloseModal.vue";
import RegisterInfoModal from "../POS/RegisterInfoModal.vue";

export default {
    name: "Counter",
    components: {CounterAddEditModal, 'cash-register-close-modal': CashRegisterCloseModal, 'register-info-modal': RegisterInfoModal},
    mixins: [HelperMixin, CounterMixin],
    props: ['id'],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
            cashRegisterToCloseId: '',
            cashRegisterBranchWarehouseId: '',
            cashRegisterToShowTheInfoOfId: '',
            cashRegisterCloseModalOpen: false,
            registerModalOpen: false,
        }
    },
    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, (component) => {
            this.openModal();
        })
    },
    methods: {
        handlerRegisterInfoModalClose() {
            this.cashRegisterToShowTheInfoOfId = ''
            this.registerModalOpen = false
        },
        triggerActions(row, action, active) {
            // if (action.title === this.$t('edit')) {
            //     this.selectedUrl = `${COUNTERS}/${row.id}`;
            //     this.isModalActive = true;
            // } else {
            //     this.getAction(row, action, active)
            // }
            switch (action.title) {
                case this.$t('edit'):
                    this.selectedUrl = `${COUNTERS}/${row.id}`;
                    this.isModalActive = true;
                    break;
                case this.$t('delete'):
                    this.getAction(row, action, active)
                    break;
                case this.$t('close'):
                    this.cashRegisterToCloseId = row.id
                    this.cashRegisterBranchWarehouseId = row.branch_or_warehouse_id
                    this.cashRegisterCloseModalOpen = true
                    break;
                case this.$t('info'):
                    this.cashRegisterToShowTheInfoOfId = row.id;
                    this.registerModalOpen = true
                    break;

                default:
                    break;
            }
        },
        handleCashRegisterModalClose() {
            this.cashRegisterCloseModalOpen = false;
            this.cashRegisterBranchWarehouseId = ''
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
