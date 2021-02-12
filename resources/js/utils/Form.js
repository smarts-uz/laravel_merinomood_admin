import Errors from './Errors'

class Form {
    constructor(data) {
        this.data = data
        this.errors = new Errors

        for (const value in data) {
            if (data.hasOwnProperty(value)) {
                this[value] = data[value]
            }
        }
    }

    submit(method, url) {
        const data = new FormData()
        for (const [key, value] of Object.entries(this)) {
            if (key !== 'data' && key !== 'errors') {
                if (Array.isArray(value)) {
                    for (let valueItem of value) {
                        if (value instanceof Object && !(valueItem instanceof File)) {
                            valueItem = JSON.stringify(valueItem)
                        }
                        data.append(key + '[]', valueItem)
                    }
                } else {
                    data.append(key, value)
                }
            }
        }

        return Admin.request()({
            method,
            url,
            data
        })
    }

    post(url) {
        return new Promise((resolve, reject) => {
            this.submit('post', url).then(response => {
                resolve(response.data)
            }).catch(error => {
                this.errors.set(error.response.data.errors)
                reject(error.response.data)
            })
        })
    }

    put(url) {
        return new Promise((resolve, reject) => {
            this.submit('put', url).then(response => {
                resolve(response.data)
            }).catch(error => {
                this.errors.set(error.response.data.errors)
                reject(error.response.data)
            })
        })
    }
}

export default Form
