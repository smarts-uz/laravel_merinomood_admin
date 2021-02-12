<template>
    <detail-field :field="field">
        <div v-if="field.value.length"
             class="gallery flex justify-start flex-wrap w-full rounded-lg p-2">
            <div v-for="image in field.value" class="object-fill w-1/5 p-1">
                <img :src="`/storage/${image.src}`" alt=""
                     class="gallery-image w-full h-24 rounded object-cover bg-white shadow-lg">
            </div>
        </div>
        <div v-else title="Изображения отсутствуют" class="flex align-center">
            <i class="dripicons-minus text-gray-600"></i>
        </div>
    </detail-field>
</template>

<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],
    }
</script>

<style scoped>
    .gallery {
        background: rgba(119, 175, 213, 0.34);
    }
</style>