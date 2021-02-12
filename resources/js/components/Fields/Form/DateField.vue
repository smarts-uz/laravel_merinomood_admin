<template>
    <form-field :field="field" :errors="errors">
        <input
                v-model="value"
                type="date"
                class="input appearance-none block w-full leading-tight focus:outline-none focus:bg-white"
                :class="classes()"
                :id="field.attribute"
        >
    </form-field>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'

    export default {
        mixins: [FormFieldMixin]
    }
</script>
