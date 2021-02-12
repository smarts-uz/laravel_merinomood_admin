<template>
    <form-field :field="field" :errors="errors">
        <!--SHOW MODE-->
        <div v-show="!isInChange && !isClear">
            <!--BASE BUTTONS-->
            <div>
                <button
                    @click.prevent="trigger"
                    class="inline-block text-sm bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded xs:mb-2"
                >
                    <i class="dripicons-document-edit mr-1 align-middle"></i>Редактировать

                </button>
                <button
                    @click.prevent="clear"
                    class="inline-block text-sm bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded xs:mb-2"
                >
                    <i class="dripicons-clockwise mr-1 align-middle"></i>Очистить
                </button>
            </div>
            <!--//BASE BUTTONS-->

            <!--IMAGE-->
            <div class="w-full mt-2">
                <div v-if="inputImage.url" class="image-show block">
                    <img
                        id="displayedImage"
                        class="w-full border-dashed border-2 border-purple-600 flex-auto inline-block flex-1 result"
                        :src="inputImage.url"
                        alt="" data-preview>
                </div>
                <p class="text-red-500 text-xs italic" v-if="errors.has('image')">{{ errors.get('image') }}</p>
            </div>
            <!--//IMAGE-->
        </div>
        <!--//SHOW MODE-->

        <!--CHANGE MODE-->
        <div v-show="isInChange && !isClear">
            <!--BUTTONS-->
            <button
                @click.prevent="crop"
                class="text-sm bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded"
            >
                <i class="dripicons-crop mr-1 align-middle"></i>Обрезать

            </button>
            <button
                @click.prevent="upload"
                class="text-sm bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded"
            >
                <i class="dripicons-upload mr-1 align-middle"></i>Загрузить
            </button>
            <button
                @click.prevent="trigger"
                class="inline-block text-sm bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
            >
                <i class="dripicons-reply mr-1 align-middle"></i>Отмена
            </button>
            <!--//BUTTONS-->

            <!--IMAGE WITH CROPPER-->
            <div class="w-full mt-2">
                <div v-if="inputImage.url" class="image-crop block">
                    <clipper-basic
                        class="border-dashed border-2 border-purple-600 flex-auto inline-block my-clipper mr-2"
                        ref="clipper"
                        :src="inputImage.url"
                        :ratio="ratio"
                        preview="my-preview"
                        @load="imgLoad"
                        :style="basicStyle"
                    />
                </div>
            </div>
            <!--//IMAGE WITH CROPPER-->
        </div>
        <!--//CHANGE MODE-->

        <!--DROPZONE-->
        <div v-show="isClear" class="image-drop block">
            <input enctype="multipart/form-data" type="file" id="file" ref="file" @change="handleFileUpload()"
                   accept="image/*" class="image-input">
            <p>
                Перетащите сюда вашу картинку <i class="dripicons-photo"></i>
                <br> Или нажмите для загрузки картинки с диска <i class="dripicons-download"></i>
            </p>
        </div>
        <!--//DROPZONE-->
    </form-field>
</template>

<script>
    import FormField from 'Vendor/js/Mixins/FormField'
    import {clipperBasic} from 'vuejs-clipper'
    import ClipperFixed from 'vuejs-clipper/src/components/clipper-fixed'

    export default {
        mixins: [FormField],
        components: {
            ClipperFixed,
            clipperBasic
        },
        data() {
            return {
                maxWidth: 700,
                maxHeight: 500,
                based: 850,
                isInChange: false,
                ratio: this.field.ratio,
                isClear: true,
                inputImage: {},
                outputImage: {},
                image: {}
            }
        },

        computed: {
            prettyUrl() {
                return `/storage/${this.field.value}`
            },
            basicStyle() {
                return {
                    maxWidth: this.based + 'px'
                }
            },
            imgSrc() {
                return `/storage/${this.field.value}?v=${Math.floor(Math.random() * 1001)}`
            }
        },

        methods: {
            createOutputImage(url) {
                fetch(url)
                    .then(res => res.blob()) // Gets the response and returns it as a blob
                    .then(blob => {
                        this.outputImage.file = new File([blob], this.inputImage.name)
                        // this.inputImage.file = new File([blob], this.inputImage.name);
                    })
            },
            imgLoad() {
                const imgRatio = this.$refs.clipper.imgRatio
                if (imgRatio <= 1) this.based = this.maxHeight * imgRatio
                else this.based = this.maxWidth
            },
            handleFileUpload() {
                this.inputImage.file = this.$refs.file.files[0]
                this.inputImage.name = this.inputImage.file.name
                this.inputImage.url = window.URL.createObjectURL(this.inputImage.file)
                this.isClear = false
                this.isInChange = true
            },
            crop() {
                const canvas = this.$refs.clipper.clip()
                canvas.toBlob((blob) => {
                    this.outputImage.file = new File([blob], this.inputImage.name)
                    // console.log(this.outputImage.file)
                })
                this.outputImage.url = canvas.toDataURL('image/jpg', 1)
                this.inputImage.url = canvas.toDataURL('image/jpg', 1)
                this.trigger()
            },
            upload() {
                document.getElementById('file').value = ''
                document.getElementById('file').click()
            },
            clear() {
                this.inputImage.file = ''
                this.inputImage.url = ''
                this.inputImage.name = ''
                this.outputImage.file = ''
                this.outputImage.url = ''
                this.isClear = true
                this.isInChange = true
            },
            trigger() {
                this.isInChange = !this.isInChange
            },
            fill(formData) {
                formData.append(this.field.attribute, this.outputImage.file || this.inputImage.file)
            }
        },

        created() {

            this.isClear = !this.value

            this.inputImage =
                {
                    file: (this.field.value) ? (this.createOutputImage(this.prettyUrl)) : (''),
                    url: (this.field.value) ? (this.prettyUrl + `?v=${Math.floor(Math.random() * 1001)}`) : (''),
                    name: (this.field.value) ? (this.field.value.split('\\').pop().split('/').pop()) : ('')
                }
            this.outputImage =
                {
                    file: '',
                    url: ''
                }
        }
    }
</script>

<style lang="scss" scoped>
    .my-clipper {
        width: 100%;
        max-width: 700px;
    }

    .image-drop {
        outline: 2px dashed grey; /* the dash box */
        outline-offset: -10px;
        background: lightcyan;
        color: dimgray;
        padding: 10px 10px;
        min-height: auto; /* minimum height */
        position: relative;
        cursor: pointer;
    }

    .image-input {
        opacity: 0; /* invisible but it's there! */
        width: 100%;
        height: 100%;
        position: absolute;
        cursor: pointer;
    }

    .image-drop:hover {
        background: lightblue; /* when mouse over to the drop zone, change color */
    }

    .image-drop p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 0;
    }
</style>


