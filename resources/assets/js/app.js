
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
window.VeeValidate = require('vee-validate');
window.fz = require('fuzzaldrin-plus');

Vue.use(VeeValidate);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

Vue.component('alert-component', require('./components/AlertComponent.vue'));
Vue.component('table-component', require('./components/TableComponent.vue'));
Vue.component('form-component', require('./components/FormComponent.vue'));
Vue.component('show-component', require('./components/ShowComponent.vue'));
Vue.component('sidebar-component', require('./components/SidebarComponent.vue'));
Vue.component('modal-component', require('./components/ModalComponent.vue'));
Vue.component('single-resource-component', require('./components/SingleResourceComponent.vue'));

window.debounce = function debounce(fn, delay = 300) {
    var timeoutID = null;

    return function () {
        clearTimeout(timeoutID);

        var args = arguments;
        var that = this;

        timeoutID = setTimeout(function () {
            fn.apply(that, args);
        }, delay);
    }
};

Vue.directive('debounce', (el, binding) => {
    if (binding.value !== binding.oldValue) {
        // window.debounce is our global function what we defined at the very top!
        el.oninput = debounce(ev => {
            el.dispatchEvent(new Event('change'));
        }, parseInt(binding.value) || 300);
    }
});

Vue.filter("capitalize", value => {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

Vue.filter("beautify", value => {
    if (!value) return ''
    value = value.toString().match(/[A-Za-z][a-z]*/g) || [];
    return value.join(' ').replace(/\b\w/g, l => l.toUpperCase());
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});