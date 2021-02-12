export default {
    computed: {
        visibleFields() {
            return this.resource.resource.fields.filter(field => {
                if (! field.when) {
                   return true
                }

                return ! this.whenFields(field).filter(field => {
                    if (field.value) {
                        return !field.value.includes(field.field.value)
                    }

                    return !field.field.value
                }).length
            })
        },
        officialFields() {
            return this.resource.resource.fields.filter(field => field.hasOwnProperty('fill'))
        },
    },

    methods: {
        whenFields(field) {
            let whenFields = []

            for (let whenItem in field.when) {
                whenFields.push({
                    field: _.find(this.resource.resource.fields, {attribute: whenItem}),
                    value: field.when[whenItem]
                })
            }

            return whenFields
        },
        hasScope(attribute) {
            return this.$scopedSlots[attribute] || this.$scopedSlots[attribute + '-raw']
        },
    }
}
