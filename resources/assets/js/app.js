import './bootstrap'

import PurchaseProduct from './components/PurchaseProduct.vue'
import ProductSearch from './components/ProductSearch.vue'

Vue.component('purchase-product', PurchaseProduct)
Vue.component('product-search', ProductSearch)

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
