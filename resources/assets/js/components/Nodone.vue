<template>
    <div class="tab-pane active" id="no">
        <div  class="list-group">
            <button v-for="(nonotify,index) in nonotifies" :key="nonotify.id" type="button" class="list-group-item">
                <button v-on:click="readit(index,nonotify)" type="button" class="close">
                    <span aria-hidden="true">×</span>
                </button>

                    <a style="color:#33392b" v-on:click="readit(index,nonotify)" v-bind:href="nonotify.url">{{ nonotify.content }}</a>
                    <span style="color: #99cb84 "> | {{ nonotify.created_at }}</span>

            </button>
                <a ><div v-if="shownextbutton" v-on:click="showAll" class="list-group-item-info text-center"><span class="glyphicon glyphicon-menu-down " aria-hidden="true"><strong>剩余{{cantsee}}条</strong></span></div></a>

            <button  class="list-group-item">
                <button v-on:click="clearAll" class="btn btn-block btn-success"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>  清除全部</button>
            </button>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'nodone',
        computed: {

            nonotifies(){
                    return this.$store.getters.needread.slice(0,4*this.$store.state.page.now)
            },
            shownextbutton(){
                    return this.$store.getters.canshownext
            },
            cantsee(){
                    return this.$store.getters.needread.length-4*this.$store.state.page.now
            }



        },
        methods: {
            readit(index,nonotify){

                return this.$store.dispatch('readMessage',nonotify);
            },
            clearAll() {

                return this.$store.dispatch('clearMessages');
            },
            showAll() {
                return this.$store.dispatch('showMore');
            },


        }
    }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>