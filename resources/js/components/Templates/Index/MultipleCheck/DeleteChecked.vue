<template>
    <div class="flex delete-checked w-30 px-2 cursor-pointer" @click="remove()">
        <div class="self-center inline-flex text-sm rounded border text-gray-600 border-gray-500 font-bold py-1 px-4">
            <button @click="" class="">
                <i class="dripicons-trash align-middle"/>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DeleteChecked",
        props: {
            items: Array,
            resources: Object,
            via: Object,
        },

        data() {
            return {
                checkedItems: this.items,
            }
        },
        methods: {
            remove() {
                this.$emit('delete', this.checkedItems)
            }
        },

    }
</script>
