<template>
    <div v-if="field.value">
        <div v-if="field.html" v-html="getValue"></div>
        <template v-else>
            {{ getValue }}
        </template>
    </div>
    <div v-else class="w-10 flex justify-center items-center">
        <i class="dripicons-minus text-gray-600" title="Значение отсутствует"/>
    </div>
</template>

<script>
    import IndexField from 'Vendor/js/Mixins/IndexField'

    export default {
        mixins: [IndexField],
    }
</script>
