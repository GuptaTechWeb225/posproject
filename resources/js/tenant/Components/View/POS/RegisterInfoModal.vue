<template>
    <app-modal modal-id="register-info-modal"
               :title="$t('register_details')"
               modal-alignment="top"
               v-model="showModal"
               modal-size="large"
               @close-modal="handlerRegisterInfoModalClose">

        <template #header>
            <div class="header col-12">
                <h4 class="text-center">{{ $t('register_details') }}</h4>
            </div>
        </template>

        <template #body>
            <template v-if="registerInfo">
                <div class="counter-details mb-5 pb-2 border-bottom row no-gutters">
                    <div class="col-md-6">
                        <div class="row col-12 no-gutters justify-content-between">
                            <p class="label text-muted col-6 m-0">{{ $t('counter_name') }}</p>
                            <p class="value col-6 mb-0">{{ registerInfo.cashRegister.name }}</p>
                        </div>

                        <div class="row col-12 no-gutters justify-content-between">
                            <p class="label text-muted col-6 mb-0">{{ $t('opened_at') }}</p>
                            <p class="value col-6 mb-0">{{ openingTime }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row col-12 no-gutters justify-content-between">
                            <p class="label text-muted col-6 mb-0">{{ $t('date') }}</p>
                            <p class="value col-6 mb-0">{{ formatDateToLocal(date) }}</p>
                        </div>

                        <div class="row col-12 no-gutters justify-content-between">
                            <p class="label text-muted col-6 mb-0">{{ $t('closed_at') }}</p>
                            <p class="value col-6 mb-0">{{ registerInfo.cashRegister.closing_time || '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="cash-details row col-12 no-gutters">
                    <div class="d-flex col-12 justify-content-between" >
                        <p class="label text-muted mb-0">{{ $t('opening_balance') }}:</p>
                        <p class="value mb-0"> {{ numberWithCurrencySymbol(registerInfo.cashRegister.opening_balance) }}</p>
                    </div>
                    <div class="d-flex col-12 justify-content-between border-bottom">
                        <p class="label text-muted mb-0">{{ $t('total_sales') }}:</p>
                        <p class="value mb-0"> {{ numberWithCurrencySymbol(totalSales) }}</p>
                    </div>
                    <div class="d-flex col-12 justify-content-between mb-3" style="border-bottom: 2px solid #777777">
                        <p class="label text-muted mb-0">{{ $t('credit_sales') }}:</p>
                        <p class="value mb-0 text-danger">- {{ numberWithCurrencySymbol(creditSales) }}</p>
                    </div>
                    <div class="d-flex col-12 justify-content-between 2">
                        <h4 class="label text-muted" style="text-transform: uppercase">{{ $t('total_cash') }}</h4>
                        <h4 class="value mb-0"> {{ numberWithCurrencySymbol(totalCash) }}</h4>
                    </div>
                </div>
            </template>
            <app-overlay-loader v-else />
        </template>

        <template #footer>
            <button class="btn btn-secondary" @click="handlerRegisterInfoModalClose">{{ $t('close') }}</button>
        </template>

    </app-modal>
</template>

<script>
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {axiosGet} from "../../../../common/Helper/AxiosHelper";
import moment from "moment";
import {CASH_REGISTER_INFO} from "../../../Config/ApiUrl-CP";
import {mapGetters} from 'vuex';
import {numberWithCurrencySymbol} from "../../../Helper/Helper";
import {formatDateToLocal} from "../../../Helper/Helper";

export default {
    name: "register-info-modal",
    mixins: [ModalMixin, HelperMixin, FormHelperMixins],
    data() {
        return {
            registerInfo: null,
            numberWithCurrencySymbol,
            formatDateToLocal
        }
    },
    props: ['cashRegisterId'],
    mounted() {
        this.fetchRegisterData();
    },
    methods: {
        handlerRegisterInfoModalClose() {
            $('#register-info-modal').modal('hide');
            this.$emit('close');
        },
        async fetchRegisterData() {
            const {data} = await axiosGet(CASH_REGISTER_INFO + (this.cashRegisterId ? this.cashRegisterId : this.getCashRegisterId));
            this.registerInfo = {...data};
        }
    },
    computed: {
        ...mapGetters([
            'getCashRegisterId',
        ]),
        date() {
            if (!this.registerInfo) return;
            return moment(this.registerInfo.cashRegister.opening_time.split(' ')[0]);
        },
        openingTime() {
            if (!this.registerInfo) return;
            return this.registerInfo.cashRegister.opening_time.split(' ')[1];
        },
        totalSales() {
            // if (!this.registerInfo) return 0;
            // const {cashRegisterLogs} = this.registerInfo;
            // const cashLog = cashRegisterLogs.find(log => log.payment_method.alias === 'cash');
            // if (cashLog) return cashLog.total_sale_amount;
            // return 0;
            if (!this.registerInfo) return 0;
    
                const { cashRegisterLogs } = this.registerInfo;

                if (!cashRegisterLogs || cashRegisterLogs.length === 0) return 0;

                // Filter cash payment logs
                const cashLogs = cashRegisterLogs.filter(log => log.payment_method.alias === 'cash');

                // Sum up total sale amounts for cash payment logs
                const totalCashSales = cashLogs.reduce((total, log) => total + log.total_sale_amount, 0);

                return totalCashSales;
        },
        creditSales() {
            if (!this.registerInfo) return 0;
            const {cashRegisterLogs} = this.registerInfo;
            const creditLog = cashRegisterLogs.find(log => log.payment_method.alias === 'credit');
            if (creditLog) return creditLog.total_sale_amount;
            return 0;
        },
        totalCash() {
            if (!this.registerInfo) return 0;
            const openingBalance = this.registerInfo?.cashRegister?.opening_balance ?? 0
            return (+this.totalSales - +this.creditSales) + openingBalance;
        }
    },

}
</script>

<style scoped lang="sass">
.cash-details > div
    padding: 0.5rem 0
</style>

