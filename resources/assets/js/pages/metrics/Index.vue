<template>
    <layout>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Memnory</span>
            </div>
            <router-view/>
        </el-card>


    </layout>
</template>

<script>

    import Layout from "../../components/Layout.vue"

    export default {
        components: { Layout },
    }

</script>