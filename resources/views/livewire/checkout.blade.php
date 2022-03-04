<div class="container p-12 mx-auto">
    <div class="flex flex-col w-full px-0 mx-auto md:flex-row">
        <div class="flex flex-col md:w-full">
            <h2 class="mb-4 font-bold md:text-xl text-heading ">Shipping Address
            </h2>
            <form class="justify-center w-full mx-auto" wire:submit.prevent action>
                <div class="">
                    <div class="space-x-0 lg:flex lg:space-x-4">
                        <div class="w-full lg:w-1/2">
                            <label for="firstName" class="block mb-3 text-sm font-semibold text-gray-500">First
                                Name</label>
                            <input type="text" placeholder="First Name" wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.firstname" wire:change="updateCheckoutSession('firstname')" name="firstname"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                @error('checkout_session.firstname') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                        </div>
                        <div class="w-full lg:w-1/2 ">
                            <label for="firstName" class="block mb-3 text-sm font-semibold text-gray-500">Last
                                Name</label>
                            <input type="text" placeholder="Last Name" wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.lastname" wire:change="updateCheckoutSession('lastname')" name="lastname"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                @error('checkout_session.lastname') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full">
                            <label for="Email" class="block mb-3 text-sm font-semibold text-gray-500">Email</label>
                            <input type="text" placeholder="Email" wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.email" wire:change="updateCheckoutSession('email')" name="email"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                @error('checkout_session.email') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full">
                            <label for="country" class="block mb-3 text-sm font-semibold text-gray-500">Country</label>
                            <input type="text" placeholder="Country" wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.country" wire:change="updateCheckoutSession('country')" name="country"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                @error('checkout_session.country') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full">
                            <label for="Address" class="block mb-3 text-sm font-semibold text-gray-500">Address</label>
                            <textarea wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.address" wire:change="updateCheckoutSession('address')" name="address"
                                class="w-full px-4 py-3 text-xs border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600"
                                cols="20" rows="4" placeholder="Address"></textarea>
                                @error('checkout_session.address') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                        </div>
                    </div>
                    <div class="space-x-0 lg:flex lg:space-x-4">
                        <div class="w-full lg:w-1/2">
                            <label for="city" class="block mb-3 text-sm font-semibold text-gray-500">City</label>
                            <input type="text" placeholder="City" wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.city" wire:change="updateCheckoutSession('city')" name="city"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                @error('checkout_session.city') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                        </div>
                        <div class="w-full lg:w-1/2 ">
                            <label for="postcode" class="block mb-3 text-sm font-semibold text-gray-500">
                                Postcode</label>
                            <input type="text" placeholder="Post Code" wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.zipcode" wire:change="updateCheckoutSession('zipcode')" name="zipcode"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded lg:text-sm focus:outline-none focus:ring-1 focus:ring-blue-600">
                                @error('checkout_session.zipcode') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                        </div>
                    </div>
                    <!-- <div class="flex items-center mt-4">
                        <label class="flex items-center text-sm group text-heading">
                            <input type="checkbox" wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.firstname" wire:change="updateCheckoutSession('firstname')" name="firstname"
                                class="w-5 h-5 border border-gray-300 rounded focus:outline-none focus:ring-1">
                            <span class="ml-2">Save this information for next time</span></label>
                    </div> -->
                    <div class="relative pt-3 xl:pt-6"><label for="note"
                            class="block mb-3 text-sm font-semibold text-gray-500"> Notes
                            (Optional)</label><textarea wire:loading.attr="disabled" wire:model.debounce.500ms="checkout_session.notes" wire:change="updateCheckoutSession('notes')" name="notes"
                            class="flex items-center w-full px-4 py-3 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-600"
                            rows="4" placeholder="Notes for delivery"></textarea>
                                @error('checkout_session.notes') <span class="text-red-500">{{ str_replace('checkout session.', '', $message) }}</span>@enderror
                    </div>
                    <div class="mt-4">
                        <button class="w-full px-6 py-2 text-blue-200 bg-blue-600 hover:bg-blue-900" wire:click="placeOrder">Place Order</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex flex-col w-full ml-0 lg:ml-12 lg:w-2/5">
            <livewire:mini-cart></livewire:mini-cart>
        </div>
    </div>
</div>