<template>
    <div class="flex justify-start items-center flex-wrap">
        <img v-for="image in galleryPreviews()"
             :src="`/storage/${image.src}`"
             class="w-10 h-10 rounded-full mr-1 border border-gray-400 object-cover"/>
        <div v-if="overageImagesCount" class="overage-number w-8 h-8 rounded-full ml-2 border border-purple-600 flex justify-center items-center ">
            <div class="text-xs text-purple-600">+{{overageImagesCount}}</div>
        </div>
    </div>
</template>

<script>
    import IndexField from 'Vendor/js/Mixins/IndexField'

    export default {
        mixins: [IndexField],

        data() {
            return {
                overageImagesCount: 0,
            }
        },

        methods: {
            galleryPreviews() {
                let allImagesCount = this.field.value.length
                let newArray = this.field.value

                if (allImagesCount > 5) {
                    this.overageImagesCount = allImagesCount - 5

                    return newArray.slice(0, 5)
                }

                return newArray
            }
        },

    }
</script>
