<template>
    <app-modal modal-id="cash-register-close-modal"
               :title="$t('cash-register-close')"
               modal-alignment="center"
               v-model="showModal"
               modal-size="default"
               :modal-backdrop="false"
               :preloader="preloader"
               @close-modal="closeModal">

        <template #header>
            <div class="header text-center col-12">
                <h4 class="text-center">{{ $t('close_cash_register') }}</h4>
            </div>
        </template>

        <template #body>
            <div class="body row no-gutters">
                <div class="form-element col-12 row no-gutters my-3">
                    <label class="col-md-12">{{ $t('closing_balance') }}</label>
                    <app-input
                        class="col-md-12"
                        type="number"
                        :placeholder="$placeholder('closing_balance')"
                        v-model="formData.closing_balance"
                        :error-message="$errorMessage(errors, 'closing_balance')"
                    />
                </div>

                <div class="form-element col-12 row no-gutters">
                    <label class="col-md-12">{{ $t('note') }}</label>
                    <app-input
                        class="col-md-12"
                        type="textarea"
                        :placeholder="$placeholder('note')"
                        v-model="formData.note"
                        :required="true"
                        :error-message="$errorMessage(errors, 'note')"
                    />
                </div>
            </div>
        </template>

        <template #footer>
            <div class="footer col-12 row justify-content-center">
                <button :class="`btn btn-secondary col-5 mr-5 `" @click="handleCloseRegisterCancel"> {{ $t('cancel') }} </button>
                <button :class="`btn btn-primary col-5 ${!formData.closing_balance && 'custom-disable'}`" @click="submitData"> {{ $t('close_register') }} </button>
            </div>
        </template>

    </app-modal>
</template>

<script>
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {axiosPost} from "../../../../common/Helper/AxiosHelper";
import {CASH_COUNTER_OPEN_CLOSE} from "../../../Config/ApiUrl-CP";
import {mapGetters} from "vuex";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import { formatDateTimeForServer } from "../../../../tenant/Helper/DateTimeHelper.js";

export default {
    name: "cash-register-close",
    mixins: [ModalMixin, HelperMixin, FormHelperMixins],
    props: ["cashRegisterToCloseId", "switchingRegisters", "cashRegisterBranchWarehouseId"],
    mounted() {
        this.formData.branch_or_warehouse_id = this.cashRegisterBranchWarehouseId ? this.cashRegisterBranchWarehouseId : this.getBranchOrWarehouseId;
        this.formData.id = this.cashRegisterToCloseId;
    },
    data() {
        return {
            formData: {
                opening_balance: 0,
                closing_balance: '',
                branch_or_warehouse_id: '',
                note: '',
                id: '',
            }
        }
    },
    computed: {
     ...mapGetters(['getBranchOrWarehouseId', 'getNewCashRegisterId']),
    },
    methods: {
        handleCloseRegisterCancel() {
            if (this.switchingRegisters) this.$emit('cash-register-switch-cancel');
            this.closeModal();
        },
        closeModal(triggerSelectorModal = false) {
            $('#cash-register-close-modal').modal('hide');
            this.$emit('close', this.cashRegisterToCloseId);
            if (triggerSelectorModal) this.$hub.$emit('open_cash_register_selector_modal');
        },
        submitData() {
            axiosPost(CASH_COUNTER_OPEN_CLOSE, {...this.formData, action: 'close', closing_time: formatDateTimeForServer(new Date())})
                .then(res => {
                    // this.$toastr.s(res.data.message);
                    this.toastAndReload(res.data.message, 'counter-table')
                    localStorage.removeItem('cash_register_id');
                    localStorage.removeItem('cartState');
                    if (!this.switchingRegisters) return window.location.replace(urlGenerator('/dashboard'));
                    this.closeModal(true);
                }).catch(e => {
                    this.$toastr.e(e.response.data.message)
                    if(this.$t('cannot_close_closed_counter') == e.response.data.message) {
                        localStorage.removeItem('cash_register_id');
                        localStorage.removeItem('cartState');
                        window.location.replace(urlGenerator('/dashboard'));
                    }
                })
        }
    },
}
</script>

<style scoped lang="sass">
.custom-disable
    opacity: 0.5
    pointer-events: none
</style>
