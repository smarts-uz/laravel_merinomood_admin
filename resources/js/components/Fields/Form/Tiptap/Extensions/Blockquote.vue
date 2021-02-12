<template>
    <icon name="message" :active="isActive.blockquote()"
        @click="commands.blockquote" class="mr-2" />
</template>

<script>
    import Extension from './ExtensionMixin'

    export default {
        mixins: [Extension],
    }
</script>

<style lang="scss">
    .tiptap-field {
        .tiptap-input {
            .ProseMirror {
                blockquote {
                    @apply pl-5 border-l-3 border-gray-500;
                }
            }
        }
    }
</style>
