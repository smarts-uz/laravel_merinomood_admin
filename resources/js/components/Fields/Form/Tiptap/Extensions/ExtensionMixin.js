import Icon from './Icon'

export default {
    props: ['field', 'extension', 'editor', 'commands', 'isActive'],

    components: {
        Icon
    },

    data() {
        return this.extension.value || {}
    },

    methods: {
        update(payload) {
            this.$emit(`updated`, {
                component: this.$vnode.key,
                payload
            })
        }
    },
}
