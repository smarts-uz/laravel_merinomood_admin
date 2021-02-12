<template>
    <div class="flex" :class="'justify-' + field.align">
        <div class="w-2 h-2 rounded-full inline-flex" :class="!! field.value ? 'bg-green-500' : 'bg-red-500'"></div>
    </div>
</template>

<script>
    import IndexField from 'Vendor/js/Mixins/IndexField'

    export default {
        mixins: [IndexField],
    }
</script>
