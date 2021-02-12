<template>
    <icon name="bold" :active="isActive.bold()"
          @click="commands.bold" />
</template>

<script>
    import Extension from '../ExtensionMixin'

    export default {
        mixins: [Extension]
    }
</script>
