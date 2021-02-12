<template>
    <div class="flex flex-wrap py-6 px-8 border-b md:flex-col">
        <div class="w-1/4 text-left md:mb-2 sm:w-full">
            <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mt-2">
                <slot name="label">
                    {{ field.label }}
                </slot>
            </label>
        </div>

        <div class="w-3/4 sm:w-full">
            <slot v-if="field.translatable" name="translatable"></slot>
            <div v-else>
                <slot></slot>
                <p class="text-red-500 text-xs italic pt-2" v-if="errors.has(field.attribute)">
                    {{ errors.get(field.attribute) }}
                </p>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['errors', 'field'],
    }
</script>
