<template>
    <icon name="italic" :active="isActive.italic()"
          @click="commands.italic" width="6" />
<!--    <button class="w-6 h-8 flex items-center justify-center rounded"-->
<!--            :class="isActive.italic() ? 'bg-gray-300' : 'hover:bg-gray-200'"-->
<!--            @click.prevent="commands.italic">-->
<!--        <i class="text-gray-700 inline-flex dripicons-italic"></i>-->
<!--    </button>-->
</template>

<script>
    import Extension from '../ExtensionMixin'

    export default {
        mixins: [Extension]
    }
</script>
