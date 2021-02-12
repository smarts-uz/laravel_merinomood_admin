import {coordsAtPos} from '../Extensions/functions'

export default {
    props: ['editor', 'commands', 'isActive', 'getMarkAttrs', 'menu', 'payload'],

    methods: {
        getPosition(position = {}, side = 'left') {
            position = {left: 0, top: 0, centered: false, ...position}

            const start = coordsAtPos(this.editor.view, this.editor.selection.from)
            const end = coordsAtPos(this.editor.view, this.editor.selection.to, true)
            const topShift = 10

            let el = this.$parent.$el

            if (!(el instanceof HTMLElement)) {
                return
            }

            el = el.getBoundingClientRect()

            let left = start.left

            if (side === 'center') {
                left = (start.left + end.left) / 2
            }

            if (side === 'right') {
                left = end.left
            }

            left = left - el.left

            const top = Math.round(end.bottom - el.top)

            return {
                left: (left + position.left) + 'px',
                top: (top + topShift + position.top) + 'px'
            }
        },

        selectedText() {
            const {selection, state} = this.editor
            const {from, to} = selection

            return state.doc.textBetween(from, to, ' ')
        },

        clearSelection() {
            const {selection: {to}} = this.editor

            this.editor.setSelection(to, to)
        }
    }
}
