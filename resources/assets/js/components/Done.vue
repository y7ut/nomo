<template>
    <div class="tab-pane" id="do">
        <div class="list-group">
            <button v-for="notify in notifies" :key="notify.id" type="button" class="list-group-item">

                <a style="color:#33392b" v-bind:href="notify.url">{{ notify.content }}</a>

                <span style="color: #99cb84 "> | {{ notify.created_at }}</span>

            </button>
            <a href="/notifies/history"  ><div class="list-group-item-info text-center"><strong>...</strong></div></a>
            <button  class="list-group-item">
                <a href="/notifies/history" class="btn btn-block btn-info ">历史消息</a>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'done',
        computed: {
            notifies (){
                return this.$store.getters.hasread
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
