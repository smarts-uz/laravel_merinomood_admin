<template>
    <icon :active="isActive.strike()" @click="commands.strike">
        <span class="text-gray-700 inline-flex line-through uppercase">
            a
        </span>
    </icon>
</template>

<script>
    import Extension from '../ExtensionMixin'

    export default {
        mixins: [Extension]
    }
</script>
