<template>
    <component :is="wrapper" v-bind="fieldProps" class="tiptap-field" :class="{editable}">
        <template v-slot:translatable>
            <Tabs ref="tabs">
                <div v-for="(item, locale) in $locales" :class="{'pt-1': !editable}">
                    <Tab :locale="{key: locale, label: item}">

                        <editor-view
                            :editable="editable"
                            :field="field"
                            :content="field.value[locale]"
                            @update="updateContent($event, locale)"
                        />

                        <p class="text-red-500 text-xs italic" v-if="errors.has(attribute(locale))">
                            {{errors.get(attribute(locale)) }}
                        </p>
                    </Tab>
                </div>
            </Tabs>
        </template>

        <editor-view
            :editable="editable"
            :field="field"
            :content="field.value"
            @update="updateContent"
        />
    </component>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'
    import Tabs from 'Vendor/js/components/Tabs'
    import Tab from 'Vendor/js/components/Tab'
    import EditorView from './Editor'

    export default {
        mixins: [FormFieldMixin],

        props: {
            editable: {
                type: Boolean,
                default: true
            },

            wrapper: {
                type: String,
                default: 'form-field'
            }
        },

        components: {
            Tabs,
            Tab,
            EditorView
        },

        computed: {
            fieldProps() {
                let props = {
                    field: this.field
                }

                if (this.editable) {
                    props.errors = this.errors
                }

                return props
            },
        },

        methods: {
            fill(formData) {
                if (this.field.translatable) {
                    _.each(this.value, (value, locale) => {
                        formData.set(this.field.attribute + '[' + locale + ']', value)
                    })
                } else {
                    formData.set(this.field.attribute, this.value || '')
                }

                formData.set(this.field.attribute + '_draft', this.field.draftId)
            },
            updateContent(content, locale = null) {
                if (locale) {
                    this.$set(this.value, locale, content)
                } else {
                    this.value = content
                }
            }
        },
    }
</script>

<style lang="scss">
    .tiptap-field {
        .tiptap-input {
            @apply p-0;
            .ProseMirror {
                @apply p-5 overflow-scroll;

                &:focus {
                    @apply outline-none;
                }
            }

            &.readonly {
                @apply border-none shadow-none;
                .ProseMirror {
                    @apply p-0;
                }
            }
        }

        p.is-editor-empty:first-child::before {
            @apply text-gray-500;
            content: attr(data-empty-text);
            float: left;
            pointer-events: none;
            height: 0;
            font-style: italic;
        }

        &.editable {
            .tiptap-input {
                .ProseMirror {
                    min-height: 10rem;
                }
            }
        }
    }

</style>
