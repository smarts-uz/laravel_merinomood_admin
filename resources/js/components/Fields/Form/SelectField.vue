<template>
    <form-field :field="field" :errors="errors">
        <v-select v-model="selected"
                  :options="options"
                  :searchable="false"
                  :placeholder="field.label"
                  :disabled="field.disabled"
                  @input="onChange"
        >
        </v-select>
    </form-field>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'
    import VSelect from 'vue-select'

    export default {
        mixins: [FormFieldMixin],

        components: {
            VSelect
        },

        data() {
            return {
                checkbox: '',
                show: false,
                disabled: false,
                selected: this.field.value ? {
                    value: this.field.value,
                    label: this.field.options[this.field.value].label
                } : null
            }
        },
        computed: {
            options() {
                let options = []

                for (const [value, label] of Object.entries(this.field.options)) {
                    options.push({
                        label : label.label,
                        value
                    })
                }
                return options
            }
        },

        methods: {
            fill(formData) {
                formData.append(this.field.attribute, this.selected ? this.selected.value : '')
            },
            onChange() {
                this.$emit('change', this.selected)
                this.errors.clear(this.field.attribute)
            },
            reset() {
                this.selected = null
            }
        },

        watch: {
            selected(value) {
                this.field.value = value.value
            }
        }
    }
</script>

<style lang="scss">
    @import "~vue-select/src/scss/vue-select";

    .error-border {
        border: solid 0.5px red;
        border-radius: 5px
    }
</style>
