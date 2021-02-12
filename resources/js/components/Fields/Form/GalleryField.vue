<template>
    <form-field :field="field" :errors="errors">
        <button
                @click.prevent="upload"
                class="text-sm bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded">
            <i class="dripicons-upload mr-1 align-middle"></i>Загрузить
        </button>

        <button
                @click.prevent="clear"
                class="inline-block text-sm bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 m-2 rounded">
            <i class="dripicons-document-delete mr-1 align-middle"></i>Очистить
        </button>

        <div class="gallery-container">
            <input drag-over="handleDragOver"
                   @dragenter="dragging=true"
                   @dragleave="dragging=false"
                   @dragend="dragging=false"
                   :class="[dragging ? 'dragenter' : '']"
                   accept="image/*"
                   type="file" id="files" ref="files" class="gallery-image-input h-full w-full" multiple @change="handleFiles()" :id="field.attribute"/>

            <div class="gallery-grid w-full p-4 rounded"
                 :class="[dragging ? 'dragenter' : '']">
                <div v-if="!files.length" class="gallery-dropbox-title w-full text-sm-center">
                    <p>Перетащите изображения сюда <b class="dripicons-photo-group"/></p>
                    <p v-if="!dragging">Или с помощью соответствующей кнопки <b>"<i class="dripicons-upload"/> Загрузить"</b></p>
                </div>

                <div v-for="(file, index) in files" class="gallery-image-wrap relative w-1/5 p-1">
                    <img class="gallery-image w-full h-24 rounded shadow-lg object-cover bg-white" :ref="'preview'+index" data-preview/>
                    <button
                            @click.prevent="removeFile(index)"
                            class="image-remove-button dripicons-trash absolute top-0 right-0 mt-2 mr-2 bg-red-500 text-white font-bold w-8 h-8 rounded-full z-10 shadow-lg text-sm-center">
                    </button>
                </div>
            </div>
        </div>
    </form-field>
</template>


<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'

    export default {
        name:'GalleryField',
        mixins: [FormFieldMixin],

        data() {
            return {
                files: [],
                dragging: false,
            }
        },
        methods: {
            upload() {
                document.getElementById(this.field.attribute).click()
            },
            clear() {
                this.files.splice(0);
                this.dragging = false;
            },
            handleFiles() {
                let uploadedFiles = this.$refs.files.files;
                for( let i = 0; i < uploadedFiles.length; i++) {
                    this.files.push(uploadedFiles[i]);
                }
                this.dragging = false;
                return this.getImagePreviews();
            },
            getImagePreviews(){
                for( let i = 0; i < this.files.length; i++ ){
                    if (/\.(jpe?g|png|gif)$/i.test( this.files[i].name )) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function(){
                            this.$refs['preview'+i][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL( this.files[i] );
                    }
                }
            },
            removeFile( key ){
                this.files.splice( key, 1 );
                this.getImagePreviews();
            },
            fill(formData) {
                for( let i = 0; i < this.files.length; i++ ){
                    if(this.files[i].id) {
                        continue;
                    }
                    formData.append(`${this.field.attribute}[]`, this.files[i]);
                }
            },
            convertUrlToFile(url) {
                fetch(url)
                    .then(r => r.blob())
                    .then(blob => {
                        this.files.push( new File([blob],
                            this.getFileNameFromUrl(url),
                            {type: "image/png", lastModified: new Date()}))
                        this.getImagePreviews()
                    });
            },
            getFileNameFromUrl(url) {
                return url.split('\\').pop().split('/').pop()
            },
            getUrl(url) {
                return `/storage/${url}`
            },
            getFiles(value) {
                let files = value.map(imageUrl => {
                    return this.convertUrlToFile(this.getUrl(imageUrl.src))
                })

                return files
            },
            reset() {
                this.clear()
            }
        },

        created() {
            this.getFiles(this.value)
        },

    }
</script>

<style lang="scss" scoped>
    .gallery-container {
        position: relative;
        min-height: 100px;
        z-index: 1;
        display: flex;
        justify-content: flex-start;
        flex-direction: row;
    }

    .gallery-dropbox-title{
        color: #523c9d;
        justify-content:center;
    }

    .gallery-grid {
        align-content: center;
        display: flex;
        text-align: center;
        justify-content: flex-start;
        flex-wrap: wrap;
        background: rgba(119,175,213,0.34);
        outline: 2px dashed #6b46c1;
        outline-offset: -6px;
        &.dragenter {
            background: rgba(125, 7, 213, 0.34);
        }
    }

    .gallery-image-input {
        opacity: 0;
        position: absolute;
        cursor: pointer;
        z-index: 0;
        transition: all ease .6s;
        &.dragenter {
            z-index: 50;
        }
    }

    .image-remove-button {
        will-change: opacity, transform;
        opacity: 0;
        transition: all ease .6s;
    }

    .gallery-image-wrap {
        will-change: transform;
        transition: all .2s ease-in-out;
        z-index: 2;
        &:hover {
            transform: scale(1.1);
            z-index: 3;
            .image-remove-button {
                opacity: 1;
            }
        }
    }
</style>
