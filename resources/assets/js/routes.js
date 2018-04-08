/**
 * Created by XYX on 2017/10/2.
 */
import VueRouter from 'vue-router'

let routes = [
    {
        path : '/',
        component : require('./components/Notify')
    }
]

export default new VueRouter({
    mode: 'history',
    routes
})
