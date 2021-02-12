export default {
    methods: {
        $via(relationship, resource = null) {
            if (!resource) {
                resource = this[this.resource]
            }

            return {
                resource: resource.name,
                id: resource.resource.id,
                relationship
            }
        }
    }
}
