<template>
    <layout>
        <div>
            <div style="margin-top: 15px;">
                <el-input placeholder="*" v-model="pattern" @keyup.enter.native="scan"></el-input>

                <div class="button-group">
                  
                    <el-button class="action-btns" @click="scan" type="primary" size="small"><i class="fa fa-search"></i>&nbsp;Search</el-button>

                    <div >
                        <el-button :disabled="multipleSelection.length==0" type="danger" size="small" @click="multiDelete"><i class="fa fa-trash"></i>&nbsp;Delete
                            <span v-if="multipleSelection.length>0">{{ multipleSelection.length }} keys</span>
                        </el-button>

                        <el-dropdown split-button type="success" size="small" @command="create">
                            New
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item command="string">STRING</el-dropdown-item>
                                <el-dropdown-item command="hash">HASH</el-dropdown-item>
                                <el-dropdown-item command="list">LIST</el-dropdown-item>
                                <el-dropdown-item command="set">SET</el-dropdown-item>
                                <el-dropdown-item command="zset">ZSET</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <el-table
                    ref="multipleTable"
                    :data="keys"
                    tooltip-effect="dark"
                    style="width: 100%"
                    v-loading="loading"
                    @selection-change="handleSelectionChange">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column
                        prop="key"
                        label="Key">
                </el-table-column>
                <el-table-column
                        prop="type"
                        label="Type">
                    <template slot-scope="scope">
                        <el-tag :type="typeColor[scope.row.type]">{{ scope.row.type }}</el-tag>
                    </template>

                </el-table-column>
                <el-table-column
                        prop="ttl"
                        label="TTL(s)">
                </el-table-column>
                <el-table-column
                        label="Action"
                        width="150">
                    <template slot-scope="scope">
                        <el-button size="mini" type="primary" plain @click="edit(scope.row.key, scope.row.type)"><i class="fa fa-edit"></i></el-button>
                        <el-button size="mini" type="danger" plain @click="del(scope.row.key)"><i class="fa fa-trash"></i></el-button>
                    </template>

                </el-table-column>
            </el-table>
        </div>

    </layout>
</template>

<style>
.button-group {
  margin: 10px 0 10px 0;
}

.action-btns {
  float: right;
}
</style>

<script>
import Layout from "../components/Layout.vue";
import map from 'lodash/map'

export default {
  components: {
    Layout
  },

  data() {
    return {
      pattern: "*",
      select: "1",
      multipleSelection: [],
      keys: [],
      loading: true,
      typeColor: {
        string: "primary",
        list: "info",
        zset: "danger",
        hash: "warning",
        set: "success"
      }
    };
  },

  created() {
    document.title = "Redis manager";
  },

  methods: {
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },

    scan() {
      this.loading = true;

      this.$redis.scan(this.pattern).then(response => {
        this.keys = response.data;

        this.loading = false;
      });
    },

    del(keys) {
      if (typeof keys != "object") {
        keys = [keys];
      }

      if (!keys.length) {
        this.$message({
          type: "info",
          message: "Nothing to delete"
        });

        return;
      }

      let message;
      if (keys.length == 1) {
        message = "Delete key [" + keys[0] + "] ?";
      } else {
        message = "Delete " + keys.length + " keys?";
      }

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

            this.scan();
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Delete canceled!"
          });
        });
    },

    multiDelete() {
      let keys = map(this.multipleSelection, row => {
        return row.key;
      });

      this.del(keys);
    },

    edit(key, type) {
      this.$router.push({ path: "/edit/" + type, query: { key: key } });
    },

    create(type) {
      this.$router.push({ path: "/create/" + type });
    }
  },

  mounted() {
    this.scan();

    Bus.$on("connectionChanged", data => {
      this.scan();
    });
  },

  beforeDestroy() {
    Bus.$off("connectionChanged");
  }
};
</script>