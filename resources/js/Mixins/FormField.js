import Errors from "../utils/Errors";

export default {
    props: {
        field: {},
        errors: {
            type: Errors,
            default() {
                return new Errors
            }
        }
    },

    data() {
        return {
            value: ''
        }
    },

    created() {
        this.value = this.field.value || ''
    },

    mounted() {
        this.field.fill = this.fill
        this.field.set = this.set
        this.field.reset = this.reset
    },

    methods: {
        classes(locale = null) {
            const attribute = locale ? this.attribute(locale) : this.field.attribute

            return {
                'border-red-500': this.errors.has(attribute),
            }
        },

        attribute(locale) {
            return this.field.attribute + '.' + locale
        },

        fill(formData) {
            if (this.field.translatable) {
                _.each(this.value, (item, i) => {
                    formData.set(this.field.attribute + '[' + i + ']', item)
                })
            } else {
                formData.set(this.field.attribute, this.value || '')
            }
        },

        set(newValue) {
            if (this.field.translatable) {
                _.each(this.value, (item, i) => {
                    this.value[i] = newValue[i] || ''
                })
            } else {
                this.value = newValue
            }
        },

        reset() {
            this.field.set('')
        },

        fileChange() {
            this.value = this.$refs.fileField.files[0];
        },
    },

    watch: {
        errors: {
            deep: true,
            handler(errors) {
                if (!this.field.translatable) return

                for (let locale in this.$locales) {
                    if (errors.has(this.attribute(locale))) {
                        this.$refs.tabs.selectTab(locale)

                        break
                    }
                }
            }
        },
        value(value) {
            this.field.value = value
        }
    }
}
