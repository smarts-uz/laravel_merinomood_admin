<template>
    <div>
        <detail-field :field="field">
            {{ field.types[field.value.attributeType]}}
        </detail-field>
        <detail-field :field="{}">
            {{ field.options[field.value.attributeType][field.value.attributeId]}}
        </detail-field>
    </div>
</template>

<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],
    }
</script>
