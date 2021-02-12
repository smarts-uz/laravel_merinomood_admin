<template>
    <div>
        <form-field :field="field" :errors="errors">
            <input
                v-model="value"
                type="password"
                class="input appearance-none block w-full leading-tight focus:outline-none focus:bg-white"
                :class="classes()"
                :id="field.attribute"
            >
        </form-field>

        <form-field :field="confirmationField" :errors="errors" v-if="field.confirmation">
            <input
                v-model="confirmationField.value"
                type="password"
                class="input appearance-none block w-full leading-tight focus:outline-none focus:bg-white"
                :class="classes()"
                :id="confirmationField.attribute"
            >
        </form-field>

    </div>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'

    export default {
        mixins: [FormFieldMixin],

        data() {
            return {
                confirmationField: {
                    ...this.field,
                    attribute: this.field.attribute + '_confirmation',
                    label: this.field.confirmation
                }
            }
        },

        methods: {
            fill(formData) {
                formData.set(this.field.attribute, this.value || '')

                if (this.field.confirmation) {
                    formData.set(this.confirmationField.attribute, this.confirmationField.value || '')
                }
            },
            reset() {
                this.set('')
                this.confirmationField.value = ''
            }
        }
    }
</script>
