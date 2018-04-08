<template>
    <div class="panel-heading">
        <div class="media">

                <ul class=" nav nav-tabs">

                    <li class="active"><a href="#no" data-toggle="tab" aria-expanded="false">通知 <span  class="badge">{{needRead}}</span></a></li>
                    <li><a href="#do" data-toggle="tab" aria-expanded="true">已读</a></li>

                </ul>
        </div>
    </div>
</template>

<script>
    export default {
        computed:{
            needRead() {
                return this.$store.getters.needreadcount
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
