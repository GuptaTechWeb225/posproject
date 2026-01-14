<template>
    <app-modal modal-id="cash-register-select-modal"
               :title="$t('select_cash_register')"
               modal-alignment="top"
               modal-size="large"
               :modal-backdrop="false"
               v-model="showModal"
               @close-modal="closeModal">

        <!-- select cash register -->
        <template #header>
            <div class="header col-12">
                <h4 class="p-0 m-0">{{ $t('open_a_cash_register') }}</h4>
            </div>
        </template>

        <template #body>
            <app-pre-loader v-if="preloader"/>
            <template v-else>
                <template v-if="isMailSettingExist">
                    <div class="opening-balance-input row align-items-baseline mb-5" v-if="!switchingRegisters">
                        <label class="col-md-4"> {{ $t('select_branch') }} </label>
                        <template v-if="formData.branch_or_warehouse_id === null">
                            <app-input
                                class="w-50 col-md-7 px-0"
                                type="search-and-select"
                                :placeholder="$placeholder('branch')"
                                :inputclearable="false"
                                :key="branchOrWarehouseInputKey"
                                :options="branchOptions"
                                v-model="formData.branch_or_warehouse_id"
                            />
                        </template>
                        <template v-else>
                            <app-input
                                class="w-50 col-md-7 px-0"
                                type="search-and-select"
                                :placeholder="$placeholder('branch')"
                                :inputclearable="false"
                                :key="branchOrWarehouseInputKey"
                                :options="branchOptions"
                                v-if="formData.branch_or_warehouse_id"
                                v-model="formData.branch_or_warehouse_id"
                            />
                        </template>
                    </div>
                    <div class="cash-register-input row align-items-baseline mb-5"
                         v-if="formData.branch_or_warehouse_id">
                        <label class="col-md-4"> {{ $t('cash_register') }} </label>
                        <app-input
                            type="cash-register-search-and-select"
                            class="w-50 col-md-7 px-0"
                            :placeholder="$t('select_cash_register')"
                            :options="cashRegisterOptions"
                            :key="cashRegisterInputKey"
                            :showCloseCounterBtn="false"
                            v-model="formData.id"
                            :error-message="$errorMessage(errors, 'id')"
                        />
                        <div class="col-md-1"></div>
                    </div>

                    <div class="opening-balance-input row align-items-baseline mb-5">
                        <label class="col-md-4"> {{ $t('opening_balance') }} </label>
                        <app-input
                            class="col-md-7 px-0"
                            type="number"
                            :placeholder="$placeholder('opening_balance')"
                            v-model="formData.opening_balance"
                            :error-message="$errorMessage(errors, 'opening_balance')"
                        />
                        <div class="col-md-1"></div>
                    </div>
                </template>
                <app-note v-else
                          :title="$t('no_delivery_settings_found')"
                          :notes="[$t('pos_menu_no_email_setting_warning', {
                      location: `<a href='${settingsLocation}'>${$t('here')}</a>`
                  })]"
                          content-type="html"
                />
            </template>
        </template>

        <template #footer>
            <template v-if="isMailSettingExist">
                <button class="btn btn-secondary mr-3" @click="handleBackBtnClick"> {{
                        $t('back_to_dashboard')
                    }}
                </button>
                <button
                    :class="`btn btn-primary ${+formData.id && +formData.opening_balance >= 0 ? '' : 'custom-disable'}`"
                    @click="handleCashRegisterOpenBtnClick"> {{ $t('open_register') }}
                </button>
            </template>
            <button type="button"
                    @click="handleBackBtnClick"
                    v-else
                    class="btn btn-secondary"
                    data-dismiss="modal">
                {{ $t('back_to_dashboard') }}
            </button>
        </template>
    </app-modal>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {
    CASH_COUNTER_OPEN_CLOSE,
    SELECTABLE_CASH_REGISTERS
} from "../../../Config/ApiUrl-CP";
import {axiosGet, axiosPost} from "../../../../common/Helper/AxiosHelper";
import {mapGetters, mapActions} from "vuex";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {BRANCHES_OR_WAREHOUSES, SELECTABLE_BRANCHES} from "../../../Config/ApiUrl-CPB";
import {
    MAIL_CHECK_URL,
    TENANT_EMAIL_SETUP_SETTING,
    TENANT_MAIL_CHECK_URL
} from "../../../../common/Config/apiUrl";
import { formatDateTimeForServer } from "../../../../tenant/Helper/DateTimeHelper.js";

export default {
    name: "CashRegisterSelectModal",
    mixins: [FormHelperMixins],
    props: {
        alias: {
            type: String,
            default: 'tenant'
        }
    },
    data() {
        return {
            showModal: '',
            preloader: true,
            cashRegisters: null,
            branchOrWarehouseInputKey: 1,
            showBranchOrWarehouseSelectorModal: '',
            isMailSettingExist: false,
            cashRegisterInputKey: 0,
            branches: [],
            branchOptions: {
                url: urlGenerator(SELECTABLE_BRANCHES),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
            },
            formData: {
                opening_balance: '',
                closing_balance: 0,
                branch_or_warehouse_id: '',
                id: '',
            },
            settingsLocation: urlGenerator(TENANT_EMAIL_SETUP_SETTING)
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId', 'getNewCashRegisterId']),
        cashRegisterOptions() {
            if (!this.formData.branch_or_warehouse_id) return null;
            return {
                url: urlGenerator(SELECTABLE_CASH_REGISTERS),
                query_name: "search",
                per_page: 10,
                params: {
                    branch_or_warehouse_id: this.formData.branch_or_warehouse_id
                },
                loader: "app-pre-loader",
                modifire: ({id, name: value, status, opened_by}) => ({id, value, status, opened_by}),
                prefetch: false
            }
        },
        switchingRegisters() {
            return Boolean(this.getNewCashRegisterId);
        }
    },
    created() {
        this.checkMailSettings();
    },
    mounted() {
        this.getBranchOrWarehouseNames();
        this.getCashRegisters();
        this.formData.id = this.getNewCashRegisterId;
        this.cashRegisterInputKey++;
    },
    watch: {
        'formData.branch_or_warehouse_id'() {
            if (this.getNewCashRegisterId) return;
            this.formData.id = '';
            this.cashRegisterInputKey++;
        },
        getBranchOrWarehouseId: {
            immediate: true,
            handler(newId) {
                this.formData.branch_or_warehouse_id = eval(newId);
                this.branchOrWarehouseInputKey++;
            }
        }
    },
    methods: {
        ...mapActions(['setCashRegisterId', 'setBranchOrWarehouseId', 'setBranchOrWarehouseName', 'setProducts']),
        checkMailSettings() {
            this.preloader = true;
            const url = this.alias === 'tenant' ? TENANT_MAIL_CHECK_URL : MAIL_CHECK_URL
            axiosGet(url).then(response => {
                this.isMailSettingExist = !!response.data;
            }).finally(() => {
                this.preloader = false;
            });
        },
        getBranchOrWarehouseNames() {
            axiosGet(BRANCHES_OR_WAREHOUSES).then(response => {
                this.branches = response.data.data;
            }).catch(e => {
                this.$toastr.e(e);
            })
        },
        handleBranchOrWarehouseSelect() {
            this.setBranchOrWarehouseId(this.formData.branch_or_warehouse_id);
            const {name: selectedBranchName} = this.branches.find(branch => branch.id === this.formData.branch_or_warehouse_id)
            this.setBranchOrWarehouseName(selectedBranchName);

            localStorage.setItem("currentBranchWahrehouseId", this.formData.branch_or_warehouse_id);
            localStorage.setItem("currentBranchWahrehouseName", selectedBranchName);

            this.setProducts();
            this.closeModal();
        },
        async handleCashRegisterOpenBtnClick() {
            axiosPost(CASH_COUNTER_OPEN_CLOSE, {...this.formData, action: 'open', opening_time: formatDateTimeForServer(new Date()) })
                .then(res => {
                    this.$toastr.s(res.data.message);
                    this.setCashRegisterId(this.formData.id);
                    this.handleBranchOrWarehouseSelect();

                    if (this.switchingRegisters) window.location.reload();
                })
                .catch(e => {
                    this.$toastr.e(e.response.data.message)
                })
        },
        async getCashRegisters() {
            const {data: {data: cashRegisterData}} = await axiosGet(SELECTABLE_CASH_REGISTERS);
            this.cashRegisters = [...cashRegisterData.map(crd => ({...crd, dropdown_active: false}))];
        },
        closeModal() {
            $("#cash-register-select-modal").modal("hide");
            this.$emit("close");
        },
        handleBackBtnClick() {
            window.location.replace(urlGenerator('/dashboard'));
        }
    },
}
</script>

<style lang="sass">
.product-details-btn
    width: 2rem
    height: 2rem
    border: 1px solid
    border-radius: 50%
    transition: 250ms

    &:hover
        color: #3498db
        border-color: #3498db

.right-90
    transform: rotate(90deg)

.custom-disable
    opacity: 0.5
    pointer-events: none

</style>
