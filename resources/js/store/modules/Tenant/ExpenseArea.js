import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {SELECTABLE_EXPENSE_AREA} from "../../../tenant/Config/ApiUrl-CP";

const state = {
    expenseAreas: [],
};
const getters = {
    expenseAreaOptions: state => state.expenseAreas
};
const mutations = {
    EXPENSE_AREA(state, data) {
        state.expenseAreas = data
    }
};
const actions = {
    getExpenseArea({commit}) {
        axiosGet(SELECTABLE_EXPENSE_AREA).then((response) => {
            commit('EXPENSE_AREA', response.data)
        }).catch((error) => this.$toastr.e(error));
    }
};
export default {
    state,
    getters,
    mutations,
    actions
}