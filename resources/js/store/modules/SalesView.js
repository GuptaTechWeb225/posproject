import {axiosGet} from "../../common/Helper/AxiosHelper";
import {ON_HOLD_ORDERS, SALES_VIEW_PRODUCTS, VARIANT_PRODUCT_TAX} from "../../tenant/Config/ApiUrl-CP";
import {ALL_TAX} from "../../tenant/Config/ApiUrl-CPB";

const state = {
    ordersOnHold: null,
    products: null,
    isOnLastPageOfProducts: false,
    viewMode: 'primary', // primary - thumbnail view, secondary - barcode view
    flatDiscountActive: false,
    cash_register_id: '',
    customerInputKey: 0, // using this to re-render the customer search and select
    new_cash_register_id_to_change_to: '',
    resumingOrder: false,
    pendingOrder: {
        selectedCustomer: '',
        itemsInCart: [],
        discountValue: 0, // raw discount amount before calculation
        discountOnSubtotal: 0, // discount amount after calculation
        discountType: "percentage",
        discountOnSubtotalName: '',
        tax: null,
        taxAmount: 0,
        subTotal: 0,
        grandTotal: 0,
    }
};

const getters = {
    getCustomerInputKey(state) {
        return state.customerInputKey;
    },
    getDiscountValue(state) {
        return state.pendingOrder.discountValue;
    },
    getDiscountOnSubtotal(state) {
        return state.pendingOrder.discountOnSubtotal;
    },
    getSubtotalDiscountType(state) {
        return state.pendingOrder.discountType;
    },
    getFlatDiscountActiveStatus(state) {
        return state.flatDiscountActive;
    },
    getNewCashRegisterId(state) {
        return state.new_cash_register_id_to_change_to;
    },
    getProductSellingPrice: (state) => (upc) => {
        const correspondingProduct = state.products.find(product => product.variant.upc.toString() === upc.toString());
        if (correspondingProduct) return correspondingProduct.selling_price;
        return 0;
    },
    getState(state) {
        return state;
    },
    getOrdersOnHold(state) {
        return state.ordersOnHold;
    },
    numberOfHeldOrders(state) {
        if (!state.ordersOnHold) return 0;
        return state.ordersOnHold.length;
    },
    getCashRegisterId(state) {
        return state.cash_register_id;
    },
    getGrandTotal(state) {
        return state.pendingOrder.grandTotal
    },
    getSelectedCustomer(state) {
        return state.pendingOrder.selectedCustomer;
    },
    getTotalTax(state) {
        if (!state.pendingOrder.tax) return 0;
        return state.pendingOrder.tax.percentage;
    },
    getSubtotalDiscountName(state) {
        return state.pendingOrder.discountOnSubtotalName;
    },
    getTaxAmount(state) {
        if (!state.pendingOrder.taxAmount) return 0;
        return state.pendingOrder.taxAmount;
    },
    saleViewProducts(state) {
        if (state.products === null) return state.products;
        return state.products
            .map((product) => ({...product, quantity: 1}))
            .map((product) => ({
                    ...product,
                    product_selected: state.pendingOrder.itemsInCart.some(
                        (cartItem) => cartItem.upc.toString() === product.variant.upc.toString()
                    ),
                }
            ));
    },
    cartItems(state) {
        return state.pendingOrder.itemsInCart.map((cartItem) => ({
            ...cartItem,
            quantity: cartItem.quantity ?? 1,
        }));
    },
    viewMode(state) {
        return state.viewMode;
    },
    subtotal(state) {
        return state.pendingOrder.subTotal;
    },
    getTaxId(state) {
        if (!state.pendingOrder.tax) return;
        return state.pendingOrder.tax.id;
    },
    getTaxData(state) {
        return state.pendingOrder.tax;
    },
    totalItems(state) {
        return state.pendingOrder.itemsInCart
            .map((item) => item.quantity)
            .reduce((a, v) => a + +v, 0);
    },
    getOrderResumeState(state) {
        return state.resumingOrder;
    },
    getIsOnLastPageOfProducts(state) {
        return state.isOnLastPageOfProducts;
    }
};

const mutations = {
    INCREMENT_CUSTOMER_INPUT_KEY(state) {
        state.customerInputKey += 1;
    },
    SET_DISCOUNT_VALUE(state, amount) {
        state.pendingOrder.discountValue = amount;
    },
    SET_FLAT_DISCOUNT_ACTIVE_STATUS(state, newStatus) {
        state.flatDiscountActive = newStatus;
    },
    SET_NEW_CASH_REGISTER_ID(state, newId) {
        state.new_cash_register_id_to_change_to = newId;
    },
    SET_ORDER_RESUME_STATE(state, newResumingState) {
        state.resumingOrder = newResumingState;
    },
    SET_CASH_REGISTER_ID(state, newId) {
        state.cash_register_id = newId;
    },
    TOGGLE_CART_ITEM_DROPDOWN(state, {cartItemUpc, show}) {
        state.pendingOrder.itemsInCart = state.pendingOrder.itemsInCart.map(itemInCart => itemInCart.upc.toString() !== cartItemUpc.toString() ? ({
            ...itemInCart,
            dropdownActive: false,
        }) : ({...itemInCart, dropdownActive: show}))
    },
    SET_SUBTOTAL(state, subTotal) {
        state.pendingOrder.subTotal = subTotal;
    },
    SET_GRAND_TOTAL(state, grandTotal) {
        state.pendingOrder.grandTotal = grandTotal;
    },
    SET_TAX_DATA(state, taxData) {
        state.pendingOrder.tax = taxData;
    },
    SET_TAX_AMOUNT(state, taxAmount) {
        state.pendingOrder.taxAmount = taxAmount;
    },
    SET_DISCOUNT_ON_SUBTOTAL(state, payload) {
        state.pendingOrder.discountOnSubtotal = payload;
    },
    SET_DISCOUNT_ON_SUBTOTAL_TYPE(state, discountType) {
        state.pendingOrder.discountType = discountType;
    },
    SET_DISCOUNT_ON_SUBTOTAL_NAME(state, discountName) {
        state.pendingOrder.discountOnSubtotalName = discountName;
    },
    SET_SELECTED_CUSTOMER(state, customerId) {
        state.pendingOrder.selectedCustomer = customerId;
    },
    SET_ITEM_NOTE(state, payload) {
        const itemToUpdate = state.pendingOrder.itemsInCart.find(item => item.upc.toString() === payload.upc.toString());
        itemToUpdate.note = payload.note;
    },
    SET_PRODUCTS(state, payload) {
        state.products = payload;
    },
    ADD_NEW_PRODUCTS(state, payload) { // this is for pagination
        state.products = [...state.products, ...payload];
    },
    ADD_ITEMS_TO_CART(state, payload) {
        state.pendingOrder.itemsInCart.push(payload);
    },
    REMOVE_ITEM_FROM_CART(state, upc) {
        state.pendingOrder.itemsInCart = state.pendingOrder.itemsInCart.filter(
            (cartItem) => cartItem.upc.toString() !== upc.toString()
        );
    },
    CHANGE_VIEW_MODE(state, payload) {
        state.viewMode = payload;
    },
    CHANGE_CART_ITEM_SELLING_PRICE(state, payload) {
        const itemToChangePriceOf = state.pendingOrder.itemsInCart.find(item => item.upc.toString() === payload.upc.toString());
        itemToChangePriceOf.selling_price = payload.new_product_price;
        itemToChangePriceOf.sub_total = payload.new_product_price;
    },
    CHANGE_CART_ITEM_PRICE(state, payload) {
        const itemToChangePriceOf = state.pendingOrder.itemsInCart.find(item => item.upc.toString() === payload.upc.toString());
        itemToChangePriceOf.price = payload.newPrice;
    },
    SET_ITEM_QUANTITY(state, payload) {
        const itemToChangeQuantityOf = state.pendingOrder.itemsInCart.find(item => item.upc.toString() === payload.upc.toString());
        itemToChangeQuantityOf.quantity = +payload.new_quantity_amount;
    },
    INCREMENT_ITEM_QUANTITY(state, payload) {
        const itemToIncrementQuantityOf = state.pendingOrder.itemsInCart.find(item => item.upc.toString() === payload.upc.toString());
        itemToIncrementQuantityOf.quantity = itemToIncrementQuantityOf.quantity + 1;
    },
    DECREMENT_ITEM_QUANTITY(state, payload) {
        const itemToDecrementQuantityOf = state.pendingOrder.itemsInCart.find(item => item.upc.toString() === payload.upc.toString());
        itemToDecrementQuantityOf.quantity = itemToDecrementQuantityOf.quantity - 1;
    },
    SET_ITEM_DISCOUNT_DETAILS(state, payload) {
        const itemToUpdate = state.pendingOrder.itemsInCart.find(item => item.upc.toString() === payload.upc.toString());
        itemToUpdate.discount_type = payload.discount_type;
        itemToUpdate.discount_amount = payload.discount_amount;
        itemToUpdate.discount_value = payload.discount_value;
    },
    CLEAR_CART(state) {
        state.viewMode = 'primary';
        state.pendingOrder = {
            ...state.pendingOrder,
            selectedCustomer: '',
            discountValue: 0,
            itemsInCart: [],
            discountOnSubtotal: 0,
            discountType: 'percentage',
            discountOnSubtotalName: '',
            tax: null,
            taxAmount: 0,
            subTotal: 0,
            grandTotal: 0,
        }
    },
    SET_ORDERS_ON_HOLD(state, ordersOnHold) {
        state.ordersOnHold = [...ordersOnHold];
    },
    CLEAR_ORDERS_ON_HOLD(state) {
        state.ordersOnHold = null;
    },
    SET_CART(state, payload) {
        for (const key in payload) {
            state[key] = payload[key];
        }
    },
    SET_PENDING_ORDER(state, newOrderToResume) {
        state.pendingOrder = newOrderToResume;
    },
    SET_IS_ON_LAST_PAGE_OF_PRODUCTS(state, payload) {
        state.isOnLastPageOfProducts = payload;
    }
};

const actions = {
    async setPendingOrder({state, commit, dispatch}, orderId) {
        dispatch('cancelOrder'); // resetting everything in cart before resuming a held order
        const snakeToCamel = (str) => str.replace(/(?!^)_(.)/g, (_, char) => char.toUpperCase());
        let saleToResume = state.ordersOnHold.find(order => +order.id === +orderId);
        const saleToResumeWithCamelKeys = {};
        for (const key in saleToResume)
            saleToResumeWithCamelKeys[snakeToCamel(key)] = saleToResume[key];

        saleToResumeWithCamelKeys.selectedCustomer = saleToResumeWithCamelKeys.customerId;
        saleToResumeWithCamelKeys.discountOnSubtotal = saleToResumeWithCamelKeys.discount;

        const {data: taxData} = await axiosGet(ALL_TAX);
        saleToResumeWithCamelKeys.tax = taxData.find(td => +td.id === +saleToResumeWithCamelKeys.taxId);
        saleToResumeWithCamelKeys.taxAmount = saleToResumeWithCamelKeys.tax ? (saleToResumeWithCamelKeys.subTotal * (saleToResumeWithCamelKeys.tax.percentage / 100)) : 0;

        saleToResumeWithCamelKeys.itemsInCart = saleToResumeWithCamelKeys.orderProducts.map(op => ({
            ...op,
            product_id: op.stock.variant.product_id,
            upc: op.stock.variant.upc.toString(),
            selling_price: op.stock.variant.selling_price,
            price: op.price,
            name: op.stock.variant.name
        }));

        delete saleToResumeWithCamelKeys.orderProducts;
        delete saleToResumeWithCamelKeys.discount;
        delete saleToResumeWithCamelKeys.customerId;

        const {discountValue, discountOnSubtotal} = saleToResumeWithCamelKeys;
        saleToResumeWithCamelKeys.discountValue = discountOnSubtotal;
        saleToResumeWithCamelKeys.discountOnSubtotal = discountValue;

        commit('SET_PENDING_ORDER', {...saleToResumeWithCamelKeys, is_being_held: true});
        commit('SET_ORDER_RESUME_STATE', true);
        commit('INCREMENT_CUSTOMER_INPUT_KEY');
    },
    changeItemQuantityInCart({commit, state}, payload) {
        if (payload.type === 'increment')
            return commit('INCREMENT_ITEM_QUANTITY', payload);

        const itemInCart = state.pendingOrder.itemsInCart.find(
            (item) => item.upc.toString() === payload.upc.toString()
        );
        const existingItemQuantity = itemInCart.quantity;
        if (existingItemQuantity === 1)
            return commit('REMOVE_ITEM_FROM_CART', payload.upc.toString());
        return commit('DECREMENT_ITEM_QUANTITY', payload);
    },
    async addItemToCart({commit, state, rootState}, payload) {
        const productInCart = state.pendingOrder.itemsInCart.find((cartItem) => cartItem.upc.toString() === payload.variant.upc.toString());
        if (productInCart) return commit('INCREMENT_ITEM_QUANTITY', {...payload, upc: payload.variant.upc.toString()});

        const productDiscountAmount = (() => {
            const data = {...payload.variant};
            if (data.discount_product === null || !Object.keys(data.discount_product).length) return 0;
            return data.discount_product.type === 'percentage' ? payload.selling_price - (payload.selling_price * (data.discount_product.value / 100)) : data.discount_product.value;
        })();

        const newPayload = {
            id: payload.variant.id,
            product_id: payload.variant.product_id,
            stock_reminder_quantity: payload.variant.stock_reminder_quantity,
            upc: payload.variant.upc.toString(),
            selling_price: +payload.variant.selling_price,
            name: payload.variant.name,
            thumbnail: payload.variant.thumbnail,
            stock: {
                id: payload.id,
                branch_or_warehouse_id: payload.branch_or_warehouse_id,
                variant_id: payload.branch_or_warehouse_id,
                avg_purchase_price: payload.avg_purchase_price,
                total_purchase_qty: payload.total_purchase_qty,
                available_qty: payload.available_qty,
            },
            quantity: 1,
            price: +payload.variant.selling_price,
            stock_id: payload.id,
            avg_purchase_price: payload.avg_purchase_price,
            variant_id: payload.variant.id,
            order_product_id: payload.variant.product_id,
            dropdownActive: false,
            tenant_id: 1,
            note: "",
            discount_type: payload?.variant?.discount_product?.discount.type ?? "flat",
            discount_value: payload?.variant?.discount_product?.discount.value,
            discount_amount: productDiscountAmount,
            tax_id: payload.variant.tax_id,
            tax_amount: +payload.variant.tax?.percentage || 0,
        }
        commit('ADD_ITEMS_TO_CART', newPayload);
    },
    async setProducts({commit, state, rootState}, filterQueryString = '') {
        const branchOrWarehouseId = rootState.auth.branch_or_warehouse_id;
        const salesViewProducts = await axiosGet(`${SALES_VIEW_PRODUCTS}?branch_or_warehouse_id=${branchOrWarehouseId}${filterQueryString}`);
        commit('SET_PRODUCTS', salesViewProducts.data.data);
    },
    async addNewProducts({commit, rootState}, pageNumber) {
        const branchOrWarehouseId = rootState.auth.branch_or_warehouse_id;
        const salesViewProducts = await axiosGet(`${SALES_VIEW_PRODUCTS}?branch_or_warehouse_id=${branchOrWarehouseId}&page=${pageNumber}`);
        if (!Boolean(salesViewProducts.data.next_page_url)) commit('SET_IS_ON_LAST_PAGE_OF_PRODUCTS', true)
        commit('ADD_NEW_PRODUCTS', salesViewProducts.data.data);
    },
    async setHoldOrders(
        {
            commit,
            dispatch,
            state,
            getters: {getCashRegisterId},
            rootGetters: {getBranchOrWarehouseId}
        }, reload = false
    ) {
        try {
            const apiEndpoint = `${ON_HOLD_ORDERS}?branch_or_warehouse_id=${getBranchOrWarehouseId}&cash_register_id=${getCashRegisterId}`;
            const {data: ordersOnHold} = await axiosGet(apiEndpoint);
            commit('SET_ORDERS_ON_HOLD', ordersOnHold);
            if (reload) {
                dispatch('cancelOrder');
            }
        } catch (e) {
            this.$toastr.e(e)
        }
    },
    cancelOrder({commit, state}) {
        localStorage.removeItem('cartState')
        commit('CLEAR_CART');
        commit('SET_SELECTED_CUSTOMER', 1);
        commit('INCREMENT_CUSTOMER_INPUT_KEY');
        commit('SET_ORDER_RESUME_STATE', false);
    },
    setCart({commit, state}, payload) {
        const cartState = JSON.parse(payload);
        if (cartState.pendingOrder.discountType === 'flat') cartState.pendingOrder.discountType = 'fixed';
        commit('SET_CART', cartState);
        commit('SET_ORDER_RESUME_STATE', false);
    },
    setCashRegisterId({commit}, payload) {
        const existingLocalStorageId = localStorage.getItem('cash_register_id');
        if (existingLocalStorageId !== payload) localStorage.setItem('cash_register_id', JSON.stringify(payload));
        commit('SET_CASH_REGISTER_ID', payload);
    }
};

export default {
    state,
    getters,
    mutations,
    actions,
};
