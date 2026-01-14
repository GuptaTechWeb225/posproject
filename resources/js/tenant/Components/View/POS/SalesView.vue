<template>
    <section class="sales-view content-wrapper">
        <pos-view-top-section/>
        <div class="view-body row">
            <product-input
                @filter-btn-click="handleFilterBtnClickEvent"
                @product-search="handleProductSearch"
                :view-mode="viewMode"
                :filter-query-params="filterQueryParams"
            />
            <customer-input :view-mode="viewMode" v-if="showCustomerInput" :key="getCustomerInputKey"/>

            <cash-register-selector-modal v-if="showCashRegisterSelectModal"
                                          @close="handleCashRegisterSelectorModalClose"/>

            <cash-register-notifier-modal
                v-if="showOpenedCashRegisterModal"
                @close="handleCashRegisterNotifierModalClose"
                :openedCashRegisterDetails="openedCashRegisterDetails" 
            />

            <div :class="`${viewMode === 'primary' ? 'col-md-7 custom-resize' : 'col-md-6'} col-12 mt-4 item-display-wrapper`">
                <filter-display
                    :search-term="searchTerm"
                    v-if="showFilterDisplay"
                    @clear-filter-click="handleClearFilterClickEvent"
                    @filter-change="handleFilterChange"
                />

                <!-- Skeletons -->
                <template v-if="saleViewProducts === null">
                    <div class="item-display">
                        <div v-for="i in 20" class="card card-with-shadow rounded d-flex flex-column mb-3 animate-pulse"
                             style="width: 165px; height: 270px"></div>
                    </div>
                </template>

                <template v-else> <!--if saleViewProducts is an array-->
                    <template v-if="!Boolean(saleViewProducts.length)"> <!--if saleViewProducts is empty-->
                        <div class="empty-message d-flex flex-column align-items-center justify-content-around"
                             style="margin-top: 5rem;" :key="saleViewProducts.length">
                            <div class="mb-5" style="opacity: 0.5;">
                                <app-icon :key="saleViewProducts.length" name="shopping-bag" class="text-danger opacity-50"
                                          style="transform: scale(3.5)"/>
                            </div>
                            <h4 class="text-muted">{{ $t('no_items_found') }}</h4>
                        </div>
                    </template>

                    <!-- if saleViewProducts[] has elements in it -->
                    <template v-else>
                        <div class="item-display" v-if="viewMode === 'primary'" @scroll="fetchNewProducts">
                            <sale-item v-for="(product, index) in saleViewProducts" :key="index"
                                       :product="product"/>

                            <div class="pagination-indicators mt-3">
                                <p class="text-muted text-center" v-if="getIsOnLastPageOfProducts">{{ $t('no_more_products_to_show') }}</p>
                                <template v-else>
                                    <div class="text-center" v-if="paginationTriggered">
                                        <app-pre-loader />
                                    </div>
                                </template>
                            </div>
                        </div>
                        <template v-else> <!-- barcode view -->
                            <div :class="`labels col-12 row border-bottom no-gutters mb-3`">
                                <small class="col-4">{{ $t('selected_product') }}</small>
                                <small class="col-4">{{ $t('quantity') }}</small>
                                <small class="col-3">{{ $t('sub_total') }}</small>
                                <small class="col-1"></small>
                            </div>
                            <div class="barcode-view rounded">
                                <cart-item v-for="item in cartItems" :key="item.upc" :item="item"/>
                            </div>
                        </template>
                    </template>
                </template>


                <div v-else>
                    <app-cart/>
                </div>
            </div>

            <div :class="`${viewMode === 'primary' ? 'col-md-5 custom-resize' : 'col-md-6'} col-12 mt-4 cart-parent-wrapper`">
                <div>
                    <app-cart/>
                </div>
            </div>
        </div>
        <div class="bg-info cart-button text-white" @click="handleCartButtonClick">
            <app-icon name="shopping-cart" class="size-18" />
        </div>
    </section>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";

import POSTopSection from './POSTopSection';
import ProductInput from './ProductInput';
import CustomerInput from './CustomerInput';
import CashRegisterSelectModal from './CashRegisterSelectorModal';
import OpenedCashRegisterNotifierModal from './OpenedCashRegisterNotifierModal';
import BranchOrWarehouseSelectorModal from './BranchOrWarehouseSelectorModal';
import Item from './Item';
import Cart from './Cart/Cart';
import FilterDisplay from "./FilterDisplay";
import {mapActions, mapGetters, mapMutations} from "vuex";
import CartDetails from "./Cart/CartDetails";
import CartItem from "./Cart/CartItem";
import CartControl from "./Cart/CartControl";
import { CASH_COUNTER_OPEN_CLOSE_STATUS } from "../../../Config/ApiUrl-CP";

export default {
    mixins: [FormHelperMixins],
    components: {
        'pos-view-top-section': POSTopSection,
        'product-input': ProductInput,
        'customer-input': CustomerInput,
        'sale-item': Item,
        'app-cart': Cart,
        'cart-item': CartItem,
        'filter-display': FilterDisplay,
        'cart-details': CartDetails,
        'cash-register-selector-modal': CashRegisterSelectModal,
        'cash-register-notifier-modal': OpenedCashRegisterNotifierModal,
        'branch-or-warehouse-select-modal': BranchOrWarehouseSelectorModal,
        'cart-control': CartControl
    },
    name: 'AppSalesView',
    data() {
        return {
            showCustomerInput: false,
            showFilterDisplay: false,
            pageNumber: 1,
            showCashRegisterSelectModal: false,
            showOpenedCashRegisterModal: false,
            openedCashRegisterDetails: null,
            paginationTriggered: false,
            // showBranchOrWarehouseSelectorModal: true,
            productOptions: {
                url: urlGenerator("app/selectable-variants"),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader",
                modifire: ({id, name: value}) => ({id, value}),
            },
            filterQueryParams: null,
            searchTerm: null,
            isAtTheBottomOfTheScreen: false
        }
    },
    computed: {
        ...mapGetters(['saleViewProducts', 'cartItems', 'viewMode', 'getBranchOrWarehouseId', 'getState', 'getCashRegisterId', 'getSelectedCustomer', 'getCustomerInputKey', 'getIsOnLastPageOfProducts']),

    },
    watch: {
        cartItems: {
            immediate: true,
            handler: function (newCartItems) {
                if (newCartItems.length) return;
                this.SET_SUBTOTAL(0);
            }
        },
        getState: {
            immediate: false,
            deep: true,
            handler: function (cartState) {
                localStorage.setItem('cartState', JSON.stringify(cartState));
            }
        },
        getBranchOrWarehouseId: {
            immediate: true,
            handler(newId) {
                if (!newId) return;
                this.setProducts();
                if (window.innerWidth >= 1920) {
                    this.pageNumber++;
                    this.addNewProducts(this.pageNumber)
                }
            }
        },
    },
    created() {
        const previouslyOpenedCashRegisterId = localStorage.getItem('cash_register_id');
        if (previouslyOpenedCashRegisterId) {
            // disabling the cash register and branch selector modal if user has a cash_register opened
            this.setCashRegisterId(previouslyOpenedCashRegisterId);
            this.showCashRegisterSelectModal = false;
        } else {
            axiosGet(CASH_COUNTER_OPEN_CLOSE_STATUS).then(data => {
                if (data.data.cash_register) {
                    this.openedCashRegisterDetails = data.data.cash_register
                    this.showOpenedCashRegisterModal = true
                    return
                }
                this.showCashRegisterSelectModal = true
            })
        }

        this.$hub.$on('open_cash_register_selector_modal', () => {
            this.showCashRegisterSelectModal = true
        });
    },
    mounted() {
        const stateInLocalStorage = localStorage.getItem('cartState');
        if (stateInLocalStorage) this.setCart(stateInLocalStorage);
        this.SET_IS_ON_LAST_PAGE_OF_PRODUCTS(false)
        this.showCustomerInput = true;
    },
    methods: {
        handleCartButtonClick() {
            window.scrollTo({ left: 0, top: document.body.scrollHeight, behavior: "smooth" });
        },
        ...mapMutations(['SET_SUBTOTAL', 'SET_ORDER_RESUME_STATE', 'INCREMENT_CUSTOMER_INPUT_KEY', 'SET_IS_ON_LAST_PAGE_OF_PRODUCTS']),
        ...mapActions(['setProducts', 'setHoldOrders', 'setCart', 'setCashRegisterId', 'addNewProducts']),
        handleFilterChange(filterQueryParams) {
            this.filterQueryParams = filterQueryParams 

        },
        handleCashRegisterNotifierModalClose() {
            this.showOpenedCashRegisterModal = false
            this.openedCashRegisterDetails = null
        },
        handleCashRegisterSelectorModalClose() {
            this.showCashRegisterSelectModal = false;
        },
        handleProductSearch(searchTerm) {
            this.pageNumber = 1;
            this.searchTerm = searchTerm;
        },
        handleFilterBtnClickEvent() {
            this.showFilterDisplay = true;
        },
        handleClearFilterClickEvent() {
            this.showFilterDisplay = false;
            this.filterQueryParams = null; 
        },
        async fetchNewProducts(event) {
            if (!Boolean(event.target.offsetHeight + event.target.scrollTop >= event.target.scrollHeight)) return;
            if (this.getIsOnLastPageOfProducts) return;
            this.paginationTriggered = true;
            this.pageNumber++;
            await this.addNewProducts(this.pageNumber)
            this.paginationTriggered = false;
        }
    }
}
</script>

<style lang="scss">
.item-display {
    height: 35rem;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(4, 1fr);
    justify-items: stretch;
    align-items: stretch;
    grid-gap: 1.5rem;
    overflow-y: scroll;
    overflow-x: hidden;
    padding-bottom: 5rem;
    -ms-overflow-style: none;
    scrollbar-width: 0.25vw;
    scrollbar-color: #4466F255 #99999900;

    .pagination-indicators {
        grid-column: 1 / -1; 
    }

    &::-webkit-scrollbar {
        width: 0.25vw;
    }
    &::-webkit-scrollbar-track {
        background-color: #99999900;
    }
    &::-webkit-scrollbar-thumb {
        background-color: #4466F255;
        border-radius: 50px;
    }
}

.sales-view {
    .cart-button {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: grid;
        place-items: center;
        transition: hover;
    }
    .item-display-wrapper {
        margin-bottom: 3rem;
    }

    @media (min-width: 768px) {
        .cart-button {
            display: none;
        }

        .item-display-wrapper {
            margin-bottom: 0;
        }
    }
}

.arrows-hidden input::-webkit-outer-spin-button,
.arrows-hidden input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.arrows-hidden input[type=number] {
    -moz-appearance: textfield;
}
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

@media only screen and (min-width: 1920px) {
    .item-display {
        grid-template-columns: repeat(5, 1fr);
        grid-gap: 0.65rem;
        height: 45rem;
    }
}

.default-base-color {
    background-color: var(--default-card-bg);
}
.barcode-view {
    height: 75vh;
    padding-bottom: 5rem;
    overflow-y: scroll;
    overflow-x: hidden;
    -ms-overflow-style: none;
    scrollbar-width: none;

    &::-webkit-scrollbar {
        display: none;
    }
}

.cart-parent-wrapper {
    order: 2;
}

@media only screen and (min-width: 768px) {
    .custom-resize {
        flex: 0 0 50%;
        max-width: 50%;

        .item-display {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .cart-parent-wrapper {
        order: 0;
    }
}

@media only screen and (min-width: 1400px) {
    .custom-resize .item-display {
        grid-template-columns: repeat(4, 1fr);
    }
}

</style>
