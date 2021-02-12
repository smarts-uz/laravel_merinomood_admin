<template>
    <form-field :field="field" :errors="errors">
        <input
                v-model="value"
                type="checkbox"
                class="block border border-purple-200 py-2 px-4 my-3 leading-tight focus:outline-none"
                :class="classes()"
                :id="field.attribute"
        >
    </form-field>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'

    export default {
        mixins: [FormFieldMixin],
    }
</script>
