<template>
    <loading :loading="!loaded">
        <slot name="title">
            <h1 class="font-bold text-lg mb-2 text-gray-600">
                <i class="align-middle" :class="`dripicons-${resources.icon}`"></i>
                {{ label || resources.label }}
            </h1>
        </slot>
        <template v-if="loaded">
            <div class="flex mb-5" :class="resources.seachable ? 'justify-between' : 'justify-end'">
                <div class="index-search relative flex-grow" v-if="resources.searchable">
                    <input type="text"
                           class="border rounded outline-none
                       pl-10 pr-4 py-2 text-sm text-gray-700
                       w-1/4 md:w-2/3 es:w-4/5" :placeholder="__('Search')"
                           v-model="search"
                           @keyup="performSearch"
                    >
                    <i class="dripicons-search absolute w-5 h-5 text-lg top-0 bottom-0 left-0 ml-3 m-auto text-gray-600 es:w-4 es:h-4"></i>
                </div>
                <template v-if="attaching && resources.can.attach">
                    <router-link :to="attachLink" class="es:w-1/2 es:flex es:justify-end">
                        <a-button>
                            {{ __('Attach') }}
                        </a-button>
                    </router-link>
                </template>
                <template v-else-if="resources.can.create">
                    <router-link :to="createLink" class="es:w-1/2 es:flex es:justify-end">
                        <a-button>
                            {{ __('Create') }}
                        </a-button>
                    </router-link>
                </template>
            </div>
            <div class="card-wrap">
                <div class="card rounded px-5 pt-5 pb-4">
                    <template v-if="resources.resources.length">
                        <div class="pre-table-header pb-2 flex justify-between text-sm text-gray-600">
                            <div class="main-checkbox">
                                <checkbox class="inline-block" v-model="checkAllChecker"/>
                                <div class="inline-block main-checkbox-icons">
                                    <i class="dripicons-arrow-thin-left align-middle"></i>
                                    <i class="dripicons-duplicate align-middle"></i>
                                </div>
                            </div>
                            <delete-checked @delete="remove" v-if="(checkedItems.length > 0)" :items="checkedItems"
                                            :resources="resources"/>
                        </div>
                        <table class="table w-full md:w-auto">
                            <thead>
                                <tr>
                                    <th class="text-sm text-gray-600 border border-l-0 border-r-0 border-b-2 w-12"></th>
                                    <th class="icon border border-l-0 border-r-0 border-b-2" v-if="resources.orderable">
                                        <i class="dripicons-minus text-xl pt-1"></i>
                                    </th>
                                    <th :class="[field.sortable ? 'sortable' : '', 'text-' + field.align, field.classes.head]"
                                        class="p-3 text-sm text-gray-600 border border-l-0 border-r-0 border-b-2"
                                        v-for="field in fields()"
                                        @click="sort(field.attribute, field.sortable)">
                                        {{ field.label }}
                                        <span class="sortings" v-if="field.sortable">
                                            <span class="sort sort-asc"
                                                  :class="{active: sortedField(field.attribute, 'asc')}">
                                                arrow_drop_up
                                            </span>
                                            <span class="sort sort-desc"
                                                  :class="{active: sortedField(field.attribute, 'desc')}">
                                                arrow_drop_down
                                            </span>
                                        </span>
                                    </th>
                                    <th class="text-sm text-gray-600 border border-l-0 border-r-0 border-b-2 w-28"
                                        v-if="showActions">
                                        {{ __('Action') }}
                                    </th>
                                </tr>
                            </thead>
                            <draggable
                                tag="tbody"
                                :class="{orderable: resources.orderable}"
                                v-model="resources.resources"
                                @start="drag = true"
                                @end="dragSort"
                                :disabled="!resources.orderable"
                                ghost-class="ghost"
                                :animation="200"
                                handle=".handle"
                            >
                                <slot>
                                    <tr v-for="(resource, key) in resources.resources"
                                        :key="key + 1">
                                        <checkbox class="w-12" @input="check(resource.id,$event)"
                                                  :value="isCheckedAll"/>
                                        <td class="icon handle" v-if="resources.orderable">
                                            <i class="dripicons-menu text-xl pt-1"></i>
                                        </td>
                                        <td v-for="field in fields(resource)">
                                            <slot :name="field.attribute" :index="key"
                                                  :resource="resource" :field="field">
                                                <component :is="'index-' + field.component"
                                                           :field="field">
                                                </component>
                                            </slot>
                                        </td>
                                        <actions
                                            :via="via"
                                            :resources="resources"
                                            :resource="resource" :attaching="attaching"
                                            @delete="remove" @detach="detach" v-if="showActions"/>
                                    </tr>
                                </slot>
                            </draggable>
                        </table>
                        <pagination :info="resources.pagination" :via="this.via"
                                    v-if="resources.pagination" class="mt-6 md:flex-col"/>
                    </template>
                    <template v-else>
                        <div v-if="!resources.resources.length"
                             class="w-full py-4 flex items-center justify-center flex-col">
                            <i class="dripicons-view-thumb align-middle text-5xl text-gray-600 mb-2"></i>
                            <h1 class="text-base text-gray-600">{{ __('There is no data') }}</h1>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </loading>

</template>

<script>
    import Actions from './Actions'
    import Pagination from 'Vendor/js/components/Pagination'
    import draggable from 'vuedraggable'
    import Checkbox from './MultipleCheck/Checkbox'
    import DeleteChecked from './MultipleCheck/DeleteChecked'
    import Checked from './MultipleCheck/Checked'

    export default {
        mixins: [Checked],
        components: {
            Checkbox,
            Actions,
            Pagination,
            draggable,
            DeleteChecked
        },
        props: {
            noActions: {
                type: Boolean,
                default: false
            },
            resources: Object,
            via: Object,
            attaching: {
                type: Boolean,
                default: false
            },
            label: {
                type: String
            }
        },

        data() {
            return {
                search: this.$route.query.q,
                drag: false,
                sortField: null,
                sortDirection: null,
            }
        },

        computed: {
            searchParams() {
                const params = {
                    q: this.search
                }

                if (this.via) {
                    params.viaResource = this.via.resource
                    params.viaResourceId = this.via.id
                    params.viaRelationship = this.via.relationship
                }

                return params
            },

            createLink() {
                if (this.via) {
                    const via = [
                        `viaResource=${this.via.resource}`,
                        `viaResourceId=${this.via.id}`,
                        `viaRelationship=${this.via.relationship}`
                    ]

                    return `/${this.resources.route}/create?${via.join('&')}`
                }

                return `/${this.resources.route}/create`
            },

            attachLink() {
                return `${this.$route.path}/attach/${this.resources.route}?viaRelationship=${this.via.relationship}`
            },

            showActions() {
                if (this.noActions) {
                    return false
                }

                return Object.keys(this.resources.can).filter(ability =>
                    (this.attaching ? ['attach', 'detach'] : ['view', 'update', 'delete'])
                        .includes(ability) && this.resources.can[ability]
                ).length > 0
            },
            loaded() {
                return this.resources.hasOwnProperty('resources')
            }
        },
        methods: {
            fields(resource = null) {
                return resource ? resource.fields :
                    this.resources.resources[0].fields
            },

            performSearch() {
                this.triggerSearch(this)
            },
            triggerSearch: _.debounce((vm) => {
                vm.$router.push({
                    name: vm.$route.name,
                    query: {
                        q: vm.search
                    }
                })

                Admin.request().get(`search/${vm.resources.route}`, {
                    params: vm.searchParams
                }).then(({data}) => {
                    vm.$emit('update', data)
                })
            }, 500),

            remove(id) {
                this.$toast('question', this.__('Are you sure you want to delete selected resources?')).then(() => {
                    let via = {}

                    if (this.via) {
                        via.viaResource = this.via.resource
                        via.viaResourceId = this.via.id
                        via.viaRelationship = this.via.relationship
                    }


                    Admin.request(this.resources.route).delete(id, {
                        resource: this.resources.route,
                        ...via
                    }).then(({data}) => {
                        if (!data.toast) {
                            this.$emit('update', data)
                            this.$toast('success', this.__('Resource is deleted successfully!'))
                            this.updateCheckboxes()
                        }
                    })
                })
            },

            detach(id) {
                this.$toast('question', this.__('Are you sure you want to detach selected resources?')).then(() => {
                    let via = {}

                    if (this.via) {
                        via.viaResource = this.via.resource
                        via.viaResourceId = this.via.id
                        via.viaRelationship = this.via.relationship
                    }

                    const route = `resources/${this.resources.route}/${id}/detach/${this.via.resource}/${this.via.id}?viaRelationship=${this.via.relationship}`

                    Admin.request().delete(route)
                        .then(({data}) => {
                            this.$emit('update', data)
                            this.$toast('success', this.__('Resource is detached successfully!'))
                            // this.updateCheckboxes()
                        })
                })
            },

            dragSort() {
                this.drag = false

                let resources = this.resources.resources.map(({data}, i) => {
                    return {
                        id: data.id,
                        index: i + 1
                    }
                })

                Admin.request().post(`resources/${this.resources.name}/reorder`, {
                    resources
                })
            },

            sort(attribute, sortable) {
                if (!sortable) return

                this.sortField = attribute

                let sortQuery = {...this.$route.query}

                sortQuery[`${this.resources.name}_order`] = attribute

                if (!this.sortDirection || this.sortDirection === 'desc') {
                    this.sortDirection = 'asc'
                } else {
                    this.sortDirection = 'desc'
                }

                sortQuery[`${this.resources.name}_direction`] = this.sortDirection

                this.$router.push({
                    query: sortQuery
                })

                Admin.request(this.resources.route).index({
                    params: this.$route.query
                }).then(resources => {
                    this.$emit('update', resources.data)
                })
            },

            sortedField(attribute, sort) {
                return this.sortField === attribute
                    && this.sortDirection === sort
            },

            setSorting() {
                this.sortField = this.$route.query[this.resources.name + '_order']
                this.sortDirection = this.$route.query[this.resources.name + '_direction']
            },
        },
        created() {
            if (this.search) {
                this.performSearch()
            }

            this.setSorting()
        },
        watch: {
            resources() {
                this.setSorting()
            },
        }
    }
</script>

<style lang="scss" scoped>

    .card-wrap {
        @screen md {
            width: 100%;
            overflow-x: scroll;
        }
        .card {
            @screen md {
                width: max-content;
                min-width: 100%;
            }
        }
    }

    .table {
        thead {
            tr {
                th {
                    @apply text-sm text-gray-600 py-3 pl-3 pr-3 border border-l-0 border-r-0 border-b-2;
                    &.sortable {
                        position: relative;
                        cursor: pointer;

                        .sortings {
                            position: relative;
                            height: 18px;
                            width: 10px;
                            display: inline-block;
                            margin-bottom: -4px;

                            .sort {
                                position: absolute;
                                left: 0;
                                font-family: "Material Icons";
                                font-size: 1.1rem;
                                @apply text-gray-400;
                                &-decs {
                                    top: 0;
                                }

                                &-asc {
                                    bottom: 0;
                                }

                                &.active {
                                    @apply text-gray-700;
                                }
                            }
                        }
                    }
                }
            }
        }

        tbody {
            tr.categories-title {
                /*display: none;*/
                td {
                    @apply text-sm text-gray-600 py-3 pl-3 pr-3 border border-l-0 border-r-0 border-b-2;
                    &.sortable {
                        position: relative;
                        cursor: pointer;

                        .sortings {
                            position: relative;
                            height: 18px;
                            width: 10px;
                            display: inline-block;
                            margin-bottom: -4px;

                            .sort {
                                position: absolute;
                                left: 0;
                                font-family: "Material Icons";
                                font-size: 1.1rem;
                                @apply text-gray-400;
                                &-decs {
                                    top: 0;
                                }

                                &-asc {
                                    bottom: 0;
                                }

                                &.active {
                                    @apply text-gray-700;
                                }
                            }
                        }
                    }
                }
            }

            tr {
                td {
                    @apply text-sm text-gray-600 py-4 px-3 text-left border-b;
                }

                .icon {
                    width: 50px;
                }

                th {;

                    &.sortable {
                        position: relative;
                        cursor: pointer;

                        .sortings {
                            position: relative;
                            height: 18px;
                            width: 10px;
                            display: inline-block;
                            margin-bottom: -4px;

                            .sort {
                                position: absolute;
                                left: 0;
                                font-family: "Material Icons";
                                font-size: 1.1rem;
                                @apply text-gray-400;
                                &-decs {
                                    top: 0;
                                }

                                &-asc {
                                    bottom: 0;
                                }

                                &.active {
                                    @apply text-gray-700;
                                }
                            }
                        }
                    }
                }

                &:last-child {
                    td {
                        @apply border-none;
                    }
                }

                &.sortable-chosen {
                    color: #fff;
                    @apply bg-gray-200 text-gray-200;
                }

                &.ghost {
                    td {
                        @apply text-gray-100;
                    }

                    @apply bg-gray-100;
                }
            }
        }

        &.orderable {
            tr {
                cursor: move;
            }
        }
    }

    .flip-list-move {
        transition: transform 0.3s;
    }
</style>
