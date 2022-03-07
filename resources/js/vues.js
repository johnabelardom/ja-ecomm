/*
 * libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue')
// // import Vue from 'vue';
// // import * as Vue from 'vue';
// // import { createApp } from 'vue'

// // import Products from './components/Products.vue';
// Vue.component(
//     'Products',
//     require('./components/Products.vue').default
// );

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('products', require('./components/Products.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// window.products = createApp({
//     // el: '#products',
//     data() {
//         return {
//             count: 0,
//             currentPage: 1,
//             products: {},
//         }
//     },
//     mounted() {
//         this.getNextProducts();
//     },
//     methods: {
//     }
// });
// window.products.component('Products', Products);
// window.products.methods = ({
//     getProducts(page = 1) {
//         var _this = this;
//         fetch('api/products', {
//             method: 'GET',
//             headers: {
//                 'Accept': 'application/json, text/plain, */*',
//                 'Content-Type': 'application/json'
//             },
//             // body: JSON.stringify({
//             //     page: page,
//             // })
//         }).then(res => res.json())
//             .then(res => { _this.products = res; });
//     },
//     getNextProducts() {
//         this.currentPage++;
//         this.getProducts(this.currentPage);
//     }
// });

// const cart = new Vue({
//     el: '#cart',
// });

// const checkout = new Vue({
//     el: '#checkout',
// });


import { createApp } from "vue";
import Products from "./components/Products";
import Cart from "./components/Cart";
import Swal from 'sweetalert2';

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

if (document.querySelector('#products')) {
    createApp({
        data() {
            return {
                products: {},
                config: {},
                cart: {},
            }
        },
        methods: {
            getConfig() {
                var _this = this;
                fetch('/api/config', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     page: page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.config = res; });
            },
            getProducts(page = 1) {
                var _this = this;
                fetch('/api/products', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     page: page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.products = res.data; });
            },
            getNextProducts() {
                this.currentPage++;
                this.getProducts(this.currentPage);
            },
            addToCart(product_id) {
                var _this = this;
                fetch('/api/cart/add-item/' + product_id, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     : page,
                    // })
                }).then(res => res.json())
                    .then(res => { 
                        _this.cart = res.data; 
                        
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully added to cart!'
                        })
                    });
            }
        },
        mounted() {
            document.querySelector('.cart-icon').setAttribute('href', '/vue/cart');
            this.getConfig();
            this.getProducts();
        },
        components: {
            Products,
        },
    }).mount("#products");
}


if (document.querySelector('#cart')) {
    createApp({
        data() {
            return {
                config: {},
                total: 0.00,
                subtotal: 0.00,
                cart_items: {},
                shipping_method: 'standard',
                cart_charges: {
                    shipping: {
                        'name': 'Standard Shipping',
                        'price': 10.00,
                        'quantity': 1,
                    },
                },
            }
        },
        methods: {
            getConfig() {
                var _this = this;
                fetch('/api/config', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     page: page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.config = res; });
            },
            getCartItems(product_id) {
                var _this = this;
                fetch('/api/cart', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     : page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.cart_items = res; });
            },
            getCartCharges() {
                var _this = this;
                fetch('/api/cart/charges', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     : page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.cart_charges = res; });
            },
            updateCartCharges(value) {
                var _this = this;
                fetch('/api/cart/charges', {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        shipping_method: value,
                    })
                }).then(res => res.json())
                    .then(res => { _this.getCartDetails() });
            },
            increaseItem(product_id) {
                var _this = this;
                fetch('/api/cart/increase-item/' + product_id, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     : page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.getCartDetails(); });
            },
            decreaseItem(product_id) {
                var _this = this;
                fetch('/api/cart/decrease-item/' + product_id, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     : page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.getCartDetails(); });
            },
            removeItem(product_id) {
                var _this = this;
                fetch('/api/cart/remove-item/' + product_id, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     : page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.getCartDetails(); });
            },
            getCartDetails() {
                var _this = this;
                fetch('/api/cart/details', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     page: page,
                    // })
                }).then(res => res.json())
                    .then(res => { 
                        _this.cart_items = res.cart_items; 
                        _this.cart_charges = res.cart_charges; 
                        _this.config = res.config;
                        _this.total = res.total;
                        _this.subtotal = res.subtotal;
                        _this.shipping_method = res.shipping_method;
                    });
            },
            monify(money) {
                // console.log(this.config);
                // return 1;
                // return this.config.currency.symbol + money;
                return money;
            },
            gotoCheckout() {
                window.location.href = '/vue/checkout';
            }
        },
        mounted() {
            document.querySelector('.cart-icon').setAttribute('href', '/vue/cart');
            // this.getConfig();
            // this.getCartItems();
            this.getCartDetails();
            console.log(this.config);
        },
        components: {
            Cart,
        },
    }).mount("#cart");
}


if (document.querySelector('#checkout')) {
    createApp({
        data() {
            return {
                config: {},
                total: 0.00,
                subtotal: 0.00,
                cart_items: {},
                cart_charges: {
                    shipping: {
                        'name': 'Standard Shipping',
                        'price': 10.00,
                    },
                },
                customer: {
                    firstname: '',
                    lastname: '',
                    email: '',
                    address: '',
                    city: '',
                    country: '',
                    zipcode: '',
                    notes: '',
                }
            }
        },
        methods: {
            removeItem(product_id) {
                var _this = this;
                fetch('/api/cart/remove-item/' + product_id, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     : page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.getCartDetails(); });
            },
            getCartDetails() {
                var _this = this;
                fetch('/api/cart/details', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     page: page,
                    // })
                }).then(res => res.json())
                    .then(res => { 
                        _this.cart_items = res.cart_items; 
                        _this.cart_charges = res.cart_charges; 
                        _this.config = res.config;
                        _this.total = res.total;
                        _this.subtotal = res.subtotal;
                    });
            },
            monify(money) {
                // console.log(this.config);
                // return 1;
                // return this.config.currency.symbol + money;
                return money;
            },
            placeOrder() {
                var _this = this;
                fetch('/api/order', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        customer: _this.customer,
                        items: _this.cart_items,
                        charges: _this.cart_charges,
                    })
                }).then(res => res.json())
                    .then(res => { 
                        if (res.uid) {
                            Swal.fire({
                                title: 'Order successfully placed!',
                                text: 'Do you want to continue',
                                icon: 'success',
                                confirmButtonText: 'Great!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/thank-you/' + res.uid
                                }
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong. Please try again'
                            })
                        }
                    });
            }
        },
        mounted() {
            document.querySelector('.cart-icon').setAttribute('href', '/vue/cart');
            // this.getConfig();
            // this.getCartItems();
            this.getCartDetails();
            console.log(this.config);
        },
        components: {
            Cart,
        },
    }).mount("#checkout");
}