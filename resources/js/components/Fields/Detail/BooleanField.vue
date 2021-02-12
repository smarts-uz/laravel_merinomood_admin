<template>
    <detail-field :field="field">
        <div class="w-3 h-3 rounded-full mr-2" :class="!! field.value ? 'bg-green-500' : 'bg-red-500'"></div>
        <span class="text-sm text-gray-700"> {{ !! field.value ? 'Yes' : 'No' }} </span>
    </detail-field>
</template>

<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],
    }
</script>
