<template>
    <detail-field :field="field">
        <span v-if="field.value" class="text-sm text-gray-700 flex-grow">{{ formattedDate }}</span>
        <i v-else class="dripicons-minus text-gray-600" title="Дата отсутствует"/>
    </detail-field>
</template>

<script>
    import moment from 'moment'
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],
        computed: {
            formattedDate() {
                return moment(String(this.field.value)).isValid() ? moment(String(this.field.value)).format("MM/DD/YYYY hh:mm") : "Некорректная дата"
            }
        }

    }
</script>
