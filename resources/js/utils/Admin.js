import Imageboss from '../Mixins/Imageboss'
import Base from '../Mixins/Base'
import router from '../router'
import UserApp from 'Admin/js/app.js'
import Request from './Request/Request'

class Admin {
    constructor() {
        this.Request = new Request
    }

    setUp() {
        return new Promise((resolve) => {
            this.request().post('ui').then(({data}) => {
                for (let variable in data.variables) {
                    Vue.prototype['$' + variable] = data.variables[variable]
                }

                resolve()
            })
        })
    }

    start() {
        this.setUp().then(() => {
            const Admin = this

            Vue.mixin(Base)

            this.vm = new Vue({
                el: '#app',
                router,
                mixins: [UserApp, Imageboss],
                data() {
                    return {
                        mobileSidebar: false,
                        top: null
                    }
                },
                created() {
                    Admin.Request.setUp(this)
                },
                computed: {
                    route() {
                        return this.$route.path
                    }
                },
                watch: {
                    route() {
                        this.mobileSidebar = false
                    }
                },
            })
        })
    }

    request(resource = null) {
        if (resource) {
            return this.Request.resource(resource)
        }

        return this.Request.resolve()
    }

    vue() {
        return this.vm
    }
}

export default new Admin
