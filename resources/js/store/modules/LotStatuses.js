const state = {
    lotStatuses: [],
}

const getters = {

};

const mutations = {
    SET_LOT_STATUSES(state, payload) {
        state.lotStatuses = payload;
    }
};

export default {
    state,
    getters,
    mutations,
}
