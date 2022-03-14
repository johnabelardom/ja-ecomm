<x-store-layout>
    {{--
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>
    --}}

    <div id="checkout" class="container p-12 mx-auto">
        <div v-if="isLoading" class="fixed flex h-screen justify-center bg-gray-100 opacity-75 items-center z-20 inset-0 overflow-y-auto ease-out duration-400">.
            <div style="border-top-color: transparent" class="justify-center w-16 h-16 border-blue-400 border-solid rounded-full animate-spin"></div>
            <p>&nbsp; Loading...</p>
        </div>
        <div class="flex flex-col w-full px-0 mx-auto md:flex-row">
            <div class="flex flex-col md:w-full">
                <h2 class="mb-4 font-bold md:text-xl text-heading ">Shipping Address
                </h2>
                <form class="justify-center w-full mx-auto" v-on:submit="$event.preventDefault()">
                    <div class="">
                        <div class="space-x-0 lg:flex lg:space-x-4">
                            <div class="w-full lg:w-1/2">
                                <label for="firstName" class="block mb-3 text-sm font-semibold text-gray-500">First
                                    Name</label>
                                <input type="text" placeholder="First Name" wire:loading.attr="disabled" v-model="customer.firstname" name="firstname"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    @error('checkout_session.firstname') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                            </div>
                            <div class="w-full lg:w-1/2 ">
                                <label for="firstName" class="block mb-3 text-sm font-semibold text-gray-500">Last
                                    Name</label>
                                <input type="text" placeholder="Last Name" wire:loading.attr="disabled" v-model="customer.lastname" name="lastname"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    @error('checkout_session.lastname') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full">
                                <label for="Email" class="block mb-3 text-sm font-semibold text-gray-500">Email</label>
                                <input type="text" placeholder="Email" wire:loading.attr="disabled" v-model="customer.email" name="email"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    @error('checkout_session.email') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full">
                                <label for="Address" class="block mb-3 text-sm font-semibold text-gray-500">Address</label>
                                <textarea wire:loading.attr="disabled" v-model="customer.address" name="address"
                                    class="w-full px-4 py-3 text-xs border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    cols="20" rows="4" placeholder="Address"></textarea>
                                    @error('checkout_session.address') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                            </div>
                        </div>
                        <br>
                        <div class="space-x-0 lg:flex lg:space-x-4">
                            <!-- <div class="w-full lg:w-1/2">
                                <label for="city" class="block mb-3 text-sm font-semibold text-gray-500">City</label>
                                <input type="text" placeholder="City" wire:loading.attr="disabled" v-model="customer.city" name="city"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    @error('checkout_session.city') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                            </div> -->
                            <div class="w-full lg:w-1/2 ">
                                <label for="postcode" class="block mb-3 text-sm font-semibold text-gray-500">
                                    Postcode</label>
                                <input type="text" placeholder="Post Code" wire:loading.attr="disabled" v-model="customer.zipcode" name="zipcode"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    @error('checkout_session.zipcode') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="mt-4" v-if="customer.address != ''">
                            <div class="w-full">
                                <!-- <label for="country" class="block mb-3 text-sm font-semibold text-gray-500">Country</label>
                                <input type="text" placeholder="Country" wire:loading.attr="disabled" v-model="customer.country" name="country"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    @error('checkout_session.country') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror -->

                                <!-- <region-select v-model="country.region" :country="country" :region="region" /> -->
                                <select v-if="countries.length > 0" name="" id="" v-model="selectedCountry" class="w-full" v-on:change="getStates()" required>
                                    <option value="">Select Country...</option>
                                    <option v-bind:value="country.isoCode" v-for="(country, index) in countries" :key="index" v-text="country.name"></option>
                                </select>    
                                <select v-if="states.length > 0" name="" id="" v-model="selectedStateCode" class="w-full"  v-on:change="getCities($event)" required>
                                    <option value="">Select State...</option>
                                    <option v-bind:value="state.isoCode" v-for="(state, index) in states" :key="index" v-bind:data-state="state.name" v-text="state.name"></option>
                                </select>    
                                <select v-if="cities.length > 0" name="" id="" v-model="customer.city" class="w-full" required>
                                    <option value="">Select City...</option>
                                    <option v-bind:value="city.name" v-for="(city, index) in cities" :key="index" v-text="city.name"></option>
                                </select>    
                                                        
                                <!-- <vue-select2 class="vue-select2" name="select2"
                                        :options="options2" :model.sync="result2"
                                        :searchable="true" language="en-US">
                                </vue-select2> -->
                                <!-- <v-select label="text" :value="selectedCountry" :reduce="country => country.isoCode" :options="countries"></v-select> -->
                            </div>
                        </div>
                        <!-- <div class="flex items-center mt-4">
                            <label class="flex items-center text-sm group text-heading">
                                <input type="checkbox" wire:loading.attr="disabled" v-model="customer.firstname" name="firstname"
                                    class="w-5 h-5 border border-gray-300 rounded focus:outline-none focus:ring-1">
                                <span class="ml-2">Save this information for next time</span></label>
                        </div> -->
                        <div class="relative pt-3 xl:pt-6"><label for="note"
                                class="block mb-3 text-sm font-semibold text-gray-500"> Notes
                                (Optional)</label><textarea wire:loading.attr="disabled" v-model="customer.notes" name="notes"
                                class="flex items-center w-full px-4 py-3 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-600"
                                rows="4" placeholder="Notes for delivery"></textarea>
                                    @error('checkout_session.notes') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                        </div>
                        <div class="mt-4">
                            <button class="w-full px-6 py-2 text-blue-200 bg-blue-600 hover:bg-blue-900" @click="placeOrder">Place Order</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex flex-col w-full ml-0 lg:ml-12 lg:w-2/5">
                <div class="pt-12 md:pt-0 2xl:ps-4">
                    <h2 class="text-xl font-bold">Order Summary</h2>
                    <div class="mt-8">
                        <div class="flex flex-col space-y-4">
                            <div class="flex space-x-4 relative" v-for="(item, index) in cart_items" :key="index">
                                <div class="w-1/4">
                                    <img v-bind:src="item.image" alt="image" class="w-60">
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold" v-text="item.name"></h2>
                                    <!-- <p class="text-sm">Lorem ipsum dolor sit amet, tet</p> -->
                                    <span class="text-red-600 text-sm block">Price: <span v-text="config.currency.symbol + (item.price)"></span></span>
                                    <span class="text-red-600 text-sm block">Qty: <span v-text="(item.quantity)"></span></span>
                                </div>
                                <div class="absolute right-0" style="cursor:pointer" v-on:click="removeItem(item.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
                <div
                    class="flex items-center w-full py-4 text-sm font-semibold border-b border-gray-300 lg:py-5 lg:px-3 text-heading last:border-b-0 last:text-base last:pb-0">
                    Subtotal<span class="ml-2" v-if="config.currency" v-text="config.currency.symbol + subtotal"></span>
                </div>
                <br>
                <div class="pt-5 md:pt-0 2xl:ps-4" v-if="shipping_methods.length > 0">
                    <h2 class="text-xl font-bold">Shipping Method</h2>
                    <div class="mt-8">
                        <div class="flex flex-col space-y-4">
                            <div v-if="config.currency">
                                <label class="font-medium inline-block mb-3 text-sm uppercase">Shipping</label>
                                <!-- <select class="block p-2 text-gray-600 w-full text-sm" v-model="shipping_method">
                                    <option v-for="(method, index) in config.shipping_methods" :key="index" v-bind:value="index" v-text="method.name + ' - ' + config.currency.symbol + method.price"></option>
                                </select> -->
                                <select class="block p-2 text-gray-600 w-full text-sm" v-model="selectedShippingMethod" v-if="shipping_methods.length > 0" v-on:change="setShippingMethod($event)">
                                    <option value="">Please choose Shipping Option...</option>
                                    <option v-for="(method, index) in shipping_methods" :key="index" v-bind:disabled="! method.shipping_amount" v-bind:value="index" v-text=" method.shipping_amount ? method.service_type + ' - ' + method.shipping_amount.amount + method.shipping_amount.currency : method.service_type"></option>
                                </select>
                                <ul v-if="shipping_method.service_type">
                                    <li v-text="'Service: ' + shipping_method.service_type"></li>
                                    <li v-text="'Delivery: ' + shipping_method.carrier_delivery_days + ' days'"></li>
                                    <li v-text="'Fee: ' + shipping_method.shipping_amount.amount + shipping_method.shipping_amount.currency"></li>
                                    <li v-text="'Service: ' + shipping_method.service_type"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p class="text-bold text-gray-500 italic">Fill-up the address fields to show Shipping Options</p>
                </div>
                <!-- <div class="flex p-4 mt-4">
                    <h2 class="text-xl font-bold" v-text="'ITEMS ' + cart_items.length"></h2>
                </div> -->
                
                {{--
                @foreach($cart_charges_session as $css => $item)
                <div class="flex items-center w-full py-4 text-sm font-semibold border-b border-gray-300 lg:py-5 lg:px-3 text-heading last:border-b-0 last:text-base last:pb-0">
                    Shipping Tax
                    <span class="ml-2">{{ config.currency.symbol . $item['price'] }}</span>
                </div>
                @endforeach
                --}}
                <br>
                    <hr>
                <div
                    class="flex items-center w-full py-4 text-sm font-semibold border-b border-gray-300 lg:py-5 lg:px-3 text-heading last:border-b-0 last:text-base last:pb-0">
                    Total<span class="ml-2" v-if="config.currency" v-text="config.currency.symbol + total"></span>
                </div>
            </div>
        </div>
    </div>

    
    <script src="{{ mix('js/vues.js') }}"></script>
</x-store-layout>
