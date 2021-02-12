function headings(extension) {
    const {Heading} = require('tiptap-extensions')

    return [new Heading({levels: extension.value.headings})]
}

function textFormatting(extension) {
    let extensions = []

    extension.value.emphases.forEach(emphasis => {
        if (emphasis === 'link') {
            const Link = require('./Marks/Link').default
            extensions.push(new Link({openOnClick: false}))
        } else {
            const component = require('tiptap-extensions')[_.capitalize(emphasis)]

            extensions.push(new component)
        }
    })

    return extensions
}

function lists(extension) {
    let extensions = []

    const extensionsMappings = {
        unordered: ['BulletList', 'ListItem'],
        ordered: ['OrderedList', 'ListItem'],
        todo: ['TodoList', 'TodoItem']
    }

    extension.value.lists.forEach(list => {
        extensionsMappings[list].forEach(component => {
            component = require('tiptap-extensions')[component]

            extensions.push(new component)
        })
    })

    return extensions
}

function blockquote(extension) {
    const {Blockquote} = require('tiptap-extensions')

    return [new Blockquote]
}

function code(extension) {
    const {Code, CodeBlock} = require('tiptap-extensions')
    const CodeBlockHighlight = require('./CodeBlockHighlight').default

    return [
        new Code,
        new CodeBlock,
        new CodeBlockHighlight(extension.value.languages, extension.value.theme)
    ]
}

function divider(extension) {
    const {HorizontalRule} = require('tiptap-extensions')

    return [new HorizontalRule]
}

function table(extension) {
    const {Table, TableHeader, TableCell, TableRow} = require('tiptap-extensions')

    return [
        new Table({resizable: true}),
        new TableHeader,
        new TableCell,
        new TableRow
    ]
}

function attachment(extension) {
    const Attachment = require('./Nodes/Attachment').default
    const {Link} = require('tiptap-extensions')

    return [new Attachment, new Link({openOnClick: false})]
}

function trailingNode(extension) {
    const {TrailingNode} = require('tiptap-extensions')

    return [
        new TrailingNode({
            node: 'paragraph',
            notAfter: ['paragraph'],
        })
    ];
}

export {
    headings,
    textFormatting,
    lists,
    blockquote,
    code,
    divider,
    table,
    attachment,
    trailingNode
}
