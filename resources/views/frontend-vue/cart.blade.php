<x-store-layout>
    {{--
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>
    --}}


    <div id="cart" class="container mx-auto mt-10">
        <div wire:loading.flex class="fixed flex h-screen justify-center bg-gray-100 opacity-75 items-center z-20 inset-0 overflow-y-auto ease-out duration-400">.
            <div style="border-top-color: transparent" class="justify-center w-16 h-16 border-blue-400 border-solid rounded-full animate-spin"></div>
            <p>&nbsp; Loading...</p>
        </div>
        <div class="flex shadow-md my-10">
            <div class="w-3/4 bg-white px-10 py-10">
                <div class="flex justify-between border-b pb-8">
                    <h1 class="font-semibold text-2xl">Shopping Cart</h1>
                    <h2 class="font-semibold text-2xl" v-text="cart_items.length + 'Items'"></h2>
                </div>
                <div class="flex mt-10 mb-5">
                    <h3 class="font-semibold text-gray-600 text-xs uppercase w-2/5">Product Details</h3>
                    <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center">Quantity</h3>
                    <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center">Price</h3>
                    <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center">Total</h3>
                </div>

                <div v-if="config.currency" class="flex items-center hover:bg-gray-100 -mx-8 px-6 py-5" v-for="(item, index) in cart_items" :key="index">
                    <Cart  v-bind:currency="config.currency" v-bind:cart-item="item"  v-bind:index="index"></Cart>
                </div>

                <a href="/vue" class="flex font-semibold text-indigo-600 text-sm mt-10">

                    <svg class="fill-current mr-2 text-indigo-600 w-4" viewBox="0 0 448 512">
                        <path
                            d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z" />
                        </svg>
                    Continue Shopping
                </a>
            </div>

            <div id="summary" class="w-1/4 px-8 py-10">
                <h1 class="font-semibold text-2xl border-b pb-8">Order Summary</h1>
                <div class="flex justify-between mt-10 mb-5">
                    <span class="font-semibold text-sm uppercase" v-text="'Items ' + Object.keys(cart_items).length"></span>
                    <span class="font-semibold text-sm" v-text="monify(subtotal)"></span>
                </div>
                <div v-if="config.currency">
                    <label class="font-medium inline-block mb-3 text-sm uppercase">Shipping</label>
                    <select class="block p-2 text-gray-600 w-full text-sm" v-model="shipping_method" v-on:change="updateCartCharges($event.target.value)">
                        <option v-for="(method, index) in config.shipping_methods" :key="index" v-bind:value="index" v-text="method.name + ' - ' + config.currency.symbol + method.price"></option>
                    </select>
                </div>
                <!-- <div class="py-10">
                    <label for="promo" class="font-semibold inline-block mb-3 text-sm uppercase">Promo Code</label>
                    <input type="text" id="promo" placeholder="Enter your code" class="p-2 text-sm w-full">
                </div>
                <button class="bg-red-500 hover:bg-red-600 px-5 py-2 text-sm text-white uppercase">Apply</button> -->
                <div class="border-t mt-8" v-if="Object.keys(cart_items).length > 0">
                    <div class="flex font-semibold justify-between py-6 text-sm uppercase">
                        <span>Total cost</span>
                        <span v-text="monify(total)"></span>
                    </div>
                    <button @click="gotoCheckout()"
                        class="bg-indigo-500 font-semibold hover:bg-indigo-600 py-3 text-sm text-white uppercase w-full">Checkout</button>
                </div>
            </div>

        </div>
    </div>

    
    <script src="{{ mix('js/vues.js') }}"></script>
</x-store-layout>
