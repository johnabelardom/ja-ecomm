<template>
    <div class="flex w-full items-center">
        <div class="flex w-2/5">
            <!-- product -->
            <div class="w-20">
                <img class="h-24" v-bind:src="cartItem.image"
                    alt="">
            </div>
            <div class="flex flex-col justify-between ml-4 flex-grow">
                <span class="font-bold text-sm" v-text="cartItem.name"></span>
                <!-- <span class="text-red-500 text-xs">Apple</span> -->
                <a href="#" @click="$parent.removeItem(index)" class="font-semibold hover:text-red-500 text-gray-500 text-xs">Remove</a>
            </div>
        </div>
        <div class="flex justify-center w-1/5">

            <button @click="$parent.decreaseItem(index)" type="button" v-bind:disabled="cartItem.quantity == 1">
                <svg class="fill-current text-gray-600 w-3" viewBox="0 0 448 512">
                    <path d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" />
                </svg>
            </button>
            

            <input class="mx-2 border text-center w-8" style="min-width: 50px" type="text" readonly v-model="cartItem.quantity">

            <button @click="$parent.increaseItem(index)" type="button">
                <svg class="fill-current text-gray-600 w-3" viewBox="0 0 448 512">
                    <path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" />
                </svg>
            </button>
            
        </div>
        <span class="text-center w-1/5 font-semibold text-sm" v-text="currency.symbol + (cartItem.price).toFixed(2)"></span>
        <span class="text-center w-1/5 font-semibold text-sm" v-text="currency.symbol + (cartItem.price * cartItem.quantity).toFixed(2)"></span>
    </div>
</template>

<script>
    export default {
        props: ['cartItem', 'currency', 'index'],
        mounted() {
            console.log('Component mounted.')
        },
    }
</script>
