<template>
    <button class="h-8 flex items-center justify-center rounded spinner"
            :class="classes"
            @click.prevent="$emit('click')">
        <slot>
            <i class="text-gray-700 inline-flex transition" :class="iconClasses" />
        </slot>
    </button>
</template>

<script>
    export default {
        props: {
            name: {},
            active: {},
            width: {
                type: String,
                default: '8'
            },
            loading: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            classes() {
                return [
                    `w-${this.width}`,
                    this.active ? 'bg-gray-300' : 'hover:bg-gray-200',
                    this.loading ? 'loading' : ''
                ]
            },
            iconClasses() {
                return [
                    `dripicons-${this.name}`,
                    this.loading ? 'opacity-25' : ''
                ]
            }
        }
    }
</script>

<style lang="scss" scoped>
    .spinner {
        &:after {
            @apply border-gray-800;
            border-bottom-color: transparent;
        }
    }
</style>
