<template>
    <detail-field :field="field">
        <img v-if="field.value && !imageLoadError" :src="imgSrc" class="card max-w-xs border overflow-hidden" @error="imageLoadError = true" />
        <p v-else-if="imageLoadError">Некорректное изображение</p>
        <i v-else class="dripicons-minus text-gray-600" title="Изображение отсутствует"/>
    </detail-field>
</template>

<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],
        data() {
            return {
                imageLoadError: false,
            }
        },
        computed: {
            imgSrc() {
                return `/storage/${this.field.value}?v=${Math.floor(Math.random()*1001)}`
            },
        },
    }
</script>
