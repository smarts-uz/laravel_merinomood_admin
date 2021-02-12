<template>
    <div class="flex justify-center">
        <img v-if="field.value && !imageLoadError" :src="imgSrc" width="80" class="card max-w-xs border overflow-hidden" @error="imageLoadError = true" />
        <p v-else-if="imageLoadError">Некорректное изображение</p>
        <i v-else class="dripicons-minus text-gray-600" title="Изображение отсутствует"/>
    </div>
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
        computed: {
            imgSrc() {
                return `/storage/${this.field.value}?v=${Math.floor(Math.random()*1001)}`
            }
        }
    }
</script>
