<template>
    <icon name="checklist" :active="isActive.todo_list()"
        @click="commands.todo_list" class="mr-2" />
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
                ul[data-type="todo_list"] {
                    @apply pl-6;
                    li {
                        @apply list-none flex relative;
                        cursor: grab;

                        .todo-checkbox {
                            @apply absolute w-4 h-4 border border-gray-700 inset-y-0 my-auto cursor-pointer rounded;
                            left: -1.5rem;
                            user-select: none;

                            &:before {
                                content: '';
                                @apply absolute w-full h-full inset-0 m-auto transition-colors duration-300;
                            }
                        }

                        .todo-content {
                            p {
                                @apply relative;
                                &:before {
                                    content: '';
                                    @apply absolute left-0 inset-y-0 my-auto w-full bg-gray-700 transform origin-left
                                    scale-x-0 transition-transform duration-300;
                                    height: 2px;
                                }
                            }
                        }

                        &[data-done="true"] {
                            .todo-content {
                                p {
                                    &:before {
                                        @apply scale-x-100;
                                    }
                                }
                            }

                            .todo-checkbox {
                                &:before {
                                    @apply bg-gray-700;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
</style>
