import {SELECTABLE_BRANCH} from "../../../tenant/Config/ApiUrl-CP";
import {axiosGet} from "../../../common/Helper/AxiosHelper";

const state = {
    branches: [],
};
const getters = {
    branchesOptions: state => state.branches
};
const mutations = {
    BRANCH_LIST(state, data) {
        state.branches = data
    }
};
const actions = {
    getSelectableBranches({commit}) {
        axiosGet(SELECTABLE_BRANCH).then((response) => {
            commit('BRANCH_LIST', response.data)
        }).catch((error) => this.$toastr.e(error));
    }
};
export default {
    state,
    getters,
    mutations,
    actions
}