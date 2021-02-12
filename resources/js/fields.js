import IndexTextField from 'Vendor/js/components/Fields/Index/TextField'
import IndexBooleanField from 'Vendor/js/components/Fields/Index/BooleanField'
import IndexDateField from 'Vendor/js/components/Fields/Index/DateField'
import IndexGalleryField from 'Vendor/js/components/Fields/Index/GalleryField'
import IndexFileField from 'Vendor/js/components/Fields/Index/FileField'
import IndexImageField from 'Vendor/js/components/Fields/Index/ImageField'
import IndexSelectField from 'Vendor/js/components/Fields/Index/SelectField'
import IndexMorphToField from 'Vendor/js/components/Fields/Index/MorphToField'
import IndexCropImageField from 'Vendor/js/components/Fields/Index/CropImageField'

import FormField from 'Vendor/js/components/Fields/Form/FormField'
import FormTextField from 'Vendor/js/components/Fields/Form/TextField'
import FormNumberField from 'Vendor/js/components/Fields/Form/NumberField'
const FormTrixField = () => import(/* webpackChunkName: "form-trix-field" */ 'Vendor/js/components/Fields/Form/TrixField')
import FormTextareaField from 'Vendor/js/components/Fields/Form/TextareaField'
import FormBooleanField from 'Vendor/js/components/Fields/Form/BooleanField'
import FormPasswordField from 'Vendor/js/components/Fields/Form/PasswordField'
const FormSelectField = () => import(/* webpackChunkName: "form-select-field" */ 'Vendor/js/components/Fields/Form/SelectField')
const FormMorphToField = () => import(/* webpackChunkName: "form-select-field" */ 'Vendor/js/components/Fields/Form/MorphToField')
import FormFileField from 'Vendor/js/components/Fields/Form/FileField'
const FormCropImageField = () => import(/* webpackChunkName: "form-crop-image-field" */ 'Vendor/js/components/Fields/Form/CropImageField')
import FormDateField from 'Vendor/js/components/Fields/Form/DateField'
import FormImageField from 'Vendor/js/components/Fields/Form/ImageField'
import FormGalleryField from 'Vendor/js/components/Fields/Form/GalleryField'
import FormTiptapField from 'Vendor/js/components/Fields/Form/Tiptap/TiptapField'
import DetailTiptapField from 'Vendor/js/components/Fields/Detail/TiptapField'

import DetailField from 'Vendor/js/components/Fields/Detail/DetailField'
import DetailTextField from 'Vendor/js/components/Fields/Detail/TextField'
import DetailTrixField from 'Vendor/js/components/Fields/Detail/TrixField'
import DetailBooleanField from 'Vendor/js/components/Fields/Detail/BooleanField'
import DetailDateField from 'Vendor/js/components/Fields/Detail/DateField'
import DetailImageField from 'Vendor/js/components/Fields/Detail/ImageField'
import DetailCropImageField from 'Vendor/js/components/Fields/Detail/CropImageField'
import DetailGalleryField from 'Vendor/js/components/Fields/Detail/GalleryField'
import DetailFileField from 'Vendor/js/components/Fields/Detail/FileField'
import DetailSelectField from 'Vendor/js/components/Fields/Detail/SelectField'
import DetailMorphToField from 'Vendor/js/components/Fields/Detail/MorphToField'


Vue.component('index-text-field', IndexTextField)
Vue.component('index-number-field', IndexTextField)
Vue.component('index-trix-field', IndexTextField)
Vue.component('index-textarea-field', IndexTextField)
Vue.component('index-boolean-field', IndexBooleanField)
Vue.component('index-date-field', IndexDateField)
Vue.component('index-gallery-field', IndexGalleryField)
Vue.component('index-file-field', IndexFileField)
Vue.component('index-image-field', IndexImageField)
Vue.component('index-select-field', IndexSelectField)
Vue.component('index-morph-to-field', IndexMorphToField)
Vue.component('index-crop-image-field', IndexCropImageField)

Vue.component('form-field', FormField)
Vue.component('form-text-field', FormTextField)
Vue.component('form-number-field', FormNumberField)
Vue.component('form-trix-field', FormTrixField)
Vue.component('form-textarea-field', FormTextareaField)
Vue.component('form-boolean-field', FormBooleanField)
Vue.component('form-password-field', FormPasswordField)
Vue.component('form-select-field', FormSelectField)
Vue.component('form-morph-to-field', FormMorphToField)
Vue.component('form-file-field', FormFileField)
Vue.component('form-gallery-field', FormGalleryField)
Vue.component('form-crop-image-field', FormCropImageField)
Vue.component('form-date-field', FormDateField)
Vue.component('form-image-field', FormImageField)
Vue.component('form-tiptap-field', FormTiptapField)

Vue.component('detail-text-field', DetailTextField)
Vue.component('detail-field', DetailField)
Vue.component('detail-number-field', DetailTextField)
Vue.component('detail-trix-field', DetailTrixField)
Vue.component('detail-textarea-field', DetailTextField)
Vue.component('detail-boolean-field', DetailBooleanField)
Vue.component('detail-date-field', DetailDateField)
Vue.component('detail-image-field', DetailImageField)
Vue.component('detail-crop-image-field', DetailCropImageField)
Vue.component('detail-gallery-field', DetailGalleryField)
Vue.component('detail-file-field', DetailFileField)
Vue.component('detail-select-field', DetailSelectField)
Vue.component('detail-morph-to-field', DetailMorphToField)
Vue.component('detail-tiptap-field', DetailTiptapField)
