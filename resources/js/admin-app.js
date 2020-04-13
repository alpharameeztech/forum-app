require('./bootstrap');
import Vue from 'vue'
import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
Vue.use(VueRouter)
Vue.use(Vuetify);

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
