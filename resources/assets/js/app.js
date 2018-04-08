
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//import VueRouter from 'vue-router'
import Vuex from 'vuex'
//import router from './routes'
import Notify from './components/Notify'

//Vue.use(VueRouter);
Vue.use(Vuex);
Vue.component('app',Notify);

const store = new Vuex.Store({
    state: {
        messages:[],
        page:{now:1,all:1}
        //messages:[{"id":2,"content":"\u6f5c\u6c34\u8247\u53f8\u673a\u56de\u590d\u4e86\u4f60\u7684\u6587\u7ae0\u300aLaravel\u4e2dPassPort\u5b89\u88c5\u5931\u8d25\uff1f\u300b\u3002","url":"<a href=\"\/post\/20180317\/Laravel\u4e2dPassPort\u5b89\u88c5\u5931\u8d25\uff1f\"class=\"btn btn-success\">\u67e5\u770b<\/a>","read":1,"created_at":"17\u5c0f\u65f6\u524d"},{"id":3,"content":"\u6101\u5bb9\u9a91\u58eb\u5c82\u591a\u4f59\u56de\u590d\u4e86\u4f60\u7684\u6587\u7ae0\u300aVue\u7684\u4e8b\u4ef6\u89e3\u8bfb\u300b\u3002","url":"<a href=\"\/post\/20180317\/Vue\u7684\u4e8b\u4ef6\u89e3\u8bfb\"class=\"btn btn-success\">\u67e5\u770b<\/a>","read":1,"created_at":"17\u5c0f\u65f6\u524d"},{"id":6,"content":"\u6101\u5bb9\u9a91\u58eb\u5c82\u591a\u4f59\u8d2d\u4e70\u4e86\u4f60\u7684\u6587\u7ae0\u300aVue\u7684\u4e8b\u4ef6\u89e3\u8bfb\u300b\u3002\u79ef\u5206+5","url":"<a href=\"\/post\/20180317\/\u57fa\u4e8e Swoole \u7684\u5fae\u4fe1\u626b\u7801\u767b\u5f55\"class=\"btn btn-success\">\u67e5\u770b<\/a>","read":0,"created_at":"16\u5c0f\u65f6\u524d"},{"id":7,"content":"\u6101\u5bb9\u9a91\u58eb\u5c82\u591a\u4f59\u56de\u590d\u4e86\u4f60\u7684\u6587\u7ae0\u300aVue\u7684\u4e8b\u4ef6\u89e3\u8bfb\u300b\u3002\u79ef\u5206+2","url":"<a href=\"\/post\/20180317\/Vue\u7684\u4e8b\u4ef6\u89e3\u8bfb\"class=\"btn btn-success\">\u67e5\u770b<\/a>","read":0,"created_at":"16\u5c0f\u65f6\u524d"},{"id":8,"content":"\u6101\u5bb9\u9a91\u58eb\u5c82\u591a\u4f59\u8d2d\u4e70\u4e86\u4f60\u7684\u6587\u7ae0\u300aVue\u7684\u4e8b\u4ef6\u89e3\u8bfb\u300b\u3002\u79ef\u5206+5","url":"<a href=\"\/post\/20180317\/Vue\u7684\u4e8b\u4ef6\u89e3\u8bfb\"class=\"btn btn-success\">\u67e5\u770b<\/a>","read":0,"created_at":"16\u5c0f\u65f6\u524d"},{"id":10,"content":"\u4f24\u5fc3\u6b32\u7edd\u8d2d\u4e70\u4e86\u4f60\u7684\u6587\u7ae0\u300aVue\u7684\u4e8b\u4ef6\u89e3\u8bfb\u300b\u3002\u79ef\u5206+5","url":"<a href=\"\/post\/20180317\/Vue\u7684\u4e8b\u4ef6\u89e3\u8bfb\"class=\"btn btn-success\">\u67e5\u770b<\/a>","read":0,"created_at":"16\u5c0f\u65f6\u524d"}]
    },
    getters:{
        needread:state => {
            return state.messages.filter(message => !message.read)
        },
        hasread:state => {
            return state.messages.filter(message => message.read)
        },
        needreadcount:(state,getters) => {
            return getters.needread.length
        },
        canshownext:(state) => {
            if(state.messages.length==0){
                return 0;
            }
            return state.page.now != state.page.all
        }

    },
    mutations: {
        get_message_list(state,messages){
            state.messages =messages
        },
        read_it(state,message){

            state.messages.splice(state.messages.indexOf(message),1)
            message.read = !message.read
            state.messages.push(message)

        },
        clear_all(state){
            state.messages.filter(message => !message.read).map(message=> message.read = !message.read)
        },
        get_page_list(state,pagenum){
            state.page.all =pagenum
        },
        show_all(state){
            if(state.page.now<state.page.all)
            state.page.now =state.page.now+1
        }

    },
    actions: {
        getMessage(context){
            axios.get('/api/notifies/'+userid).then(response => {
                context.commit('get_message_list',response.data)
                context.commit('get_page_list',Math.ceil(response.data.filter(message => !message.read).length/4))
            })
        },
        readMessage(context,message){
            axios.get('/api/notify/'+message.id).then(response => {

                context.commit('read_it',message)
            })
        },
        clearMessages(context){
            axios.get('/api/notify/clear/'+userid).then(response => {

                context.commit('clear_all')
            })
        },
        showMore(context){
            context.commit('show_all')
        }

    }
})



new Vue({
    el: '#app',
    store
    //router
});
