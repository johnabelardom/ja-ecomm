<x-store-layout>
    {{--
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>
    --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> 
                Thank you, your order has been placed.
            </div>
            <div class="flex items-center justify-center min-h-screen bg-gray-100">
            <livewire:receipt :order="$order"></livewire:receipt>
            <div class="p- mt-5 mb-5">
                <div class="flex items-center justify-center">
                    Thank you very much for doing business with us.
                </div>
                <!--  <div class="flex items-end justify-end space-x-3">
                    <button class="px-4 py-2 text-sm text-green-600 bg-green-100">Print</button>
                    <button class="px-4 py-2 text-sm text-blue-600 bg-blue-100">Save</button>
                    <button class="px-4 py-2 text-sm text-red-600 bg-red-100">Cancel</button>
                </div> -->
            </div>
        </div>
        </div>
    </div>
</x-store-layout>
