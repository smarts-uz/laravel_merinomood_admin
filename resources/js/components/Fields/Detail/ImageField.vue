<template>
    <detail-field :field="field">
        <div v-if="field.value" class="card inline-flex max-w-xs border overflow-hidden">
            <img :src="`/storage/${field.value}`" alt="" class="w-full">
        </div>
        <div v-else class="w-full">
            <i class="dripicons-minus text-gray-600" title="Изображение отсутствует" />
        </div>
    </detail-field>
</template>

<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],
    }
</script>
