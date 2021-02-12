<template>
    <index :resources="field.value" :label="field.label"
           :via="via" class="mb-10" />
</template>

<script>
    export default {
        props: {
            field: {},
            via: {}
        },
        created() {
            let string = _.startCase(this.field.value.name)
            let dir = _.replace(string, ' ', '')

            this.$options.components['index'] = require(`Admin/js/views/${dir}/Index.vue`).default;
        }
    }
</script>
