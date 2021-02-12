<template>
    <detail-field :field="field">
        <div v-if="field.value">
            <router-link v-if="value.link" :to="value.link"
                         class="text-blue-500 font-bold">
                {{ value.label || 'Не выбрано' }}
            </router-link>
            <div v-else :class="colorClasses">
                {{ value.label || 'Не выбрано' }}
            </div>
        </div>
        <div v-else>
            <i class="dripicons-minus text-gray-600" title="Значение отсутствует" />
        </div>
    </detail-field>
</template>

<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],
        computed: {
            value() {
                return this.field.options[this.field.value]
            },
            colorClasses() {
                return this.value.color ? [`text-${this.value.color}-500`, 'font-semibold'] : []
            }
        }
    }
</script>
