<template>
    <app-modal modal-id="cash-register-notifier-modal"
               :title="$t('select_cash_register')"
               modal-alignment="top"
               modal-size="large"
               :modal-backdrop="false"
               v-model="showModal"
               @close-modal="closeModal">

        <!-- select cash register -->
        <template #header>
            <div class="header">
                <h4 class="p-0 m-0">{{ $t('resume') }}</h4>
            </div>
        </template>

        <template #body>
            <p class="text-info mb-5">
                <app-icon name="alert-circle" />
                <span class="ml-2">{{ $t('you_already_have_a_counter_opened') }}</span>
            </p>
            <div class="row">
                <p class="col-md-4"> {{ $t('counter_name') }} </p>
                <p class="col-md-8">{{ openedCashRegisterDetails?.name }}</p>

                <p class="col-md-4"> {{ openedCashRegisterDetails?.branch_or_warehouse?.type === 'branch' ? $t('branch') : $t('warehouse')  }} </p>
                <p class="col-md-8">{{ openedCashRegisterDetails?.branch_or_warehouse?.name }}</p>
            </div>
        </template>

        <template #footer>
            <button class="btn btn-secondary mr-3" @click="handleBackBtnClick"> {{ $t('back_to_dashboard') }} </button>
            <button class="btn btn-primary" @click="handleProceed"> 
                {{ $t('proceed') }}
            </button>
        </template>
    </app-modal>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import FormHelperMixins from '../../../../common/Mixin/Global/FormHelperMixins';
import {urlGenerator} from "../../../Helper/Helper";

export default {
    name: "CashRegisterNotifierModal",
    mixins: [FormHelperMixins],
    props: ['openedCashRegisterDetails'],
    data() {
        return {
            showModal: '',
        }
    },
    methods: {
        ...mapActions(['setBranchOrWarehouseId', 'setBranchOrWarehouseName', 'setCashRegisterId']),
        handleProceed() {
            if (!this.openedCashRegisterIsInCurrentBranch) {
                this.setBranchOrWarehouseId(this.openedCashRegisterDetails?.branch_or_warehouse_id)
                this.setBranchOrWarehouseName(this.openedCashRegisterDetails?.branch_or_warehouse?.name)

                localStorage.setItem("currentBranchWahrehouseId", this.formData.branch_or_warehouse_id);
                localStorage.setItem("currentBranchWahrehouseName", this.openedCashRegisterDetails?.branch_or_warehouse?.name);
            }
            this.setCashRegisterId(this.openedCashRegisterDetails?.id)
            this.closeModal()
        },
        closeModal() {
            $("#cash-register-notifier-modal").modal("hide");
            this.$emit("close");
        },
        handleBackBtnClick() {
            window.location.replace(urlGenerator('/dashboard'));
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
        openedCashRegisterIsInCurrentBranch() {
            return this.openedCashRegisterDetails?.branch_or_warehouse_id === this.getBranchOrWarehouseId
        }
    }
}
</script>
