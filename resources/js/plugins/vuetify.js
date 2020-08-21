import Vue from 'vue';
import Vuetify from 'vuetify';
//import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/dist/vuetify.css'

Vue.use(Vuetify)

// Translation provided by Vuetify (javascript)
import es from 'vuetify/es5/locale/es'

export default new Vuetify({
    icons: {
        iconfont: 'mdi' || 'fa', // default - only for display purposes
    },
    lang: {
        locales: { es },
        current: 'es',
    },
})