<template>
    <icon :active="isActive.underline()" @click="commands.underline">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
             class="w-3 h-3 fill-current text-gray-700">
            <path d="M22.5,21.248H1.5a1.25,1.25,0,0,0,0,2.5h21a1.25,1.25,0,0,0,0-2.5Z"/>
            <path
                d="M1.978,2.748H3.341a.25.25,0,0,1,.25.25v8.523a8.409,8.409,0,0,0,16.818,0V3a.25.25,0,0,1,.25-.25h1.363a1.25,1.25,0,0,0,0-2.5H16.3a1.25,1.25,0,0,0,0,2.5h1.363a.25.25,0,0,1,.25.25v8.523a5.909,5.909,0,0,1-11.818,0V3a.25.25,0,0,1,.25-.25H7.7a1.25,1.25,0,1,0,0-2.5H1.978a1.25,1.25,0,0,0,0,2.5Z"/>
        </svg>
    </icon>
</template>

<script>
    import Extension from '../ExtensionMixin'

    export default {
        mixins: [Extension]
    }
</script>
