import {axiosGet} from "../../common/Helper/AxiosHelper";
import {PRODUCT_INFO} from "../../tenant/Config/ApiUrl-CP";

const state = {
    product: {}
}

const getters = {
    getProduct: state => state.product
};

const actions = {
    getProduct({commit}, productId) {
        commit('SET_LOADER', true);
        axiosGet(`${PRODUCT_INFO}/${productId}`).then(({data}) => {
            commit('PRODUCT_DATA', data)
        }).finally(() => {
            commit('SET_LOADER', false);
        });
    }
}

const mutations = {
    PRODUCT_DATA(state, data) {
        state.product = data;
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
