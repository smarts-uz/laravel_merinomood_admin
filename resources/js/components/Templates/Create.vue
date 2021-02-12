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
                    {{ __('Back') }}
                </a-button>
            </div>

            <div class="card pb-4">
                <!--Body slot-->
                <form
                    v-if="resource.resource.fields"
                    class="w-full"
                    @submit.prevent="createRequest" autocomplete="off"
                    @keydown="errors.clear($event.target.id)"
                >

                    <div class="fields-panel">
                        <template v-for="field in visibleFields">
                            <component
                                :is="'form-' + field.component"
                                :field="field" class="field"
                                :errors="errors"
                                :key="field.attribute"
                                v-if="!hasScope(field.attribute)"
                            >
                            </component>
                            <template v-else>
                                <slot :name="`${field.attribute}-raw`"
                                      :field="field" :errors="errors">
                                    <form-field :field="field" :errors="errors" class="field">
                                        <slot :name="field.attribute" :field="field"
                                              :errors="errors"/>
                                    </form-field>
                                </slot>
                            </template>
                        </template>
                    </div>

                    <div class="flex flex-row-reverse mr-8 py-4 xs:flex-col xs:mx-10 es:mx-4">
                        <a-button
                            @click="buttonType = 'create'"
                            :loading="loading && buttonType === 'create'">
                            {{ __('Create') }}
                        </a-button>
                        <a-button
                            @click="buttonType = 'createContinue'"
                            :loading="loading && buttonType === 'createContinue'">
                            {{ __('Create and add another') }}
                        </a-button>
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
        mixins: [Fields],
        props: {
            resource: Object,
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
            },
            reset: {
                type: Function,
                default() {
                    this.resetForm()
                }
            }
        },
        data() {
            return {
                buttonType: '',
                loading: false
            }
        },
        computed: {
            loaded() {
                if (this.resource) {
                    return this.resource.hasOwnProperty('resource')
                }
            },
            resourceIndexPage() {
                return `/${this.resource.name}`
            },
        },
        methods: {
            back() {
                this.$router.go('-1')
            },
            createResourceFormData() {
                _.each(this.officialFields, field => {
                    field.fill(this.formData)
                })

                this.formData.append('viaResource', this.$route.query.viaResource)
                this.formData.append('viaResourceId', this.$route.query.viaResourceId)
            },
            submitForm() {
                this.loading = true
                this.createResourceFormData()

                return Admin.request(this.resource.name)
                    .store(this.formData)
                    .finally(() => this.loading = false)
            },
            async createRequest() {
                this.$emit('submit')

                try {
                    const response = await this.submitForm()
                    if (this.buttonType === 'createContinue') {
                        this.resetForm()
                    } else {
                        this.back()
                    }

                    if (!response.data.toast) {
                        this.$toast('success', this.__('Resource is created successfully!'))
                    }
                } catch (error) {
                    if (error.response && error.response.status == 422) {
                        this.errors.set(error.response.data.errors)
                    }
                }
            },
            resetForm() {
                this.resource.resource.fields.forEach(field => field.reset())
            }
        },

        watch: {
            resource(resource) {
                let fields = {}
                resource.resource.fields.forEach(field => {
                    fields[field.attribute] = field
                })

                this.$emit('init', {
                    ...resource.resource, fields
                })
            }
        }
    }
</script>
