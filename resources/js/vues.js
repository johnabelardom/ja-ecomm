/*
 * libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue')
import { Country, State, City }  from 'country-state-city';

window.__ecomm = {
    cleanKeys: function(key) {
        if (this.getItem(key)) {
            var items = this.getItem(key);

            
            for (var propName in items) {
                if (items[propName] === null || items[propName] === undefined) {
                delete items[propName];
                }
            }
            
            this.setItem(key, items);
        }
    },
    getItem: function(key) {
        return JSON.parse(window.localStorage.getItem(key));
    },
    setItem: function(key, data) {
        window.localStorage.setItem(key, JSON.stringify(data));
    },
    removeItem(key) {
        window.localStorage.removeItem(key);
    }
};


import { createApp } from "vue";
import Products from "./components/Products";
import Cart from "./components/Cart";
import Swal from 'sweetalert2';
import vSelect from 'vue-select'


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
                products: [],
                config: {},
                cart: {},
                nextPage: 1,
                hasNextPage: true,
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
            getProducts() {
                var _this = this;
                fetch('/api/products?page=' + this.nextPage, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     page: page,
                    // })
                }).then(res => res.json())
                    .then(res => { _this.products = _this.products.concat(res.data); _this.nextPage++; _this.hasNextPage = res.last_page != this.nextPage-1; });
            },
            getNextProducts() {
                this.currentPage++;
                this.getProducts(this.currentPage);
            },
            addToCart(product_id) {
                var _this = this;

                var cartItems = __ecomm.getItem('cart_items', []);

                if (! cartItems) {
                    cartItems = [];
                }
                console.log(cartItems);
                
                var existingItem = cartItems.find(function(element, index) {
                    if (element.id == product_id ) {
                        cartItems[index].quantity++;
                        __ecomm.setItem('cart_items', cartItems);
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully added to cart!',
                            position: 'bottom-right'
                        })
                        console.log('Entered 1');
                    }
                    return element.id == product_id;
                });

                if (existingItem) {
                    // existingItem.quantity++;
                    // __ecomm.setItem('cart_items', cartItems);
                    // Toast.fire({
                    //     icon: 'success',
                    //     title: 'Successfully added to cart!'
                    // })
                    // console.log('Entered 1');
                } else {
                    var product = this.products.find(function(element, index) {
                        return element.id == product_id;
                    });

                    if (product) {
                        cartItems.push( {
                            image: product.image,//"https://picsum.photos/id/65/500/500",
                            name: product.name,//"blanditiis",
                            price: product.price,//175.28,
                            quantity: 1,//5,
                            seller_id: product.user_id,//2,
                            id: product.id,//2,
                        })

                        __ecomm.setItem('cart_items', cartItems);
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully added to cart!'
                        })
                        console.log('Entered 233');
                    }
                }

                __ecomm.cleanKeys('cart_items');

                // fetch('/api/cart/add-item/' + product_id, {
                //     method: 'POST',
                //     headers: {
                //         'Accept': 'application/json, text/plain, */*',
                //         'Content-Type': 'application/json'
                //     },
                //     // body: JSON.stringify({
                //     //     : page,
                //     // })
                // }).then(res => res.json())
                //     .then(res => { 
                //         _this.cart = res.data; 
                        
                //         Toast.fire({
                //             icon: 'success',
                //             title: 'Successfully added to cart!'
                //         })
                //     });
            }
        },
        mounted() {
            document.querySelector('.cart-icon').setAttribute('href', '/cart');
            this.getConfig();
            this.getProducts();
        },
        components: {
            Products,
        },
    }).mount("#products");
}

import 'vue-select/dist/vue-select.css'; 
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
            calculateTotals() {
                this.subtotal = 0.00;

                for(var item in this.cart_items) {
                    this.subtotal = this.subtotal + (this.cart_items[item].price * this.cart_items[item].quantity);
                }
            },
            refreshCartItems() {
                this.cart_items = __ecomm.getItem('cart_items');
                this.calculateTotals();
            },

            increaseItem(product_id) {
                var _this = this;
                var cartItems = __ecomm.getItem('cart_items', []);

                if (! cartItems) {
                    cartItems = [];
                }

                if (cartItems[product_id]) {
                    cartItems[product_id].quantity++;
                }

                __ecomm.setItem('cart_items', cartItems);
                this.refreshCartItems();

                // fetch('/api/cart/increase-item/' + product_id, {
                //     method: 'POST',
                //     headers: {
                //         'Accept': 'application/json, text/plain, */*',
                //         'Content-Type': 'application/json'
                //     },
                //     // body: JSON.stringify({
                //     //     : page,
                //     // })
                // }).then(res => res.json())
                //     .then(res => { _this.getCartDetails(); });
            },
            decreaseItem(product_id) {
                var _this = this;
                var cartItems = __ecomm.getItem('cart_items', []);

                if (! cartItems) {
                    cartItems = [];
                }

                if (cartItems[product_id]) {
                    cartItems[product_id].quantity++;
                }

                __ecomm.setItem('cart_items', cartItems);
                this.refreshCartItems();
                // var _this = this;
                // fetch('/api/cart/decrease-item/' + product_id, {
                //     method: 'POST',
                //     headers: {
                //         'Accept': 'application/json, text/plain, */*',
                //         'Content-Type': 'application/json'
                //     },
                //     // body: JSON.stringify({
                //     //     : page,
                //     // })
                // }).then(res => res.json())
                //     .then(res => { _this.getCartDetails(); });
            },
            removeItem(product_id) {
                var _this = this;
                var cartItems = __ecomm.getItem('cart_items', []);
                // alert(product_id);

                //  var existingItem = cartItems.find(function(element, index) {
                //     if (element.id == product_id ) {
                //         // cartItems[index].quantity++;

                //         cartItems.splice(index, 1); 
                //         console.log(cartItems)
                //         __ecomm.setItem('cart_items', cartItems);
                //         Toast.fire({
                //             icon: 'success',
                //             title: 'Successfully added to cart!'
                //         })
                //         console.log('Entered 1');
                //     }
                //     return element.id == product_id;
                // });

                // console.log(existingItem)

                if (! cartItems) {
                    cartItems = [];
                }

                if (cartItems[product_id]) {
                    // cartItems[product_id].quantity++;
                    // delete cartItems[product_id]; 
                    cartItems.splice(product_id, 1)
                    __ecomm.setItem('cart_items', cartItems);
                }
                this.refreshCartItems();

                // var _this = this;
                // fetch('/api/cart/remove-item/' + product_id, {
                //     method: 'DELETE',
                //     headers: {
                //         'Accept': 'application/json, text/plain, */*',
                //         'Content-Type': 'application/json'
                //     },
                //     // body: JSON.stringify({
                //     //     : page,
                //     // })
                // }).then(res => res.json())
                //     .then(res => { _this.getCartDetails(); });
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
                        // _this.cart_items = res.cart_items; 
                        _this.cart_charges = res.cart_charges; 
                        _this.config = res.config;
                        _this.total = res.total;
                        _this.subtotal = res.subtotal;
                        _this.shipping_method = res.shipping_method;

                        // _this.cart_items = window.__ecomm.getItem('cart_items');
                        this.refreshCartItems();
                    });
            },
            monify(money) {
                // console.log(this.config);
                // return 1;
                // return this.config.currency.symbol + money;
                return money.toFixed(2);
            },
            gotoCheckout() {
                window.location.href = '/checkout';
            }
        },
        mounted() {
            window.onload = function() {
                var items = __ecomm.getItem('cart_items');
            
                if (! items || ! items.length) {
                    window.location.href = '/'
                }
            }
            document.querySelector('.cart-icon').setAttribute('href', '/cart');
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


import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
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
                selectedShippingMethod: '',
                shipping_method: {},
                shipping_methods: [],
                customer: {
                    firstname: '',
                    lastname: '',
                    email: '',
                    address: '',
                    city: '',
                    country: '',
                    region: '',
                    zipcode: '',
                    notes: '',
                },
                selectedCountry: '',
                selectedState: '',
                selectedStateCode: '',
                selectedCity: '',
                countries: [],
                states: [],
                cities: [],
                options2: [{
                    text: "name1",
                    value: "value1"
                }, {
                    text: "name2",
                    value: "value2"
                }, {
                    text: "name3",
                    value: "value3"
                }],
                result2: '',
                isLoading: false,
                fullPage: true
            }
        },
        methods: {
            removeItem(product_id) {
                var _this = this;
                var cartItems = __ecomm.getItem('cart_items', []);

                if (! cartItems) {
                    cartItems = [];
                }

                if (cartItems[product_id]) {
                    cartItems.splice(product_id, 1)
                    __ecomm.setItem('cart_items', cartItems);
                }
                this.refreshCartItems();
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
                        // _this.cart_items = res.cart_items; 
                        _this.cart_charges = res.cart_charges; 
                        _this.config = res.config;
                        _this.total = res.total;
                        _this.subtotal = res.subtotal;
                        // _this.shipping_method = res.shipping_method;

                        // _this.cart_items = window.__ecomm.getItem('cart_items');
                        this.refreshCartItems();
                    });
            },
            calculateTotals() {
                this.subtotal = 0.00;

                for(var item in this.cart_items) {
                    this.subtotal = this.subtotal + (this.cart_items[item].price * this.cart_items[item].quantity);
                }
                this.total = this.subtotal;

                if (this.shipping_method.shipping_amount) {
                    this.total += this.shipping_method.shipping_amount.amount;
                }

                this.subtotal = this.subtotal.toFixed(2);
                this.total = this.total.toFixed(2);
            },
            refreshCartItems() {
                this.cart_items = __ecomm.getItem('cart_items');
                this.calculateTotals();
            },
            monify(money) {
                // console.log(this.config);
                // return 1;
                // return this.config.currency.symbol + money;
                return money.toFixed(2);
            },
            getRates() {
                this.isLoading = true;
                var _this = this;
                fetch('/api/shipping/calculate', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        customer: _this.customer,
                        items: _this.cart_items,
                    })
                }).then(res => res.json())
                    .then(res => {
                        _this.isLoading = false;
                        _this.shipping_methods = ! res.errorCode ? res : [];
                        _this.selectedShippingMethod = '';
                        _this.shipping_method = {};
                        console.log(_this.shipping_methods);
                    });
            },
            setShippingMethod(e) {
                this.shipping_method = this.shipping_methods[this.selectedShippingMethod];
                this.calculateTotals();
            },
            placeOrder() {
                var _this = this;
                if (! this.shipping_method.shipping_amount) {
                    alert('Invalid Shipping Method');
                    return;
                }

                this.isLoading = true;
                fetch('/api/order', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        customer: _this.customer,
                        items: _this.cart_items,
                        charges: [
                            {
                                id: _this.shipping_method.service_code,
                                name: _this.shipping_method.service_type,
                                price: this.shipping_method.shipping_amount.amount
                            }
                        ],
                    })
                }).then(res => res.json())
                    .then(res => { 
                        this.isLoading = false;
                        if (res.uid) {
                            Swal.fire({
                                title: 'Order successfully placed!',
                                text: 'Do you want to continue',
                                icon: 'success',
                                confirmButtonText: 'Great!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    __ecomm.removeItem('cart_items')
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
            },
            getStates() {
                this.states = State.getStatesOfCountry(this.selectedCountry);
                this.customer.country = this.selectedCountry;
            },
            getCities(e) {
                console.log(e.target.querySelector('[value="' + e.target.value + '"]'));
                this.selectedState = e.target.querySelector('[value="' + e.target.value + '"]').dataset.state;
                this.customer.region = this.selectedState;
                // console.log(this.selectedState);
                this.cities = City.getCitiesOfState(this.selectedCountry, this.selectedStateCode);
            }
        },
        mounted() {
            window.onload = function() {
                var items = __ecomm.getItem('cart_items');
            
                if (! items || ! items.length) {
                    window.location.href = '/'
                }
            }

            document.querySelector('.cart-icon').setAttribute('href', '/cart');
            // this.getConfig();
            // this.getCartItems();
            this.getCartDetails();
            console.log(this.config);
            this.countries = Country.getAllCountries()/* [{
                label: 'Countries',
                options: Country.getAllCountries().map(function(element) {
                    return {
                        text: element.name,
                        value: element.isoCode
                    }
                })
            }]; */
        },
        components: {
            Cart, vSelect, Loading
        },
        watch: {
            'customer.city': {
                handler(newCity, oldCity) {
                // Note: `newValue` will be equal to `oldValue` here
                // on nested mutations as long as the object itself
                // hasn't been replaced.
                    if (newCity != '') {
                        this.getRates();
                    }
                },
                deep: true
            },
            'customer.zipcode': {
                handler(newZipcode, oldZipcode) {
                // Note: `newValue` will be equal to `oldValue` here
                // on nested mutations as long as the object itself
                // hasn't been replaced.
                    if (newZipcode != '' && this.customer.city != '' || newZipcode == '' && this.customer.city != '') {
                        this.getRates();
                    }
                },
                deep: true
            }        
            // city(newCity, oldCity) {
            //     if (newCity != '') {
            //         this.getRates();
            //     }
            // }
        },
    }).mount("#checkout");
}