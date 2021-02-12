<template>
    <td class="text-sm text-gray-600 p-0 text-left border-b w-28 pr-4 actions-cell">
        <div class="flex justify-around">
            <router-link :to="detailLink" class="inline-flex" v-if="attaching ? resources.can.view : resources.can.view">
                <i class="dripicons-preview"></i>
            </router-link>
            <router-link :to="updateLink" class="inline-flex" v-if="attaching ? resources.can.attach : resources.can.update">
                <i class="dripicons-pencil"></i>
            </router-link>
            <button class="inline-flex" @click="remove" v-if="attaching ? resources.can.detach : resources.can.delete">
                <i class="dripicons-trash"></i>
            </button>
        </div>
    </td>
</template>

<script>
    export default {
        props: {
            resource: {
                required: true,
                type: Object
            },
            resources: {
                required: true,
                type: Object
            },
            via: Object,
            attaching: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            detailLink() {
                return `/${this.resources.route}/${this.resource.id}`
            },
            updateLink() {
                if (this.attaching) {
                    return `/${this.via.resource}/${this.via.id}/attach/${this.resources.route}/edit/${this.resource.id}?viaRelationship=${this.via.relationship}`
                }

                return `/${this.resources.route}/${this.resource.id}/edit`
            },
        },
        methods: {
            remove() {
                if (this.attaching) {
                    this.$emit('detach', this.resource.id)
                } else {
                    this.$emit('delete', this.resource.id)
                }
            }
        },
    }
</script>

<style scoped>
    .actions-cell {
        min-width: 7rem;
    }
</style>
