<template>
    <form-field :field="field" :errors="errors">
        <input
                v-model="value"
                type="number"
                :step="field.step"
                :min="field.min"
                :max="field.max"
                class="input appearance-none block w-full leading-tight focus:outline-none focus:bg-white"
                :class="classes()"
                :id="field.attribute"
        >
    </form-field>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'
    import Tabs from 'Vendor/js/components/Tabs';
    import Tab from 'Vendor/js/components/Tab';

    export default {
        mixins: [FormFieldMixin],

        components: {Tab, Tabs},
    }
</script>
