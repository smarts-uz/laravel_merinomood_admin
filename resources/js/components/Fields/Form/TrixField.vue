<template>
    <form-field :field="field" :errors="errors">
        <template v-slot:translatable>
            <Tabs ref="tabs">
                <div v-for="(item, locale) in $locales">
                    <Tab :locale="{key: locale, label: item}">
                        <input :id="`${field.attribute}_${locale}`" :value="value[locale]"  type="hidden" name="content">
                        <trix-editor
                            :ref="`editor_${locale}`"
                            @keydown.stop
                            @trix-change="handleChange(locale)"
                            @trix-initialize="initialize(locale)"
                            @trix-attachment-add="handleAddFile"
                            @trix-file-accept="handleFileAccept"
                            :placeholder="placeholder"
                            class="trix-content"
                            :input="`${field.attribute}_${locale}`"/>
                        <p class="text-red-500 text-xs italic" v-if="errors.has(attribute(locale))">
                            {{errors.get(attribute(locale)) }}
                        </p>
                    </Tab>
                </div>
            </Tabs>
        </template>
    </form-field>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'
    import Tabs from 'Vendor/js/components/Tabs'
    import Tab from 'Vendor/js/components/Tab'
    import trix from 'trix';
    import 'trix/dist/trix.css'

    Vue.config.ignoredElements = [
        'trix-editor', // For ignoring console trix-editor warning (official Trix problem)
    ];
    export default {
        mixins: [FormFieldMixin],
        components: {Tab, Tabs},
        data(){
            return{
                name: '',
                placeholder: 'Введите текст. Можно перетащить сюда картинку или видео',
                withFiles: true,
            }
        },
        methods: {
            initialize(locale) {
                // this.$refs[`editor_${locale}`][0].editor.insertHTML(this.value[locale])
            },

            handleChange(locale) {
                this.value[locale] = this.$refs[`editor_${locale}`][0].value
                this.errors.clear(this.field.attribute + "." + locale)
            },

            handleFileAccept(e) {
                if (!this.withFiles) {
                    e.preventDefault()
                }
            },

            handleAddFile({ attachment }) {
                if (attachment.file) {
                    console.log(attachment.file)
                    this.uploadAttachment(attachment)
                }
            },

            // handleRemoveFile({ attachment: { attachment } }) {
            //     axios.post(
            //         'trix/delete', {
            //             params: { attachmentUrl: attachment.attributes.values.url },
            //         })
            //         .then(response => {})
            //         .catch(error => {})
            // },

            uploadAttachment(attachment) {
                const data = new FormData()
                data.append('Content-Type', attachment.file.type)
                data.append('file', attachment.file)
                // data.append('draftId', this.draftId)

                axios.post(
                    'trix/add',
                    data,
                    {
                        onUploadProgress: function(progressEvent) {

                            attachment.setUploadProgress(
                                Math.round((progressEvent.loaded * 100) / progressEvent.total)
                            )
                        },
                    }
                )
                    .then(({ data: { url } }) => {
                        return attachment.setAttributes({
                            url: url,
                            href: url,
                        })
                    })
            },
        },
    }
</script>

<style lang="scss">
    .trix-content {
        .attachment {
            @apply w-1/2 block m-auto;
        }
    }
</style>
