import {axiosGet} from "../../common/Helper/AxiosHelper";
import {SELECTABLE_ROLE} from "../../tenant/Config/ApiUrl-CPB";

const state = {
    selectable: []
}

const actions = {
    getSelectableRoles({commit}) {
        axiosGet(SELECTABLE_ROLE).then(({data}) => {
            commit('ROLE_LIST', data)
        });
    }
}

const mutations = {
    ROLE_LIST(state, data) {
        state.selectable = data;
    }
}

export default {
    state,
    actions,
    mutations
}
