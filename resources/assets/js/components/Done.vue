<template>
    <div class="tab-pane" id="do">
        <div class="list-group">
            <button v-for="notify in notifies" :key="notify.id" type="button" class="list-group-item">

                <a style="color:#33392b" v-bind:href="notify.url">{{ notify.content }}</a>

                <span style="color: #99cb84 "> | {{ notify.created_at }}</span>

            </button>
            <div class="list-group-item-info text-center"><strong>已读消息仅保存一周</strong></div>
            <button  class="list-group-item">
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
