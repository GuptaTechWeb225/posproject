import {axiosGet} from "../../../common/Helper/AxiosHelper";

const state = {
    userprofile:{}
};
const getters = {
    getUserInformation: state => {
        return state.userprofile
    }
};
const actions = {
    getUserInformation({commit, state}) {
            axiosGet(`admin/authenticate-user`).then(({ data }) => {
                commit('PROFILE_PERSONAL_INFO', data)
            }).catch((error) => this.$toastr.e(error));
    },
};
const mutations = {
    PROFILE_PERSONAL_INFO(state, data) {
        state.userprofile = data;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}
