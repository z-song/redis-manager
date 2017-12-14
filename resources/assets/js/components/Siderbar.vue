<template>
    <el-menu
            :default-active="$route.path"
            :router="true"
            class="el-menu-vertical">

        <el-menu-item index="/">
            <i class="fa fa-search"></i>
            <span slot="title"><strong>Search</strong></span>
        </el-menu-item>

        <el-menu-item index="/info">
            <i class="fa fa-file-text-o"></i>
            <span slot="title"><strong>Information</strong></span>
        </el-menu-item>

        <el-menu-item index="/console">
            <i class="fa fa-terminal"></i>
            <span slot="title"><strong>Console</strong></span>
        </el-menu-item>

        <el-submenu index="/metrics">
            <template slot="title"><i class="fa fa-bar-chart"></i>Metrics</template>
            <el-menu-item index="/metrics/memory"><i class="fa fa-save"></i>Memory</el-menu-item>
            <el-menu-item index="/metrics/cpu"><i class="fa fa-database"></i>CPU</el-menu-item>
            <el-menu-item index="/metrics/clients"><i class="fa fa-sitemap"></i>Clients</el-menu-item>
            <el-menu-item index="/metrics/throughput"><i class="fa fa-sliders"></i>Throughput</el-menu-item>
        </el-submenu>

    </el-menu>
</template>
<style>
.el-menu-vertical {
  margin-top: 20px;
}

.el-menu i {
    margin-right: 5px;
}
</style>
<script>
export default {};
</script>
