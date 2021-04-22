export const state = () =>({
    empData : {},
    otpresponse : null
}) 

export const getters = {
    getEmpData(state){
        return state.empData
    },

    getOtpresponse(state){
        return state.otpresponse
    }
}

export const mutations = {
    SET_EmpData(state,data){
        state.empData = {
            ...state.empData,
            ...data
        }
    },
    SET_Otpresponse(state,data){
        state.otpresponse = {
            ...state.otpresponse,
            ...data
        }
    }
}

export const actions = {
    setEmpData({commit},data){
        commit('SET_EmpData',data)
    },
    setOtpresponse({commit},data){
        commit('SET_Otpresponse',data)
    }
}