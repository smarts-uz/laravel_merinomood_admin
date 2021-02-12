class RequestInterceptors {
    constructor(vm) {
        this.vm = vm
    }

    request() {
        return [
            config => {
                config.params = {...this.vm.$route.query, ...config.params}

                this.vm.top = this.vm.$route.query

                return config
            },
            error => Promise.reject(error)
        ]
    }

    response() {
        return [
            response => {
                const toasted = response.data.toast

                if (toasted) {
                    this.vm.$toast(toasted.type, toasted.message)
                }

                return response
            },
            error => {
                if (error.response.status === 401) {
                    location.href = '/admin/login'
                }

                if (error.response.status === 403) {
                    this.vm.$router.go(-1)

                    this.vm.$toast('error', this.vm.__('You are not authorized to perform this action!'))
                }

                if (error.response.status >= 500) {
                    this.vm.$toast('error', error.response.data.message)
                }

                return Promise.reject(error);
            }
        ]
    }
}

export default RequestInterceptors
