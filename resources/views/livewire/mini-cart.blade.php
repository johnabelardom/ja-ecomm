<div class="pt-12 md:pt-0 2xl:ps-4">
    <x-spinner></x-spinner>
    <h2 class="text-xl font-bold">Order Summary</h2>
    <div class="mt-8">
        <div class="flex flex-col space-y-4">
            @foreach($cart_session as $cs => $item)
            <div class="flex space-x-4 relative">
                <div class="w-1/4">
                    <img src="{{ $item['image'] }}" alt="image" class="w-60">
                </div>
                <div>
                    <h2 class="text-xl font-bold">{{ $item['name'] }}</h2>
                    <!-- <p class="text-sm">Lorem ipsum dolor sit amet, tet</p> -->
                    <span class="text-red-600">Price</span> {{ $currency['symbol'] . ($item['price']) }}
                </div>
                <div class="absolute right-0" style="cursor:pointer" wire:click="removeItem({{ $cs }})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="flex p-4 mt-4">
        <h2 class="text-xl font-bold">ITEMS {{ count($cart_session) }}</h2>
    </div>
    <div
        class="flex items-center w-full py-4 text-sm font-semibold border-b border-gray-300 lg:py-5 lg:px-3 text-heading last:border-b-0 last:text-base last:pb-0">
        Subtotal<span class="ml-2">{{ $currency['symbol'] . $subtotal }}</span>
    </div>
    
    @foreach($cart_charges_session as $css => $item)
    <div class="flex items-center w-full py-4 text-sm font-semibold border-b border-gray-300 lg:py-5 lg:px-3 text-heading last:border-b-0 last:text-base last:pb-0">
        Shipping Tax
        <span class="ml-2">{{ $currency['symbol'] . $item['price'] }}</span>
    </div>
    @endforeach

    <div
        class="flex items-center w-full py-4 text-sm font-semibold border-b border-gray-300 lg:py-5 lg:px-3 text-heading last:border-b-0 last:text-base last:pb-0">
        Total<span class="ml-2">{{ $currency['symbol'] . $total }}</span>
    </div>
</div>