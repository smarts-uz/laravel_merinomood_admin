import axios from 'axios'
import RequestInterceptors from './RequestInterceptors'
import RequestResource from './RequestResource'

class Request {
    constructor() {
        this.instance = axios.create({
            baseURL: '/admin/api'
        })

        this.fixPut()
    }

    resolve() {
        return this.instance
    }

    fixPut() {
        this.instance.put = (url, data = {}, config = {}) => {
            if (data instanceof FormData) {
                data.append('_method', 'PUT')
            } else {
                data['_method'] = 'PUT'
            }

            return this.instance.post(url, data, config)
        }
    }

    setUp(vm) {
        const interceptors = this.interceptors(vm)

        this.instance.interceptors.response.use(...interceptors.response())
        this.instance.interceptors.request.use(...interceptors.request())
    }

    interceptors(vm) {
        return new RequestInterceptors(vm)
    }

    resource(resource, vm) {
        return new RequestResource(this.instance, resource, vm)
    }
}

export default Request
