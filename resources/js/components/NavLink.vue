<template>
    <li>
        <button @click="toggle" class="px-8 py-3 text-gray-200
                    flex items-center justify-between
                    cursor-pointer w-full xl:px-4">

            <span class="flex items-center">
                <i class="sidebar__icon xl:mr-2" :class="`dripicons-${icon}`"></i>
                <span class="text-sm text-left"><slot></slot></span>
                <i v-if="!to" :style="{ transform: isExpended ? 'rotate(90deg)' : '', transition: '0.1s'  }" class="dripicons-chevron-right inline-flex ml-1 text-sm"></i>
            </span>

        </button>
        <ul :style="{height: `${height}px`}" class="overflow-hidden h-0 sidebar__submenu"
            ref="submenu" v-if="!to">
            <slot name="submenu"></slot>
        </ul>
    </li>
</template>

<script>
    export default {
        props: {
            to: String,
            icon: String
        },
        data() {
            return {
                height: 0,
                expandedHeight: 0,
                isExpended: false,
            }
        },
        mounted() {
            if (!this.to) {
                this.expandedHeight = this.$refs.submenu.scrollHeight
            }
        },
        methods: {
            toggle(e) {
                this.isExpended = !this.isExpended
                if (!this.to) {
                    e.preventDefault()
                    this.height = this.height ? 0 : this.expandedHeight

                    return
                }

                this.$router.push(this.to)
            }
        }

    }
</script>

<style lang="scss" scoped>
    .sidebar {
        &__submenu {
            transition: all .3s ease;
            li {
                a {
                    padding-left: 4.5rem;
                    @apply text-gray-200 block text-base pr-5 py-2;
                }
            }
        }
    }
</style>
