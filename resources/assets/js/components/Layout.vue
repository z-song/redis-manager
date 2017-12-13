<template>
    <div id='app'>
        <el-container>
            <el-header height="150px;">

                <el-row>
                    <el-col :span="6">
                        <img src="/vendor/horizon/img/horizon.svg">
                    </el-col>

                    <el-col :span="4" :offset="14">
                        <el-select v-model="current" style="width:100%;" @change="changeConnection">
                            <el-option
                                    v-for="conn in connections"
                                    :key="conn"
                                    :label="conn"
                                    :value="conn">
                            </el-option>
                        </el-select>

                    </el-col>
                </el-row>

            </el-header>
            <el-container>
                <el-aside width="200px">
                    <siderbar/>
                </el-aside>
                <el-main>
                    <slot/>
                </el-main>
            </el-container>

        </el-container>
    </div>
</template>

<style>
#app {
  width: 1140px;
  margin-left: auto;
  margin-right: auto;
}

.el-header {
  padding-top: 1rem !important;
  padding-bottom: 1rem !important;
  border-bottom: 1px solid #e6e6e6;
}

.el-footer {
  margin-top: 20px;
  border-top: 1px solid #e6e6e6;
  text-align: center;
  line-height: 60px;
}
</style>

<script>
import Siderbar from "../components/Siderbar.vue";

export default {
  components: { Siderbar },

  data() {
    return {
      connections: [],
      current: ""
    };
  },

  mounted() {
    this.setDefaultConnection();

    this.$redis.connections().then(response => {
      this.connections = response.data;
    });
  },

  methods: {
    changeConnection() {
      this.$redis.conn = this.current;

      localStorage.setItem("conn", this.current);

      Bus.$emit("connectionChanged");
    },

    setDefaultConnection() {
      (this.current = localStorage.getItem("conn") || "default"),
        (this.$redis.conn = this.current);
    }
  }
};
</script>