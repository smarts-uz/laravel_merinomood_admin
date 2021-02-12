<template>
    <div>{{ type }}: {{ value }}</div>
</template>

<script>
    import IndexField from 'Vendor/js/Mixins/IndexField'

    export default {
        mixins: [IndexField],
        computed: {
            type() {
                return this.field.types[this.field.value.attributeType]
            },
            value() {
                return this.field.options[this.field.value.attributeType][this.field.value.attributeId]
            }
        }
    }
</script>
