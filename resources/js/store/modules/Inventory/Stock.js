import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {STOCK} from "../../../tenant/Config/ApiUrl-CP";

const state = {
    stocks: [],
};

const getters = {
    getStocksByBranchOrWarehouse(state) {
        return state.stocks;
    }
};

const mutations = {
    SET_STOCKS_BY_BRANCH_OR_WAREHOUSE_ID(state, payload) {
        state.stocks = payload;
    }
}

const actions = {

    async setStocksByBranchOrWarehouseId({commit, state}, payload) {
        const {data} = await axiosGet(STOCK,{params: {'branch_or_warehouse_id': payload}})
        commit('SET_STOCKS_BY_BRANCH_OR_WAREHOUSE_ID', data);
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}