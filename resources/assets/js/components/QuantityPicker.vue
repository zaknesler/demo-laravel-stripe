<template>
    <div class="flex">
        <div v-click-outside="hideDropdown" @click="toggleDropdown" class="relative flex items-center justify-between border border-r-0 px-3 py-2 h-full outline-none hover:border-grey focus:border-blue rounded-l cursor-pointer">
            <div class="mr-3" v-text="selected"></div>

            <svg class="flex-no-shrink w-4 h-4 text-grey fill-current pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>

            <div :class="{ hidden: !showDropdown }" style="top: 100%" class="mt-1 h-48 block absolute pin-l pin-t w-full rounded bg-white border overflow-hidden overflow-y-auto">
                <div :class="{ 'bg-blue text-white': (n == selected) }" class="px-3 py-1 hover:text-grey-darker hover:bg-grey-lighter w-full" v-for="n in max" @click="select(n)" v-html="n"></div>
            </div>
        </div>

        <button class="block appearance-none no-underline text-white text-sm bg-blue hover:bg-blue-dark focus:outline-none px-4 py-2 rounded-r" v-html="text"></button>

        <input type="hidden" :name="name" :value="selected" />
    </div>
</template>

<script>
    import ClickOutside from 'vue-click-outside'

    export default {
        props: {
            text: String,
            name: String,
            max: {
                default: 10,
                type: Number,
            },
            value: {
                default: 1,
                type: Number,
            },
        },

        mounted () {
            this.popupItem = this.$el

            this.selected = this.value
        },

        data() {
            return {
                showDropdown: false,
                selected: ''
            }
        },

        methods: {
            select(value) {
                this.selected = value
            },

            hideDropdown() {
                this.showDropdown = false
            },

            toggleDropdown() {
                this.showDropdown = !this.showDropdown
            }
        },

        directives: {
            ClickOutside
        }
    }
</script>
