require('./bootstrap');
import Vue from 'vue'
import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import Vuelidate from 'vuelidate'
import '@mdi/font/css/materialdesignicons.css'
import ReadMore from 'vue-read-more';
import VueFusionCharts from 'vue-fusioncharts';
Vue.use(VueRouter)
Vue.use(Vuetify);
Vue.use(Vuelidate)
Vue.use(ReadMore);
Vue.use(VueFusionCharts, FusionCharts, Charts, FusionTheme, TimeSeries)

window.events = new Vue();

window.flash = function(message, type = 'success') {
    window.events.$emit('flash', {message, type} );
}
new Vue({
    el: '#app',
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),

})
