<template>
    <loading :loading="!loaded">
        <div v-if="loaded">
            <div class="flex items-center justify-between">
                <!--Header slot-->
                <slot name="head">
                    <h1>
                        <router-link :to="resourceIndexPage">
                            <button class="font-bold text-lg mb-8 text-gray-600 hover:text-gray-800">
                                <i class="align-middle" :class="`dripicons-${resource.icon}`"></i>
                                {{ resource.label }}
                            </button>
                        </router-link>

                        <i class="align-middle dripicons-chevron-right"></i>
                        <!--<span class="text-purple-600 text-lg">{{ resource.data.name }}</span>-->
                    </h1>
                </slot>
                <!--//Header slot-->
                <a-button @click="back" icon="reply">
                    Назад
                </a-button>
            </div>

            <div class="card pb-4">
                <!--Body slot-->
                <form
                    v-if="resource.fields"
                    class="w-full"
                    @submit.prevent="createRequest" autocomplete="off"
                    @keydown="errors.clear($event.target.id)"
                >

                    <div class="fields-panel">
                        <template v-for="field in resource.fields">
                            <component
                                :is="'form-' + field.component"
                                :field="field" class="field"
                                :errors="errors"
                                :key="field.attribute + key"
                                v-if="!hasScope(field.attribute)"
                            >
                            </component>
                            <slot v-else :name="`${field.attribute}-raw`"
                                  :field="field" :errors="errors">
                                <form-field :field="field" :errors="errors">
                                    <slot :name="field.attribute" :field="field"
                                          :errors="errors"/>
                                </form-field>
                            </slot>
                        </template>
                    </div>

                    <div class="flex flex-row-reverse mr-8 py-4 xs:flex-col xs:mx-10 es:mx-4">
                        <a-button
                            @click="buttonType = 'attach'"
                            :loading="loading && buttonType === 'attach'">
                            Прикрепить
                        </a-button>
                        <!--                        <a-button-->
                        <!--                            @click="buttonType = 'createContinue'"-->
                        <!--                            :loading="loading && buttonType === 'createContinue'">-->
                        <!--                            Создать и добавить еще-->
                        <!--                        </a-button>-->
                    </div>
                </form>
                <!--//Body slot-->
            </div>
        </div>
    </loading>
</template>

<script>
    import Errors from '../../utils/Errors'
    import Fields from 'Vendor/js/Mixins/Fields'

    export default {
        props: {
            formData: {
                type: FormData,
                default() {
                    return new FormData
                }
            },
            errors: {
                type: Errors,
                default() {
                    return new Errors
                }
            }
        },
        mixins: [Fields],
        data() {
            return {
                resource: {},
                buttonType: '',
                key: 1,
                loading: false,
                route: ''
            }
        },
        computed: {
            loaded() {
                if (this.resource) {
                    return this.resource.hasOwnProperty('fields')
                }
            },
            resourceIndexPage() {
                return `/${this.resource.name}`
            },
            officialFields() {
                return this.resource.fields.filter(field => field.hasOwnProperty('fill'))
            }
        },
        methods: {
            back() {
                this.$router.go('-1')
            },
            createResourceFormData() {
                _.each(this.officialFields, field => {
                    field.fill(this.formData)
                })

                this.formData.set('_method', 'PUT')
            },
            updateForm() {
                _.each(this.resource.fields, field => {
                    field.set('')
                })
            },
            submitForm() {
                this.loading = true
                this.createResourceFormData()

                return new Promise((resolve, reject) => {
                    Admin.request().post(this.route, this.formData)
                        .then(response => {
                            resolve(response)
                        })
                        .catch(error => {
                            reject(error)
                        })
                        .finally(() => {
                            this.loading = false
                        })
                })
            },
            async createRequest() {
                this.$emit('submit')

                try {
                    const response = await this.submitForm()
                    if (this.buttonType === 'createContinue') {
                        this.updateForm()
                        this.key++
                    } else {
                        this.back()
                    }

                    if (!response.data.toast) {
                        this.$toast('success', this.__('Resource is updated successfully!'))
                    }
                } catch (error) {
                    if (error.response && error.response.status == 422) {
                        this.errors.set(error.response.data.errors)
                    }
                }
            },
        },
        async created() {
            const {resource, id, relationship, attachedId} = this.$route.params
            const viaRelationship = this.$route.query.viaRelationship
            this.route = `resources/${resource}/${id}/attach/${relationship}/edit/${attachedId}?viaRelationship=${viaRelationship}`

            const {data} = await Admin.request().get(this.route)
            this.resource = data
        }
    }
</script>
