<template>
    <loading :loading="!resource.hasOwnProperty('resource')">
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
            <a-button icon="reply" @click="back">
                {{ __('Back') }}
            </a-button>
        </div>

        <div class="card edit pb-4">
            <!--Body slot-->
            <form
                v-if="resource.resource"
                class="w-full"
                @submit.prevent="createRequest" autocomplete="off"
                @keydown="errors.clear($event.target.id)"
            >
                <div class="fields-panel">
                    <template v-for="field in visibleFields">
                        <component
                            :key="field.attribute"
                            :is="'form-' + field.component"
                            :field="field" class="field"
                            :errors="errors"
                            v-if="!hasScope(field.attribute)"
                        >
                        </component>
                        <template v-else>
                            <slot :name="`${field.attribute}-raw`" :field="field"
                                  :errors="errors">
                                <form-field :field="field" :errors="errors" class="field">
                                    <slot :name="field.attribute" :field="field"
                                          :errors="errors"/>
                                </form-field>
                            </slot>
                        </template>
                    </template>
                </div>

                <div class="flex flex-row-reverse mr-8 py-4 sm:flex-col sm:mx-8 es:mx-2">
                    <a-button :loading="loading">
                        {{ __('Update') }}
                    </a-button>
                </div>
            </form>
            <!--//Body slot-->
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
            }
        },
        data() {
            return {
                loading: false
            }
        },
        computed: {
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
                    .update(this.$route.params.id, this.formData)
                    .finally(() => this.loading = false)
            },
            async createRequest() {
                this.$emit('submit')
                try {
                    const response = await this.submitForm()

                    this.back()

                    if (!response.data.toast) {
                        this.$toast('success', this.__('Resource is updated successfully!'))
                    }
                } catch (error) {
                    if (error.response.status == 422) {
                        this.errors.set(error.response.data.errors)
                    }
                }
            },
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

