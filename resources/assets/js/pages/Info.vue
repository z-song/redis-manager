<template>
    <layout>

        <el-row :gutter=10
            v-loading="loading">
            <el-col :span=12 >
                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> Server </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.Server" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><code>{{ val }}</code></td>
                        </tr>
                    </table>
                </el-card>

                <br />

                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> Clients </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.Clients" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><code>{{ val }}</code></td>
                        </tr>
                    </table>
                </el-card>

                <br />

                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> Stats </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.Stats" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><code>{{ val }}</code></td>
                        </tr>
                    </table>
                </el-card>

                <br />

                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> Replication </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.Replication" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><code>{{ val }}</code></td>
                        </tr>
                    </table>
                </el-card>

            </el-col>

            <el-col :span=12>

                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> Memory </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.Memory" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><code>{{ val }}</code></td>
                        </tr>
                    </table>
                </el-card>

                <br />

                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> CPU </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.CPU" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><code>{{ val }}</code></td>
                        </tr>
                    </table>
                </el-card>

                <br />

                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> Keyspace </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.Keyspace" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><pre>{{ val }}</pre></td>
                        </tr>
                    </table>
                </el-card>

                <br />

                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> Persistence </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.Persistence" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><code>{{ val }}</code></td>
                        </tr>
                    </table>
                </el-card>

                <br />

                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> Cluster </span>
                    </div>

                    <table>
                        <tr v-for="(val, key) in info.Cluster" :key="key">
                            <td class="text">{{ key }}</td>
                            <td><code>{{ val }}</code></td>
                        </tr>
                    </table>
                </el-card>

            </el-col>

        </el-row>

    </layout>
</template>
<style>
code {
  color: #5e6d82;
  background-color: #e6effb;
  margin: 0 4px;
  display: inline-block;
  padding: 1px 5px;
  font-size: 12px;
  border-radius: 3px;
  height: 18px;
  line-height: 18px;
}

table {
  width: 100%;
}

td.text {
  font-family: monospace;
}

table td {
  border-bottom: 1px solid #e6ebf5;
  padding: 5px 0;
  min-width: 0;
  box-sizing: border-box;
  text-overflow: ellipsis;
  vertical-align: middle;
  position: relative;
  border-collapse: separate;
}
</style>
<script>
import Layout from "../components/Layout.vue";
export default {
  data() {
    return {
      info: {},
      loading: false
    };
  },

  components: { Layout },

  mounted() {
    this.fetchInfo();

    Bus.$on("connectionChanged", data => {
      this.fetchInfo();
    });
  },

  beforeDestroy() {
    Bus.$off("connectionChanged");
  },

  methods: {
    fetchInfo() {
      (this.loading = true),
        this.$redis.info().then(response => {
          this.info = response.data;

          this.loading = false;
        });
    }
  }
};
</script>
