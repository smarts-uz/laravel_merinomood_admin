<template>
    <label class="relative rounded cursor-pointer hover:bg-gray-200">
        <input type="file" class="absolute left-0 top-0 w-full h-full opacity-0"
               @change="fileUploaded">
        <span class="relative">
            <icon name="paperclip" :loading="loading" class="pointer-events-none" />
        </span>
    </label>
</template>

<script>
    import ExtensionMixin from './ExtensionMixin'

    export default {
        mixins: [ExtensionMixin],

        data() {
            return {
                loading: false
            }
        },

        methods: {
            async fileUploaded(event) {
                if (event.target.files.length === 0) {
                    return
                }

                let formData = new FormData
                formData.append('file', event.target.files[0])
                formData.append('draftId', this.field.draftId)
                formData.append('dir', this.dir)

                this.loading = true

                const {data: {attachment}} = await Admin.request().post('tiptap/files', formData)

                this.loading = false

                this.commands.attachment({
                    src: attachment.url,
                    title: attachment.attachment,
                    type: attachment.type
                })
            }
        }
    }
</script>

<style lang="scss">
    .tiptap-field {
        .tiptap-input {
            .ProseMirror {
                .attachment-file {
                    @apply text-gray-800 no-underline px-2 py-1 border rounded inline-flex my-1 leading-none align-middle;
                }

                .attachment-image {
                    @apply w-1/2;
                }
            }
        }
    }
</style>
