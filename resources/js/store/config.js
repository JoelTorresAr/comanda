export const config = {
    state: {
        ip: 'http://192.168.0.2:7000',
        server: 0,
    },
    getters: {
        getIP: (state) => {
            return state.ip;
        },
        getSERVER: (state) => {
            return state.server;
        },
    },
    actions: {},
    mutations: {
        SET_IP(state, value) {
            state.ip = value;
        },
        SET_SERVER(state, value) {
            state.server = value;
        },
    },
    modules: {}
}