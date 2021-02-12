<template>
    <td class="checkbox-cell py-3 pl-3 w-10 text-sm text-gray-600 text-left cursor-pointer" @click="toggle">
        <div class="flex justify-left">
            <!--Fully or Not checked-->
            <div v-if="checked !== 'partlyChecked'"
                 :class="{ 'bg-indigo-500 border-indigo-500 ': checked === 'fullyChecked' }"
                 class="w-5 h-5 rounded border border-gray-500 inline-flex justify-center items-center bg-white">
                <svg
                    class="w-1/2 fill-current text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512">
                    <path
                        d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                    C514.5,101.703,514.499,85.494,504.502,75.496z">
                    </path>
                </svg>
            </div>

            <!--Partly checked-->
            <div v-if="checked === 'partlyChecked'"
                 class="w-5 h-5 rounded border bg-indigo-500 border-indigo-500 inline-flex justify-center items-center bg-white">
                <!--    @click="$emit('change', !value)" -->
                <svg
                    class="w-1/2 fill-current text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512">
                    <path
                        d="M 114.00,215.09
           C 114.00,215.09 179.00,215.09 179.00,215.09
             179.00,215.09 380.00,215.09 380.00,215.09
             380.00,215.09 436.00,215.09 436.00,215.09
             444.53,215.10 456.69,217.32 462.47,224.10
             470.37,233.35 470.02,247.54 470.00,259.00
             469.98,268.74 468.50,281.73 460.82,288.61
             454.21,294.53 442.55,295.99 434.00,296.00
             434.00,296.00 140.00,296.00 140.00,296.00
             140.00,296.00 84.00,296.00 84.00,296.00
             76.23,295.91 65.12,293.92 59.18,288.61
             51.50,281.73 50.02,268.74 50.00,259.00
             49.98,247.04 49.64,229.78 60.09,221.63
             65.33,217.55 71.66,216.47 78.00,215.09
             78.00,215.09 114.00,215.09 114.00,215.09 Z">
                    </path>
                </svg>
            </div>
        </div>
    </td>
</template>

<script>
    export default {
        name: "Checkbox",

        props: {
            value: {
                type: String,
                default: 'notChecked',
            },
            // via: Object,
        },

        data() {
            return {
                checked: this.value,
            }
        },

        methods: {
            toggle() {
                let checked = 'fullyChecked'

                if (this.checked === 'fullyChecked' || this.checked === 'partlyChecked') {
                    checked = 'notChecked';
                }

                this.checked = checked

                this.$emit('input', this.checked)
            }
        },

        watch: {
            value(newCondition) {
                if (newCondition) this.checked = newCondition
            },
        }
    }
</script>
