import './bootstrap'

import PurchaseProduct from './components/PurchaseProduct.vue'
import ProductSearch from './components/ProductSearch.vue'

Vue.component('purchase-product', PurchaseProduct)
Vue.component('product-search', ProductSearch)

Number.prototype.format = function(n, x) {
    var re = '(\\d)(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$1,');
};

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
