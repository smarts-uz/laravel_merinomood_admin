<template>
    <img v-if="field.value && !imageLoadError" :src="`/storage/${field.value}`" alt="" class="w-12 h-12 rounded-full" @error="imageLoadError = true" />
    <p v-else-if="imageLoadError">Некорректное изображение</p>
    <i v-else class="dripicons-minus text-gray-600 w-12 h-12 rounded-full flex justify-center items-center" title="Изображение отсутствует" />
</template>

<script>
    import IndexField from 'Vendor/js/Mixins/IndexField'

    export default {
        mixins: [IndexField],
        data() {
            return {
                imageLoadError: false,
            }
        },
    }
</script>
