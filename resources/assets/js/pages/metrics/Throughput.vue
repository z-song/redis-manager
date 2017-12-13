<template>
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span> Throughput </span>
        </div>

        <table>
            <thead>
                <tr>
                    <td class="text">Command</td>
                    <td class="text">calls</td>
                    <td class="text">usec</td>
                    <td class="text">usec_per_call</td>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(info, command) in commands" :key="command">
                    <td class="text"><el-tag>{{ command }}</el-tag></td>
                    <td><code>{{ info.calls }}</code></td>
                    <td><code>{{ info.usec }}</code></td>
                    <td><code>{{ info.usec_per_call }}</code></td>
                </tr>
            </tbody>
        </table>
    </el-card>
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

export default {
  data() {
    return {
      commands: {}
    };
  },

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
        this.$redis.info('commandstats').then(response => {
          this.commands = response.data;
        });
    }
  }
};
</script>
