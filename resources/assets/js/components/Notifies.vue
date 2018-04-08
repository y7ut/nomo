<template>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">

            <nodone></nodone>
            <done></done>
        </div>
    </div>

</template>

<script>
    import Done from './Done'
    import Nodone from './Nodone'
    export default {
        components:{
            Done,
            Nodone
        }
    }
</script>

