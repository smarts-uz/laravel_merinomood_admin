<template>
    <div class="flex">
        <component v-for="emphasis in emphases"
                   :is="`editor-${emphasis}`"
                   :key="emphasis"
                   :extension="extension"
                   :editor="editor"
                   :commands="commands"
                   :is-active="isActive"
                   class="mr-2"
                   @updated="$emit('updated', $event)"
        />
    </div>
</template>

<script>
    import Extension from './ExtensionMixin'
    import EditorBold from './TextFormatting/Bold'
    import EditorItalic from './TextFormatting/Italic'
    import EditorStrike from './TextFormatting/Strike'
    import EditorUnderline from './TextFormatting/Underline'
    import EditorLink from './TextFormatting/Link'

    export default {
        mixins: [Extension],

        components: {
            EditorBold,
            EditorItalic,
            EditorStrike,
            EditorUnderline,
            EditorLink
        },
    }
</script>
