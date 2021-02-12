export default {
    props: ['resources', 'via', 'label'],
    methods: {
        async fetchData(fetch = false) {
            let response

            if (fetch || !this.resources) {
                response = await Admin.request(this.route).index(this.viaParams)

                this[this.resourceKey] = response.data
            } else {
                this[this.resourceKey] = this.resources
            }
        },
    },
    created() {
        this.fetchData()
    },
    computed: {
        resourceKey() {
            return _.camelCase(this.route);
        },
        viaParams() {
            return this.via ? {
                viaResource: this.via.resource,
                viaResourceId: this.via.id,
                viaRelationship: this.via.relationship
            } : {}
        },
        hasPageParam() {
            return Object.keys(this.$route.query)
                .filter(key => key.includes('page'))
                .length > 0
        }
    },
    watch: {
        $route() {
            if (this.hasPageParam) {
                this.fetchData(true)
            }
        },
        resources() {
            this[this.resourceKey] = this.resources
        }
    }
}
