<template>
    <detail-field :field="field">
        <!--filename from url-->
        <div v-if="field.value">
            {{ filename }}
            <div  class="block pt-4">
                <a :href="`/storage/${field.value}`" target="_blank"
                   class="text-sm bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 mr-2 rounded">
                    <i class="align-middle dripicons-download"></i>
                    Скачать
                </a>
            </div>
        </div>
        <i v-else class="dripicons-minus text-gray-600" title="Файл отсутствует" />
    </detail-field>
</template>

<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'

    export default {
        mixins: [DetailField],

        computed: {
            filename() {
                return this.field.value.substring(this.field.value.lastIndexOf('/')+1)
            }
        }
    }
</script>
