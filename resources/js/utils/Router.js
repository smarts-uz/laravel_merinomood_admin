class Router {

    constructor() {
        this.resourceRoutes = []
    }

    add(resource) {
        let dir = _.startCase(resource).split(' ').join('')
        this.resourceRoutes.push(...[
            {
                path: `/${resource}`,
                component: require(`Admin/js/views/${dir}/Index.vue`).default
            },
            {
                path: `/${resource}/create`,
                component: require(`Admin/js/views/${dir}/Create.vue`).default
            },
            {
                path: `/${resource}/:id`,
                component: require(`Admin/js/views/${dir}/Detail.vue`).default
            },
            {
                path: `/${resource}/:id/edit`,
                component: require(`Admin/js/views/${dir}/Edit.vue`).default
            }
        ])

        return this
    }

    routes() {
        return this.resourceRoutes
    }
}

export default new Router
