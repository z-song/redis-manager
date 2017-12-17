
<template>
    <layout>

        <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>Edit {{ current() }} <el-tag type="success">{{ this.$route.query.key }}</el-tag> </span>
              <el-button style="float: right;" type="danger" @click="del" size="mini"><i class="el-icon-delete"></i></el-button>
            </div>

            <router-view/>

        </el-card>
    </layout>
</template>

<style>
a.nav-link {
  text-decoration: none;
}

.line {
  height: 1px;
  background-color: #e6e6e6;
  margin: 10px 0 20px 0;
}

</style>

<script type="text/ecmascript-6">
import Layout from "../../components/Layout.vue";

import last from 'lodash/last'
import pickBy from 'lodash/pickBy'

export default {
  components: { Layout },

  data() {
    return {
      types: ["string", "hash", "list", "set", "zset"]
    };
  },

  methods: {
    current() {
      return last(this.$route.path.split("/"));
    },

    others() {
      const current = this.current();

      return pickBy(this.types, function(type) {
        return type !== current;
      });
    },

    del() {

      const keys = [this.$route.query.key];

      const message = "Delete key [" + keys[0] + "] ?";

      this.$confirm(message, "Notice", {
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
        type: "warning"
      })
        .then(() => {
          this.$redis.del(keys).then(response => {
            this.$message({
              type: "success",
              message: "Deleted!"
            });

            this.$router.push({ path: "/" });
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Delete canceled"
          });
        });
    }
  }
};
</script>

