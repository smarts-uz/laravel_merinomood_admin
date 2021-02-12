class RequestResource {
    constructor(request, resource) {
        this.request = request
        this.resource = resource
    }

    uri(url = '') {
        return `resources/${this.resource}/${url}`
            .replace(/\/$/, '')
    }

    get(url, query) {
        return this.request.get(this.uri(url), {
            params: query
        })
    }

    index(query = {}) {
        return this.get('', query)
    }

    create(query = {}) {
        return this.get('create', query)
    }

    store(data) {
        return this.request.post(this.uri(''), data)
    }

    detail(key, query = {}) {
        return this.get(key, query)
    }

    edit(key, query) {
        return this.get(`${key}/edit`, query)
    }

    update(key, data) {
        return this.request.put(this.uri(key), data)
    }

    delete(key, data) {
        return this.request.delete(this.uri(key), {data})
    }
}

export default RequestResource
