<template>
    <div class="flex">
        <component v-for="list in lists"
                   :is="list"
                   :key="list"
                   :extension="extension"
                   :commands="commands"
                   :is-active="isActive"
                   class="mr-2"/>
    </div>
</template>

<script>
    import Extension from './ExtensionMixin'
    import Unordered from './Lists/Unordered'
    import Ordered from './Lists/Ordered'
    import Todo from './Lists/Todo'

    export default {
        mixins: [Extension],

        components: {
            Unordered,
            Ordered,
            Todo
        }
    }
</script>

<style lang="scss">
    .tiptap-field {
        .tiptap-input {
            .ProseMirror {
                ul, ol {
                    @apply pl-5;
                }
            }
        }
    }
</style>
