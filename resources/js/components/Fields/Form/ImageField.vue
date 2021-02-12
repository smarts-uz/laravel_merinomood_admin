<template>
    <form-field :field="field" :errors="errors">
        <div v-if="field.value" class="card max-w-xs border overflow-hidden mb-4 flex flex-wrap justify-center">
            <img v-if="!uploaded" :src="`/storage/${field.value}`" alt="" class="w-full mb-1">
            <img v-if="uploaded" :src="uploadedImage" alt="" data-preview>
            <i class="text-xl text-red-600 dripicons-cross cursor-pointer"
                @click="() => {value = null; field.value = ''; uploadedImage = null}"
            ></i>
        </div>

        <input v-if="!value"
                ref="fileField"
                type="file"
                class="appearance-none block w-full leading-tight focus:outline-none"
                :class="classes()"
                @change="fileChange"
                :id="field.attribute"
        >
    </form-field>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'

    export default {
        name: "FileField",
        mixins: [FormFieldMixin],
        data(){
            return {
                uploaded: false,
                uploadedImage: null,
            }
        },
        methods: {
            fileChange() {
                this.uploaded = true
                this.value = this.$refs.fileField.files[0];
                this.createImage(this.value);
            },
            createImage(file){
                let reader = new FileReader();

                reader.onload = (e) => {
                    this.uploadedImage = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            reset() {
                this.value = ''
                this.uploaded = false
                this.uploadedImage = null
            }
        },
    }
</script>
