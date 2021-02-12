<template>
    <icon name="link" :active="isActive.link()"
          @click="update({popup: true})" />
</template>

<script>
    import Extension from '../ExtensionMixin'

    export default {
        mixins: [Extension],
    }
</script>

<style lang="scss">
    .tiptap-field {
        .tiptap-input {
            .ProseMirror {
                a {
                    @apply text-blue-600 underline;
                }
            }
        }
    }
</style>
