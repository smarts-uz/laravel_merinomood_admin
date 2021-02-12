<template>
    <form-field :field="field" :errors="errors">
        <template v-slot:translatable>
            <Tabs ref="tabs">
                <div v-for="(item, locale) in $locales">
                    <Tab :locale="{key: locale, label: item}">
                        <textarea v-model="value[locale]"
                            class="appearance-none block w-full text-gray-700
                                border rounded py-2 px-4 mb-2 leading-tight
                                focus:outline-none focus:bg-white focus:border-purple-400"
                            :class="classes(locale)"
                            :id="attribute(locale)">
                        </textarea>
                        <p class="text-red-500 text-xs italic" v-if="errors.has(attribute(locale))">
                            {{ errors.get(attribute(locale)) }}
                        </p>
                    </Tab>
                </div>
            </Tabs>
        </template>
        <textarea
            v-model="value"
            class="appearance-none block w-full text-gray-700 border rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-purple-400"
            :class="classes()"
            :id="field.attribute"
        ></textarea>
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
