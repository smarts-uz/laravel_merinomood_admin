<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <form-field :field="field" :errors="errors">
        <template v-slot:translatable>
            <Tabs ref="tabs" :show-locales="field.showLocales">
                <div v-for="(item, locale) in $locales">
                    <Tab :locale="{key: locale, label: item}">
                        <input
                            v-model="value[locale]"
                            type="text"
                            class="input appearance-none block w-full leading-tight focus:outline-none focus:bg-white"
                            :class="classes(locale)"
                            :id="attribute(locale)">
                        <p class="text-red-500 text-xs italic" v-if="errors.has(attribute(locale))">
                            {{errors.get(attribute(locale)) }}
                        </p>
                    </Tab>
                </div>
            </Tabs>
        </template>
        <input
                v-model="value"
                type="text"
                class="input appearance-none block w-full leading-tight focus:outline-none focus:bg-white"
                :class="classes()"
                :id="field.attribute"
        >
    </form-field>
</template>

<script>
    import FormFieldMixin from 'Vendor/js/Mixins/FormField'
    import Tabs from 'Vendor/js/components/Tabs'
    import Tab from 'Vendor/js/components/Tab'

    export default {
        mixins: [FormFieldMixin],

        components: {Tab, Tabs}
    }
</script>
