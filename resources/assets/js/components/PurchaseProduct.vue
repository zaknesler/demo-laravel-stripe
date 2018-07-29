<template>
    <div class="w-full">
        <div class="border rounded">
            <div class="border-b bg-grey-lightest rounded-t text-grey-darker font-semibold px-4 py-3">Payment</div>

            <div class="bg-white rounded-b text-center p-4 md:py-16">
                <div>Pay now with your credit or debit card</div>

                <div class="max-w-xs my-8 mx-auto">
                    <div class="w-full border rounded bg-grey-lightest px-4 py-3 mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="font-semibold">1</div>

                            <svg class="mx-2 w-2 h-2 text-grey-darker fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/></svg>

                            <div class="text-left">{{ product.name }}</div>
                        </div>

                        <div class="ml-4 font-mono text-grey-dark">${{ (product.price / 100).toFixed(2) }}</div>
                    </div>

                    <div class="w-full border rounded bg-grey-lightest p-4" ref="card"></div>

                    <transition fade name="fade">
                        <div v-if="error" class="mt-4 bg-red-lightest rounded p-3 text-sm text-left text-red flex items-center">
                            <svg class="flex-no-shrink w-4 h-4 text-red-light fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM9 5v6h2V5H9zm0 8v2h2v-2H9z"/></svg>

                            <span class="ml-3">{{ error }}</span>
                        </div>
                    </transition>
                </div>

                <button :class="{ 'opacity-75 select-none pointer-events-none': buttonDisabled, 'bg-green-dark hover:bg-green-dark': success, 'bg-blue hover:bg-blue-dark': !success }" class="block mx-auto w-32 h-10 text-center flex items-center justify-center focus:outline-none text-white rounded" @click.prevent="makePurchase">
                    <div v-if="pending && !success" class="mx-auto loader"></div>

                    <div v-else class="flex items-center justify-center">
                        <svg v-if="success" class="w-3 h-3 text-blue-lightest fill-current mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>

                        <span>{{ success ? 'Success' : 'Purchase' }}</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    const tailwindConfig = require('../../../../tailwind.js')

    let style = {
        base: {
            fontFamily: tailwindConfig.fonts.sans.toString(),
            color: tailwindConfig.colors['grey-darker']
        }
    }

    let stripe = Stripe(process.env.MIX_STRIPE_KEY),
        elements = stripe.elements()

    export default {
        props: ['product'],

        data() {
            return {
                card: undefined,

                buttonDisabled: true,

                pending: false,
                success: false,

                error: ''
            }
        },

        mounted() {
            this.card = elements.create('card', { style })
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

                        axios.post(`/products/${this.product.id}/purchase`, {token: token.id})
                            .then(({ data }) => {
                                this.success = true

                                console.log(data)
                            })
                            .catch(({ response }) => {
                                this.error = response.data.message
                                this.setWorking(false)
                                this.success = false
                            })
                    })
            },

            setWorking(value) {
                this.buttonDisabled = value
                this.pending = value
                this.card.update({ disabled: value })
            }
        }
    }
</script>
