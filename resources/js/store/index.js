import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import SecureLS from "secure-ls";
var ls = new SecureLS({ isCompression: false });

Vue.use(Vuex);

import { auth } from './auth';
import { config } from './config';
import { comanda } from './comanda';
const dataState = new createPersistedState({
    paths: ['auth.pin', 'auth.logginState', 'auth.userName', 'auth.userId', 'config.ip', 'config.server', 'comanda.pisos', 'comanda.familias', 'comanda.mesaActual', 'comanda.idMesaActual','comanda.pisoActual'],
    storage: {
        getItem: (key) => ls.get(key),
        setItem: (key, value) => ls.set(key, value),
        removeItem: (key) => ls.remove(key),
    },
})

export default new Vuex.Store({
    modules: {
        auth,
        config,
        comanda
    },
    plugins: [dataState],
});