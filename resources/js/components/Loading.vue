<template>
    <div>
        <div class="loading flex justify-center pt-12" v-if="loading">
            <div class="lds-ripple"><div></div><div></div></div>
        </div>
        <slot v-else></slot>
    </div>
</template>

<script>
    export default {
        props: {
            loading: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {}
        },
    }
</script>

<style lang="scss">

</style>