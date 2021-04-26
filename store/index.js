export const state = () => ({
    empData: {},
    otpresponse: null,
    line: null
});

export const getters = {
    getLine(state) {
        return state.line;
    },
    getEmpData(state) {
        return state.empData;
    },

    getOtpresponse(state) {
        return state.otpresponse;
    }
};

export const mutations = {
    SET_LINE(state, data) {
        state.line = {
            ...state.line,
            ...data
        };
    },
    SET_EmpData(state, data) {
        state.empData = {
            ...state.empData,
            ...data
        };
    },
    SET_Otpresponse(state, data) {
        state.otpresponse = {
            ...state.otpresponse,
            ...data
        };
    }
};

export const actions = {
    setLine({ commit }, data) {
        commit("SET_LINE", data);
    },
    setEmpData({ commit }, data) {
        commit("SET_EmpData", data);
    },
    setOtpresponse({ commit }, data) {
        commit("SET_Otpresponse", data);
    }
};