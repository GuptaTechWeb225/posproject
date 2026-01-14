import {SELECTABLE_RECEIVED_BY} from "../../../tenant/Config/ApiUrl-CP";
import {axiosGet} from "../../../common/Helper/AxiosHelper";

const state = {
    receivedBy: [],
};
const getters = {
    receivedByOptions: state => state.receivedBy
};
const mutations = {
    RECEIVED_BY_LIST(state, data) {
        state.receivedBy = data
    }
};
const actions = {
    getSelectableReceivedBy({commit}) {
        axiosGet(SELECTABLE_RECEIVED_BY).then((response) => {
            commit('RECEIVED_BY_LIST', response.data)
        }).catch((error) => this.$toastr.e(error));
    }
};
export default {
    state,
    getters,
    mutations,
    actions
}