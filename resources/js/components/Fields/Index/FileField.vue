<template>
    <a class="text-gray-600" :href="field.value" target="_blank">{{ filename }}</a>
</template>

<script>
    import IndexField from 'Vendor/js/Mixins/IndexField'

    export default {
        mixins: [IndexField],

        computed: {
            filename() {
                return this.field.value.substring(this.field.value.lastIndexOf('/')+1)
            }
        }
    }
</script>
