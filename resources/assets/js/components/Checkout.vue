<template>
    <div class="w-full">
        <div class="border rounded">
            <div class="border-b bg-grey-lightest rounded-t text-grey-darker font-semibold px-4 py-3">Checkout</div>

            <div class="bg-white rounded-b text-center p-4 md:py-16">
                <div class="max-w-xs mx-auto">
                    <div>
                        <div v-for="item in items" class="w-full border rounded bg-grey-lightest px-4 py-3 mb-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="font-semibold" v-text="item.qty"></div>

                                <svg class="mx-2 w-2 h-2 text-grey-darker fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/></svg>

                                <div class="text-left" v-text="item.name"></div>
                            </div>

                            <div class="ml-4 font-mono text-grey-dark">${{ item.subtotal.format(2) }}</div>
                        </div>
                    </div>

                    <div class="my-8">Pay now with your credit or debit card</div>

                    <div class="w-full border rounded bg-grey-lightest p-4" ref="card"></div>

                    <transition fade name="fade">
                        <div v-if="error" class="mt-4 bg-red-lightest rounded p-3 text-sm text-left text-red flex items-center">
                            <svg class="flex-no-shrink w-4 h-4 text-red-light fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM9 5v6h2V5H9zm0 8v2h2v-2H9z"/></svg>

                            <span class="ml-3">{{ error }}</span>
                        </div>
                    </transition>
                </div>

                <button :disabled="buttonDisabled" :class="{ 'opacity-75 select-none pointer-events-none cursor-not-allowed': buttonDisabled }" class="mt-8 block mx-auto text-center bg-blue hover:bg-blue-dark flex items-center justify-center focus:outline-none text-white rounded px-6 py-3" @click.prevent="makePurchase">
                    <div v-text="processing ? 'Processing...' : 'Purchase'"></div>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    const stripe = Stripe(process.env.MIX_STRIPE_KEY)

    export default {
        props: ['items'],

        data() {
            return {
                card: undefined,
                buttonDisabled: true,
                processing: false,
                error: ''
            }
        },

        mounted() {
            const tailwindConfig = require('../../../../tailwind.js')

            this.card = stripe.elements().create('card', {
                style: {
                    base: {
                        fontFamily: tailwindConfig.fonts.sans.toString(),
                        color: tailwindConfig.colors['grey-darker']
                    }
                }
            })

            this.card.mount(this.$refs.card)

            this.card.addEventListener('change', (event) => {
                if (event.error) {
                    this.error = event.error.message

                    return
                }

                this.buttonDisabled = !event.complete;
                this.error = ''

                return
            })
        },

        methods: {
            makePurchase() {
                this.error = ''
                this.setWorking(true)

                stripe.createToken(this.card)
                    .then(({ token, error }) => {
                        if (error) {
                            this.error = error.message
                            this.setWorking(false)

                            return
                        }

                        axios.post(`/cart/checkout`, { token: token.id })
                            .then(({ data }) => window.location.href = `/orders/${data.id}`)
                            .catch(({ response }) => {
                                this.error = response.data.message
                                this.setWorking(false)
                            })
                    })
            },

            setWorking(value) {
                this.buttonDisabled = value
                this.processing = value
                this.card.update({ disabled: value })
            }
        }
    }
</script>
