<template>
    <icon name="code" :active="isActive.code_block()"
        @click="commands.code_block" class="mr-2" />
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
                p > code {
                    @apply bg-gray-300 text-sm font-semibold p-1 rounded;
                }
            }
        }
    }
</style>
