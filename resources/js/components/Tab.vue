<template>
    <div v-show="isActive"><slot></slot></div>
</template>

<script>
    export default {
        name: "Tab",
        props: {
            locale: { required: true },
            selected: { default: false},
            error: {},
        },

        data() {

            return {
                isActive: false
            };

        },

        computed: {

            // href() {
            //     return '#' + this.name.toLowerCase().replace(/ /g, '-');
            // }
        },

        mounted() {

            this.isActive = this.selected;

        },

        watch: {
            selected(value) {
                this.isActive = value
            }
        }
    }
</script>

<style scoped>

</style>
