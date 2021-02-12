<template>
    <div class="relative flex">
        <pre
            :class="`language-${node.attrs.language}`"
            :data-lang="node.attrs.language"
            spellcheck="false"
        >
            <code ref="content"
                  :class="`language-${node.attrs.language}`"
            >
            </code>
        </pre>
        <div class="absolute right-0 mr-3 rounded shadow-lg lang-chooser-wrap"
             contenteditable="false" v-if="view.editable">
            <v-select class="lang-chooser"
                      :options="languages"
                      :value="selectedLanguage"
                      :clearable="false"
                      placeholder="Language"
                      @input="setLanguage"/>
        </div>
    </div>
</template>

<script>
    import VSelect from 'vue-select'

    export default {
        components: {
            VSelect
        },

        props: ['node', 'updateAttrs', 'view', 'options'],

        computed: {
            languages() {
                let languages = []

                for (let language in this.node.attrs.languages) {
                    languages.push({
                        value: language,
                        label: this.node.attrs.languages[language]
                    })
                }

                return languages
            },
            selectedLanguage() {
                return {
                    value: this.node.attrs.language,
                    label: this.node.attrs.languages[this.node.attrs.language]
                }
            }
        },

        methods: {
            setLanguage(language) {
                if (language) {
                    language = language.value
                }

                this.updateAttrs({
                    language
                })
            },
        },
    }
</script>

<style lang="scss">
    $vs-dropdown-min-width: theme('width.40');
    $vs-dropdown-max-height: 200px;
    @import "~vue-select/src/scss/vue-select";

    .tiptap-field {
        .tiptap-input {
            .ProseMirror {
                pre {
                    white-space: normal;
                    @apply flex rounded-lg w-full;
                    code {
                        @apply w-full;
                    }
                }

                .lang-chooser-wrap {
                    min-width: $vs-dropdown-min-width;
                    top: 15px;
                }

                .lang-chooser {
                    white-space: normal;
                    z-index: 50;

                    ul {
                        padding: 0;
                    }

                    .vs__search::placeholder,
                    .vs__dropdown-toggle,
                    .vs__dropdown-menu {
                        @apply bg-gray-200 text-gray-600 border-gray-300;
                    }

                    .vs__dropdown-option, .vs__selected {
                        @apply text-gray-600;
                    }

                    .vs__dropdown-option {
                        &:hover, &.vs__dropdown-option--highlight {
                            @apply bg-gray-700 text-white;
                        }
                    }
                }
            }
        }
    }
</style>
