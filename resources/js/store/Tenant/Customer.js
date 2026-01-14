import {axiosGet} from "../../common/Helper/AxiosHelper";
import {CUSTOMER_DATA} from "../../tenant/Config/ApiUrl-CPB";

const state = {
    customer: {}
}

const actions = {
    getCustomer({commit},customerId) {
        commit('SET_LOADER', true);
        axiosGet(`${CUSTOMER_DATA}/${customerId}`).then(({data}) => {
            commit('CUSTOMER_DATA', data)
        }).finally(() => {
            commit('SET_LOADER', false);
        });
    }
}

const mutations = {
    CUSTOMER_DATA(state, data) {
        state.customer = data;
    }
}

export default {
    state,
    actions,
    mutations
}
