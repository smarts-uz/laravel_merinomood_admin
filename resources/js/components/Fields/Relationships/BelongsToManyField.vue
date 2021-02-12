<template>
    <index :resources="field.resources" :label="field.label" :key="field.relationship"
           :via="via" @update="field.resources.resources = $event.resources"
           attaching class="mb-10"
    ></index>
</template>

<script>
    import {Index} from '../../Templates/Index'

    export default {
        props: {
            field: {},
            via: {}
        },
        components: {
            Index
        }
    }
</script>
