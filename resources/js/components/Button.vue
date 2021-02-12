<template>
    <button type="submit"
            class="text-sm bg-purple-600 hover:bg-purple-800
                    flex items-center text-white font-bold
                    py-3 px-4 leading-none ml-5 rounded xs:ml-0 xs:mb-4
                    spinner" :class="{loading}" @click="$emit('click')">
        <i :class="`dripicons-${icon} mr-1`"></i><slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            loading: {
                type: Boolean,
                default: false
            },
            icon: {
                type: String,
                default: 'document-new'
            }
        }
    }
</script>
