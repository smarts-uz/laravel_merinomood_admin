<template>
    <icon name="view-list" :active="isActive.bullet_list()"
        @click="commands.bullet_list" class="mr-2" />
</template>

<script>
    import Extension from '../ExtensionMixin'

    export default {
        mixins: [Extension]
    }
</script>

<style lang="scss">
    .tiptap-field {
        .tiptap-input {
            .ProseMirror {
                ul {
                    li {
                        @apply list-disc;
                    }
                }
            }
        }
    }
</style>
