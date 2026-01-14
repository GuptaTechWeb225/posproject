<template>
    <div class="page-top-section d-flex justify-content-between mb-3 flex-wrap">
        <div class="d-flex register-input-wrapper justify-content-start" v-if="cash_register_id">
            <!--showing the div when cash_register_id is set from the state from the beforeMount hook-->
            <app-input
                v-if="cashRegisterOptions"
                type="cash-register-search-and-select"
                class="mr-2 w-50 cash-register-input"
                :placeholder="$t('cash_register')"
                :options="cashRegisterOptions"
                :inputclearable="false"
                @input="handleDropdownItemSelect"
                @dropdown-item-select="handleDropdownItemSelect"
                @cash-counter-close="handleCashCounterCloseEvent"
                v-model="cash_register_id"
                :error-message="$errorMessage(errors, 'cash_register')"
            />
            <app-input
                v-else
                class="mr-2 w-50"
                v-model="oldId"
                type="text"
                :placeholder="$t('cash_register')"
            />
            <button class="btn btn-primary rounded" @click="registerModalOpen = true">
                <app-icon name="alert-circle"/>
            </button>
        </div>

        <div class="d-flex">
            <button class="btn btn-success mr-2" :title="$t('view_item_stock')" @click="handleViewStockButtonClick">
                <app-icon name="package"/>
            </button>
            <!-- return button -->
            <button class="btn btn-info mr-2" :title="$t('open_return_modal')" @click="handleReturnModalBtnClick">
                <app-icon name="corner-left-up"/>
            </button>
            <button
                class="btn btn-warning mr-2 hold-modal-btn"
                @click="handleHoldModalBtnClick"
                :title="$t('open_hold_modal')"
            >
                <app-icon name="pause"/>
                <span class="hold-order-n" v-if="numberOfHeldOrders">
                    <span>{{ numberOfHeldOrders }}</span>
                </span>
            </button>
            <button class="btn btn-primary d-none d-md-inline-block" @click="handleMenuBtnClick" :key="viewMode"
                    :title="$t('toggle_barcode_view')">
                <app-icon :name="viewMode === 'primary' ? 'trello' : 'menu'"/>
            </button>
        </div>

        <register-info-modal
            v-if="registerModalOpen"
            v-model="registerModalOpen"
            @close="registerModalOpen = false"
        />

        <app-return-add-edit-modal
            v-if="returnModalActive"
            v-model="returnModalActive"
            @close="returnModalActive = false"
        />

        <cash-register-close-modal
            v-if="cashRegisterCloseModalOpen"
            v-model="cashRegisterCloseModalOpen"
            :cash-register-to-close-id="getCashRegisterId"
            @cash-register-switch-cancel="handleCashRegisterCancel"
            :switching-registers="switchingRegisters"
            @close="handleCashRegisterModalClose"
        />

        <order-hold-modal
            v-if="orderHoldModalOpen"
            v-model="orderHoldModalOpen"
            @close="orderHoldModalOpen = false"
        />

        <stock-view-modal
            v-if="viewStockModalOpen"
            v-model="viewStockModalOpen"
            @close="viewStockModalOpen = false"
        />

    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {mapGetters, mapMutations, mapActions} from "vuex";

import RegisterInfoModal from "./RegisterInfoModal";
import CashRegisterCloseModal from "./CashRegisterCloseModal";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {SELECTABLE_CASH_REGISTERS} from "../../../Config/ApiUrl-CP";
import OrderHoldModal from "./OrderHoldModal";
import StockViewModal from "./StockViewModal";

export default {
    mixins: [FormHelperMixins],
    components: {
        'cash-register-close-modal': CashRegisterCloseModal,
        'register-info-modal': RegisterInfoModal,
        'order-hold-modal': OrderHoldModal,
        'stock-view-modal': StockViewModal
    },
    name: 'PosViewTopSection',
    data() {
        return {
            registerModalOpen: false,
            returnModalActive: false,
            viewStockModalOpen: false,
            cashRegisterCloseModalOpen: false,
            orderHoldModalOpen: false,
            cash_register_id: '',
            oldId: '',
            switchingRegisters: false,
            cashRegisterInputEventFiredOnce: false
        }
    },
    computed: {
        ...mapGetters(['viewMode', 'numberOfHeldOrders', 'getCashRegisterId', 'getNewCashRegisterId', 'getBranchOrWarehouseId']),
        cashRegisterOptions() {
            if (!this.getBranchOrWarehouseId) return null
            return {
                url: urlGenerator(SELECTABLE_CASH_REGISTERS + '?branch_or_warehouse_id=' + this.getBranchOrWarehouseId),
                query_name: "search",
                per_page: 10,
                loader: "app-pre-loader",
                modifire: ({id, name: value, status, opened_by}) => ({id, value, status, opened_by}),
                prefetch: false
            }
        },
    },
    watch: {
        getCashRegisterId() {
            this.setCashRegisterIdFromState();
        },
    },
    methods: {
        ...mapMutations(['CHANGE_VIEW_MODE', 'SET_NEW_CASH_REGISTER_ID']),
        ...mapActions(['addItemToCart']),
        handleViewStockButtonClick() {
            this.viewStockModalOpen = true;
        },
        handleDropdownItemSelect(newId) {
            if (!this.cashRegisterInputEventFiredOnce) {
                this.cashRegisterInputEventFiredOnce = true;
                return;
            }
            if (+newId === +this.getCashRegisterId) return;
            this.switchingRegisters = true;
            this.SET_NEW_CASH_REGISTER_ID(newId);
            this.cashRegisterCloseModalOpen = true;
        },
        handleCashRegisterCancel() {
            window.location.reload(); // temporary solution
        },
        handleCashRegisterModalClose(cancelledCashRegisterCloseId) {
            this.cash_register_id = cancelledCashRegisterCloseId;
            this.cashRegisterCloseModalOpen = false;
        },
        handleHoldModalBtnClick() {
            this.orderHoldModalOpen = true;
        },
        handleReturnModalBtnClick() {
            this.returnModalActive = true;
        },
        handleCashCounterCloseEvent() {
            this.switchingRegisters = false;
            this.cashRegisterCloseModalOpen = true;
        },
        handleMenuBtnClick() {
            this.CHANGE_VIEW_MODE(this.viewMode === 'primary' ? 'secondary' : 'primary')
        },
        setCashRegisterIdFromState() {
            this.cash_register_id = this.getCashRegisterId;
        }
    },
    beforeMount() {
        this.setCashRegisterIdFromState()
    }
}
</script>

<style lang="scss" scoped>
.hold-modal-btn { 
    position: relative
}

.hold-order-n {
    $dimension: 1.5rem;
    position: absolute;
    width: $dimension;
    height: $dimension;
    display: grid;
    place-items: center;
    color: white;
    background: red;
    border-radius: 50%;
    top: -10px;
    right: -10px;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
}

.register-input-wrapper {
    width: 100%;
    margin-bottom: 1rem;

    .cash-register-input {
        flex-grow: 1;
    }
}

@media only screen and (min-width: 768px) {
    .register-input-wrapper {
        width: 50%;
        margin-bottom: 0;

        .cash-register-input {
            flex-grow: 0;
        }
    }
}
</style>
