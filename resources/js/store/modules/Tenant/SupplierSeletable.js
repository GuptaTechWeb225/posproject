import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {SELECTABLE_SUPPLIERS} from "../../../tenant/Config/ApiUrl-CP";

const state = {
    suppliers: [],
};
const getters = {
    supplierOptions: state => state.suppliers
};
const mutations = {
    SUPPLIER_LIST(state, data) {
        state.suppliers = data
    }
};
const actions = {
    getSelectableSuppliers({commit}) {
        axiosGet(SELECTABLE_SUPPLIERS).then((response) => {
            commit('SUPPLIER_LIST', response.data)
        }).catch((error) => this.$toastr.e(error));
    }
};
export default {
    state,
    getters,
    mutations,
    actions
}