import './bootstrap'
import './components'
import './fields'
import Admin from './utils/Admin'

window.Admin = Admin

import VueRx from 'vue-rx'
import VueContentPlaceholders from 'vue-content-placeholders'
import vClickOutside from 'v-click-outside'

import './plugins/modernizr'

Vue.use(vClickOutside)
Vue.use(VueRx)
Vue.use(VueContentPlaceholders)

window.Admin.start()
