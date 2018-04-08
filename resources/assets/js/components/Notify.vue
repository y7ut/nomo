<template>
    <div class="panel panel-default">
    <notify-head></notify-head>
    <notifies></notifies>
    </div>
</template>

<script>
    import NotifyHead from './NotifyHead'
    import Notifies from './Notifies'
    export default {
        name:'notify',
        components:{
            NotifyHead,
            Notifies
        },
        mounted(){
        this.$store.dispatch('getMessage')

    }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>