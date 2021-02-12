import refractor from 'refractor/core.js'
import { Node } from 'tiptap'
import { toggleBlockType, setBlockType, textblockTypeInputRule } from 'tiptap-commands'
import Component from './Component'
import HighlightPlugin from './HighlightPlugin'

export default class CodeBlockHighlight extends Node {

    constructor(languages, theme) {
        super()

        require(`prismjs/themes/${theme}.css`)

        for (let language in languages) {
            refractor.register(require(`refractor/lang/${language}`))
        }

        this.languages = languages
    }

    get name() {
        return 'code_block'
    }

    get view() {
        return Component
    }

    get defaultOptions() {
        return {
            languages: []
        }
    }

    get schema() {
        return {
            attrs: {
                languages: {
                    default: this.languages
                },
                language: {
                    default: Object.keys(this.languages)[0]
                }
            },
            content: 'text*',
            marks: '',
            group: 'block',
            code: true,
            defining: true,
            draggable: false,
            parseDOM: [
                { tag: 'pre', preserveWhitespace: 'full' },
            ],
            toDOM: ({attrs}) => [
                'pre',
                ['code', {
                    class: 'language-' + attrs.language
                }, 0]
            ]
        }
    }

    commands({ type, schema }) {
        return () => toggleBlockType(type, schema.nodes.paragraph)
    }

    keys({ type }) {
        return {
            'Shift-Ctrl-\\': setBlockType(type),
        }
    }

    inputRules({ type }) {
        return [
            textblockTypeInputRule(/^```$/, type),
        ]
    }

    get plugins() {
        return [
            HighlightPlugin({ name: this.name }),
        ]
    }

}
