const state = {
    user_id: '1',
    tenant_id: '1',
    branchManagerKey: 0,
    branch_or_warehouse_id: null,
    branch_or_warehouse_name: '',
};

const getters = {
    getBranchOrWarehouseId(state) {
        return state.branch_or_warehouse_id;
    },
    getUserId(state) {
        return state.user_id;
    },
    getBranchOrWarehouseName(state) {
        return state.branch_or_warehouse_name;
    },
    getTenantId(state) {
        return state.tenant_id;
    },
    getBranchManagerKey(state) {
        return state.branchManagerKey;
    }
};

const mutations = {
    SET_BRANCH_OR_WAREHOUSE_ID(state, payload) {
        state.branch_or_warehouse_id = payload;
    },

    SET_BRANCH_OR_WAREHOUSE_NAME(state, payload) {
        state.branch_or_warehouse_name = payload;
    },

    INC_BRANCH_MANAGER_KEY(state) {
        state.branchManagerKey++;
    },
};

const actions = {
    async setBranchOrWarehouseId({commit, state}, payload) {
        commit('SET_BRANCH_OR_WAREHOUSE_ID',payload);
    },

    async setBranchOrWarehouseName({commit, state}, payload) {
        commit('SET_BRANCH_OR_WAREHOUSE_NAME',payload);
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
