import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {SELECTABLE_ATTRIBUTES} from "../../../tenant/Config/ApiUrl-CP";
const state = {
    variations: [],
};
const getters = {
    attributeVariations: state => state.variations
};
const mutations = {
    VARIATIONS(state, data) {
        state.variations = data
    }
};
const actions = {
    attributeVariations({commit}) {
        axiosGet(SELECTABLE_ATTRIBUTES).then((response) => {
            commit('VARIATIONS', response.data)
        }).catch((error) => this.$toastr.e(error));
    }
};
export default {
    state,
    getters,
    mutations,
    actions
}