class Errors {
    constructor(errors = {}) {
        this.set(errors)
    }

    set(errors) {
        this.errors = errors
    }

    has(key) {
        return !!this.errors[key]
    }

    get(key) {
        return this.errors[key][0]
    }

    all() {
        return this.errors
    }

    clear(field) {
        delete this.errors[field]
    }
}

export default Errors
