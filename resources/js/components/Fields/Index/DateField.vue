<template>
    <div class="flex justify-center pr-3">
        <span v-if="field.value" class="text-sm text-gray-700 flex-grow">{{ formattedDate }}</span>
        <i v-else class="dripicons-minus text-gray-600" title="Дата отсутствует"/>
    </div>
</template>

<script>
    import moment from 'moment'
    import IndexField from 'Vendor/js/Mixins/IndexField'

    export default {
        mixins: [IndexField],

        computed: {
            formattedDate() {
                return moment(String(this.field.value)).isValid() ? moment(String(this.field.value)).format("MM/DD/YYYY hh:mm") : "Некорректная дата"
            }
        }
    }
</script>
