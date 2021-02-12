export default {
    computed: {
        getValue() {
            return this.field.translatable ? this.field.value[this.$locale] : this.field.value
        }
    },
    props: {
        field: {
            required: true,
            type: Object
        }
    },
}
