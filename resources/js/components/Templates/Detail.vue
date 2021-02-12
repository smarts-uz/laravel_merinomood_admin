<template>
    <loading :loading="!loaded">
        <div v-if="loaded">
            <div class="flex justify-between sm:flex-col sm:justify-start">
                <slot name="head">
                    <h1>
                        <router-link :to="resourceIndexPage">
                            <button class="font-bold text-lg mb-8 text-gray-600 hover:text-gray-800">
                                <i class="align-middle" :class="`dripicons-${resource.icon}`"></i>
                                {{ resource.label }}
                            </button>
                        </router-link>
                        <i class="align-middle dripicons-chevron-right"></i>
                    </h1>
                </slot>

                <div class="flex mb-5 es:flex-wrap">
                    <a>
                        <a-button @click="$router.go(-1)" icon="reply">
                            {{ __('Back') }}
                        </a-button>
                    </a>
                    <router-link :to="updatePage" v-if="resource.resource.can.update">
                        <a-button icon="document-edit">
                            {{ __('Edit') }}
                        </a-button>

                    </router-link>
                    <slot name="actions"></slot>
                    <a v-if="resource.resource.can.delete">
                        <a-button icon="trash"
                                  @click="deleteResource(resource.name, resource.resource.id)">
                            {{ __('Delete') }}
                        </a-button>
                    </a>
                </div>
            </div>

            <div class="card px-5 mb-10">
                <div class="flex flex-col py-1 fields-panel">
                    <template v-for="field in visibleFields">
                        <component :key="field.attribute" :is="'detail-' + field.component"
                                   :field="field" class="field"
                                   :resource="resource" v-if="!hasScope(field.attribute)">
                        </component>
                        <slot :name="`${field.attribute}-raw`" :field="field" v-else>
                            <detail-field :field="field" class="field">
                                <slot :name="field.attribute" :field="field"/>
                            </detail-field>
                        </slot>
                    </template>
                </div>
            </div>

            <template v-for="field in resource.relationships">
                <slot :name="field.attribute" :field="field"
                      :via="$via(field.attribute, resource)">
                    <component :field="field" :is="field.component"
                               :via="$via(field.attribute, resource)"
                               :key="field.attribute"/>
                </slot>
            </template>
        </div>
    </loading>
</template>

<script>
    import {Index} from './Index'
    import ResourceDetail from 'Vendor/js/Mixins/ResourceDetail'
    import HasManyField from '../Fields/Relationships/HasManyField'
    import BelongsToManyField from '../Fields/Relationships/BelongsToManyField'
    import Fields from 'Vendor/js/Mixins/Fields'

    export default {
        props: {
            resource: Object
        },
        components: {
            Index,
            HasManyField,
            BelongsToManyField
        },
        mixins: [ResourceDetail, Fields],
        computed: {
            confirmDeleteMessage() {
                if (this.resource.resource) {
                    return this.resource.resource.data.name
                }
            },
            loaded() {
                if (this.resource) {
                    return this.resource.hasOwnProperty('resource')
                }
            },
            updatePage() {
                if (this.resource.resource) {
                    return `/${this.resource.name}/${this.resource.resource.id}/edit`
                }
            },
            resourceIndexPage() {
                if (this.resource.resource) {
                    return `/${this.resource.name}`
                }
            },

            viaResource() {
                if (!!this.$route.query.viaResource) {
                    return this.$route.query.viaResource
                }

                return ''
            },

            viaResourceId() {
                if (!!this.$route.query.viaResourceId) {
                    return this.$route.query.viaResourceId
                }

                return ''
            }
        },

        methods: {
            deleteResource(resource, id) {
                if (this.resource.resource) {
                    this.$toast('question', this.__('Are you sure you want to delete selected resources?'))
                        .then(() => {
                            Admin.request(this.resource.name).delete(id, {
                                resource: this.resource.name
                            }).then(() => {
                                this.$router.go('-1')
                                this.$toast('success', this.__('Resource is deleted successfully!'))
                            })
                        })
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
