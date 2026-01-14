import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {CREATED_USERS} from "../../../tenant/Config/ApiUrl-CP";
const state = {
    createdByList: [],
};
const getters = {
    getCreatedBy: state => state.createdByList
};
const mutations = {
    CREATED_BY(state, data) {
        state.createdByList = data
    }
};
const actions = {
    getCreatedBy({commit}) {
        axiosGet(CREATED_USERS).then((response) => {
            commit('CREATED_BY', response.data)
        }).catch((error) => this.$toastr.e(error));
    }
};
export default {
    state,
    getters,
    mutations,
    actions
}