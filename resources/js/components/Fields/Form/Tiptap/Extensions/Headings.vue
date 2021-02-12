<template>
    <div class="flex">
        <icon v-for="heading in headings" :key="heading" class="mr-2"
              :active="isActive.heading({ level: heading })"
              @click="commands.heading({ level: heading })">
            <span class="text-gray-700 inline-flex leading-none uppercase">
                h{{ heading }}
            </span>
        </icon>
    </div>
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
                h1, h2, h3, h4, h5, h6 {
                    @apply font-semibold;
                }

                h1 {
                    @apply text-3xl;
                }

                h2 {
                    @apply text-2xl;
                }

                h3 {
                    @apply text-xl;
                }
            }
        }
    }
</style>
