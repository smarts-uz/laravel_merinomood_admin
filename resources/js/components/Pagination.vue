<template>
    <div class="pagination flex justify-between align-items" v-if="info.last_page > 1">
        <p class="pagination__info md:mb-4">Показаны с {{ info.from }} до {{ info.to }} из {{ info.total }} записей</p>
        <div class="pagination__links">
            <ul class="flex">
                <li :class="{disabled: info.current_page === 1}">
                    <router-link :to="link(info.current_page - 1)"
                                 class="block text-black text-sm rounded-full">
                        <i class="material-icons">chevron_left</i>
                    </router-link>
                </li>
                <li v-for="page in info.last_page"
                    :class="{active: info.current_page === page}"
                    v-if="page < 4 || page > info.last_page - 3 || (info.current_page - 4 < page && page < info.current_page + 4)"
                >
                    <span v-if="(info.current_page > 6 && page === 3) || (info.current_page < info.last_page - 6 && page === info.last_page - 2)">&hellip;</span>
                    <router-link v-else :to="link(page)"
                                 class="block text-black text-sm rounded-full">
                        {{ page }}
                    </router-link>
                </li>
                <li :class="{disabled: info.current_page === info.last_page}">
                    <router-link :to="link(info.current_page + 1)"
                                 class="block text-black text-sm rounded-full">
                        <i class="material-icons">chevron_right</i>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            info: {
                required: true,
                type: Object
            },
            via: Object
        },

        computed: {
            pageParam() {
                return this.via ? this.via.relationship + '_page' : 'page'
            }
        },

        methods: {
            link(page) {
                let query = {}
                query[this.pageParam] = page

                return {
                    path: this.$route.path,
                    query
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .pagination {
        &__info {
            @apply text-gray-600 font-semibold inline-flex items-center;
            font-size: .85rem;
        }
        &__links {
            ul {
                li {
                    a {
                        padding: .6rem .75rem;
                        line-height: 1;
                        margin: 0 3px;
                        i {
                            font-size: inherit;
                        }
                    }
                    &.disabled {
                        a {
                            pointer-events: none;
                            @apply text-gray-600;
                        }
                    }
                    &:not(.active) {
                        a {
                            &:hover {
                                @apply bg-gray-200;
                            }
                        }
                    }
                    &.active {
                        a {
                            @apply bg-purple-600 text-white;
                        }
                    }
                }
            }
        }
    }
</style>
