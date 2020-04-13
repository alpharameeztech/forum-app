require('./bootstrap');
import Vue from 'vue'
import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import Vuelidate from 'vuelidate'
import '@mdi/font/css/materialdesignicons.css'
import ReadMore from 'vue-read-more';
import VueFusionCharts from 'vue-fusioncharts';
import FusionCharts from 'fusioncharts';
import Charts from 'fusioncharts/fusioncharts.charts';
import TimeSeries from 'fusioncharts/fusioncharts.timeseries';
import FusionTheme from 'fusioncharts/themes/fusioncharts.theme.fusion'
Vue.use(VueRouter)
Vue.use(Vuetify);
Vue.use(Vuelidate)
Vue.use(ReadMore);
Vue.use(VueFusionCharts, FusionCharts, Charts, FusionTheme, TimeSeries)

window.events = new Vue();

window.flash = function(message, type = 'success') {
    window.events.$emit('flash', {message, type} );
}
Vue.component(
    'flash-component',
    require('./components/admin/FlashComponent.vue').default);

Vue.component(
    'loader-component',
    require('./components/admin/forum/Loader.vue').default);
new Vue({
    el: '#app',
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),

})
