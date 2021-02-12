<template>
    <form-tiptap-field :field="field" :editable="false" wrapper="detail-field" />
</template>
<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],
    }
</script>
