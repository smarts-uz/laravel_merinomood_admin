import {Link as TiptapLink} from 'tiptap-extensions'
import {updateMark, removeMark, markInputRule} from 'tiptap-commands'
import {getMarkRange} from 'tiptap-utils'
import {Plugin, TextSelection} from 'tiptap'

export default class Link extends TiptapLink {
    get schema() {
        return {
            attrs: {
                href: {
                    default: null,
                },
                text: {
                    default: '',
                },
            },
            inclusive: false,
            parseDOM: [
                {
                    tag: 'a[href]',
                    getAttrs: dom => ({
                        href: dom.getAttribute('href'),
                    })
                },
            ],
            toDOM: node => {
                return ['a', {
                    ...this.attrs(node.attrs),
                    target: '_blank',
                    rel: 'noopener noreferrer nofollow',
                }, 0]
            },
        }
    }

    commands({type}) {
        return attrs => {
            if (attrs.href) {
                return updateMark(type, attrs)
            }

            return removeMark(type)
        }
    }

    attrs(attrs) {
        attrs = {...attrs}
        delete attrs.text

        return attrs
    }

    inputRules ({ type }) {
        return [
            markInputRule(
                /(https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\\+.~#?&//=]*))\s$/g,
                type,
                matches => {
                    return { href: matches[1] }
                }
            ),
        ]
    }

    get plugins() {
        return [
            new Plugin({
                props: {
                    handleClick(view, pos) {
                        const { schema, doc, tr } = view.state
                        const range = getMarkRange(doc.resolve(pos), schema.marks.link)

                        if (!range) {
                            return
                        }

                        const $start = doc.resolve(range.from)
                        const $end = doc.resolve(range.to)
                        const transaction = tr.setSelection(new TextSelection($start, $end))

                        view.dispatch(transaction)
                    },
                },
            }),
        ]
    }
}
