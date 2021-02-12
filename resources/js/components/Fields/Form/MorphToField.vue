<template>
    <form-field :field="field" :errors="errors">
        <v-select v-model="selectedType"
                  :options="types"
                  :searchable="false"
                  placeholder=""
                  :disabled="field.disabled"
                  @input="onTypeChange"
        >
        </v-select>

        <div v-if="selectedType">
            <v-select class="mt-8"
                      v-model="selectedId"
                      :options="options"
                      :searchable="false"
                      placeholder=""
                      :disabled="field.disabled"
                      @input="onIdChange"
            >
            </v-select>
        </div>

        <p class="text-red-500 text-xs italic pt-2" v-if="errors.has(field.attributeId)">
            {{ errors.get(field.attributeId) }}
        </p>
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

                selectedId: this.field.value.attributeId ? {
                    value: this.field.value.attributeId,
                    label: this.field.options[this.field.value.attributeType][this.field.value.attributeId]
                } : null,

                selectedType: this.field.value.attributeType ? {
                    value: this.field.value.attributeType,
                    label: this.field.types[this.field.value.attributeType]
                } : null,
            }
        },
        computed: {
            options() {
                let options = []
                const typeOptions = this.field.options[this.selectedType.value]

                for (let id in typeOptions) {
                    options.push({
                        label: typeOptions[id],
                        value: id
                    })
                }

                return options
            },

            types() {
                let types = []

                for (const type in this.field.types) {
                    types.push({
                        label: this.field.types[type],
                        value: type
                    })
                }

                return types
            }
        },

        methods: {
            fill(formData) {
                formData.append(this.field.attributeType, this.selectedType ? this.selectedType.value : '')
                formData.append(this.field.attributeId, this.selectedId ? this.selectedId.value : '')
            },
            onTypeChange() {
                this.selectedId = null
                this.errors.clear(this.field.attributeType)
            },
            onIdChange() {
                this.errors.clear(this.field.attributeId)
            },
            reset() {
                this.selectedId = null
                this.selectedType = null
            }
        }
    }
</script>

<style lang="scss">
    @import "~vue-select/src/scss/vue-select.scss";

    .error-border {
        border: solid 0.5px red;
        border-radius: 5px
    }
</style>
