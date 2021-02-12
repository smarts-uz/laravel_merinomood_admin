<template>
    <div>
        <editor-menu-bar :editor="editor" v-if="editable"
                         v-slot="{ commands, isActive }"
                         class="mb-2">
            <div class="flex">
                <component v-for="extension in uiExtensions"
                           :is="`editor-${extension.component}`"
                           :key="extension.component"
                           :field="field"
                           :extension="extension"
                           :commands="commands"
                           :editor="editor"
                           :is-active="isActive"
                           @updated="updateBubble"
                />
            </div>
        </editor-menu-bar>

        <editor-menu-bubble :editor="editor" class="relative"
                            v-slot="{ commands, isActive, getMarkAttrs, menu }"
                            v-for="(bubble, key) in bubbles" :key="key">
            <component
                :is="`bubble-${key}`"
                v-if="getMarkAttrs(key)"
                :editor="editor"
                :commands="commands"
                :is-active="isActive"
                :get-mark-attrs="getMarkAttrs"
                :menu="menu"
                :payload="bubble"
            />
        </editor-menu-bubble>

        <editor-content ref="editor"
                        :editor="editor"
                        class="tiptap-input input"
                        :class="{readonly: !editable}"/>
    </div>
</template>

<script>
    import {Editor, EditorContent, EditorMenuBar, EditorMenuBubble} from 'tiptap'
    import BubbleLink from './Bubbles/Link'
    import {Placeholder} from 'tiptap-extensions'

    export default {
        props: ['editable', 'field', 'content'],

        components: {
            EditorContent,
            EditorMenuBar,
            EditorMenuBubble,
            BubbleLink,
        },

        data() {
            return {
                editor: null,
                extensions: [],
                headlessExtensions: [
                    'trailing-node'
                ],
                bubbles: {
                    link: {}
                },
            }
        },

        computed: {
            uiExtensions() {
                return this.field.extensions.filter(
                    extension => !this.isHeadless(extension)
                )
            }
        },

        methods: {
            newEditor() {
                return new Editor({
                    extensions: [
                        ...this.extensions,
                        new Placeholder({
                            emptyEditorClass: 'is-editor-empty',
                            emptyNodeClass: 'is-empty',
                            emptyNodeText: 'Напишите что-нибудь …',
                            showOnlyWhenEditable: true,
                            showOnlyCurrent: true,
                        }),
                    ],
                    content: this.content,
                    editable: this.editable,
                    onBlur: () => {
                        this.$emit('update', this.editor.getHTML())
                    }
                })
            },

            registerExtensionButton(extension) {
                let filename = _.replace(_.startCase(extension.component), ' ', '')

                this.$options.components[`editor-${extension.component}`] = require(
                    `./Extensions/${filename}.vue`
                ).default
            },

            registerExtension(extension) {
                const f = require('./Extensions/extensions')[_.camelCase(extension.component)]

                this.extensions = [...this.extensions, ...f(extension)]
            },

            isHeadless(extension) {
                return this.headlessExtensions.includes(extension.component)
            },

            updateBubble(payload) {
                for (let item in payload.payload) {
                    this.$set(this.bubbles[payload.component], item, payload.payload[item])
                }
            },
        },

        mounted() {
            this.field.extensions.forEach(extension => {
                if (!this.isHeadless(extension)) {
                    this.registerExtensionButton(extension)
                }

                this.registerExtension(extension)
            })

            this.editor = this.newEditor()
        },

        beforeDestroy() {
            this.editor.destroy()
        },
    }
</script>
