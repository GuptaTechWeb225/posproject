import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {SELECTABLE_TAGS} from "../../../tenant/Config/ApiUrl-CP";
const state = {
    tagList: [],
};
const getters = {
    tagsOptions: state => state.tagList
};
const mutations = {
    TAGS(state, data) {
        state.tagList = data
    }
};
const actions = {
    getTag({commit}) {
        axiosGet(SELECTABLE_TAGS).then((response) => {
            commit('TAGS', response.data)
        }).catch((error) => this.$toastr.e(error));
    }
};
export default {
    state,
    getters,
    mutations,
    actions
}