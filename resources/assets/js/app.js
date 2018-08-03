import './bootstrap'

Vue.component('checkout', require('./components/Checkout.vue'))
Vue.component('product-search', require('./components/ProductSearch.vue'))
Vue.component('quantity-picker', require('./components/QuantityPicker.vue'))

const app = new Vue({
    el: '#app',

    data: {
        displayNavigation: false
    },

    methods: {
        logout() {
            this.$refs.logoutForm.submit()
        }
    }
});
