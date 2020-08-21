import store from './index'
import axios from 'axios'
export const auth = {
    state: {
        pin: 0,
        loginState: false,
        userName: '',
        userId: 0,
    },
    getters: {
        getPIN: (state) => {
            return state.pin;
        },
        getLOGIN_STATE: (state) => {
            return state.loginState;
        },
        getUSERNAME: (state) => {
            return state.userName;
        },
        getUSERID: (state) => {
            return state.userId;
        },
    },
    actions: {
        BREAK() {
            var ip = store.getters.getIP
            axios
                .get(
                    `/conexion/cd_conectar.php?ip=${this.ip}&pin=${store.getters.getPIN}`
                )
                .then(() => {
                    console.log('libero')
                })
                .catch(error => {
                    console.log(error);
                });
        },
    },
    mutations: {
        SET_PIN(state, value) {
            state.pin = value;
        },
        SET_LOGIN_STATE(state, value) {
            state.loginState = value;
        },
        SET_USER_NAME(state, value) {
            state.userName = value;
        },
        SET_USER_ID(state, value) {
            state.userId = value;
        },
    },
    modules: {}
}