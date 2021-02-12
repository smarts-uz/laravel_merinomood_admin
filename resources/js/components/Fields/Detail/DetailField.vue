<template>
    <div class="border-b flex py-5 es:flex-col">
        <div class="inline text-sm text-gray-600 w-1/4 sm:w-2/5 es:w-full es:mb-4">
            {{ field.label }}
        </div>
        <div class="text-sm text-gray-700 flex-grow w-3/4 sm:w-3/5 es:w-full flex items-center">
            <slot v-if="field.translatable" name="translatable"></slot>
            <slot v-else></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            field: Object
        }
    }
</script>
