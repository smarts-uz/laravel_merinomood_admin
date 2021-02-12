import Attach from './components/Views/Attach'
import EditAttached from './components/Views/EditAttached'
import VueRouter from 'vue-router'
import Vue from 'vue'
import userRoutes from 'Admin/js/routes'

Vue.use(VueRouter)

const routes = [
    {path: '/:resource/:id/attach/:relationship', component: Attach},
    {path: '/:resource/:id/attach/:relationship/edit/:attachedId', component: EditAttached},

    ...userRoutes
]

export default new VueRouter({
    mode: 'history',
    base: '/admin/',
    routes: routes
})
