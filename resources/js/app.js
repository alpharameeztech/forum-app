import Vuetify from "vuetify";
import '@mdi/font/css/materialdesignicons.css'
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component(
    'favorite-reply-component',
    require('./components/FavoriteReplyComponent.vue').default);

Vue.component(
    'replies-component',
    require('./components/RepliesComponent.vue').default);

Vue.component(
    'subscribe-thread-component',
    require('./components/ThreadSubscribeComponenet.vue').default);

Vue.component(
    'thread-component',
    require('./components/ThreadComponent.vue').default);

Vue.component(
    'paginator-component',
    require('./components/PaginatorComponent.vue').default);

Vue.component(
    'user-notification-component',
    require('./components/UserNotificationComponent.vue').default);

Vue.component(
    'wysiwyg-component',
    require('./components/Wysiwyg.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:{

    },
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),
});
