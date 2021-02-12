<template>
    <icon @click="commands.horizontal_rule"
        class="mr-2 hr-btn">
        <i class="text-gray-700 inline-flex dripicons-minus"></i>
        <i class="text-gray-700 inline-flex dripicons-minus"></i>
    </icon>
</template>

<script>
    import Extension from './ExtensionMixin'

    export default {
        mixins: [Extension],
    }
</script>

<style lang="scss">
    .tiptap-field {
        .hr-btn {
            i:last-child {
                margin-left: -8px;
            }
        }
        .tiptap-input {
            .ProseMirror {
                hr {
                    border-width: 1px;
                }
            }
        }
    }
</style>
