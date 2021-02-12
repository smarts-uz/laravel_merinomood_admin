export default {
    methods: {
        __(key) {
            return this.$translations[key] || key
        }
    }
}
