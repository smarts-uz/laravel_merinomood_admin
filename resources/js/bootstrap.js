import Vue from 'vue'
import _ from 'lodash'
import toast from './toast'

Vue.prototype.$toast = toast

window._ = _
window.Vue = Vue
