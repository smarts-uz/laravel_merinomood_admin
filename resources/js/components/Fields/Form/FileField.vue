<template>
    <form-field :field="field" :errors="errors">
        <div v-if="field.value" class="mb-8 w-full">
            <a class="card border border-gray-400 py-2 px-4" :href="field.value" target="_blank" >{{ filename }}</a>
        </div>

        <input
            ref="fileField"
            type="file"
            class="appearance-none block w-full text-gray-700 border rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-purple-400"
            :class="classes()"
            :id="field.attribute"
            @change="fileChange"
        >
    </form-field>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'

    export default {
        mixins: [FormFieldMixin],

        computed: {
            filename() {
                return this.field.value.substring(this.field.value.lastIndexOf('/')+1)
            }
        }
    }
</script>
