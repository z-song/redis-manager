
<template>
    <layout>

        <el-card class="box-card">
            <div slot="header" class="clearfix">

              <span>Create </span>
              <el-dropdown @command="create">
                <span class="el-dropdown-link">
                  {{ current() }}<i class="el-icon-arrow-down el-icon--right"></i>
                </span>
                  <el-dropdown-menu slot="dropdown">
                      <el-dropdown-item v-for="type in others()" :key="type" :command="type">{{ type.toUpperCase() }}</el-dropdown-item>
                  </el-dropdown-menu>
              </el-dropdown>
            </div>

            <router-view/>

        </el-card>
    </layout>
</template>

<style>
a.nav-link {
  text-decoration: none;
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
      return last(this.$route.path.split("/")).toUpperCase();
    },

    others() {
      const current = this.current();

      return pickBy(this.types, function(type) {
        return type !== current;
      });
    },
      create(type) {
          this.$router.push({ path: "/create/" + type });
      }
  }
};
</script>

