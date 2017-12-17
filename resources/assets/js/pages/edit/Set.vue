<template>
    <div class="string-form">
        <el-form :model="form" ref="form" label-width="100px">
            <el-form-item label="Key" prop="key">
                <el-input v-model="form.key" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="Expire" prop="expire">
                <el-input-number v-model="form.expire" :min="-1"></el-input-number>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="expire">Update expire</el-button>
            </el-form-item>

            <div class="line"></div>

            <el-form-item label="Member">
                <el-input v-model="addValue" class="value" placeholder="Value" @keyup.enter.native="sadd"></el-input>
            </el-form-item>

            <el-form-item>
                <el-button @click="sadd" type="primary">Add</el-button>
            </el-form-item>

            <div class="line"></div>

            <el-form-item label="Members" prop="value">
                <el-tag
                        :key="member"
                        v-for="member in form.members"
                        closable
                        :disable-transitions="false"
                        @close="srem(member)">
                    {{member}}
                </el-tag>
            </el-form-item>
        </el-form>
    </div>
</template>
<style>
.string-form {
  margin-top: 20px;
}
.el-tag + .el-tag {
  margin-left: 10px;
}

.button-new-member {
  margin-left: 10px;
  height: 32px;
  line-height: 30px;
  padding-top: 0;
  padding-bottom: 0;
}
.input-new-member {
  width: 90px;
  margin-left: 10px;
  vertical-align: bottom;
}
</style>
<script>

    import isEmpty from 'lodash/isEmpty'

export default {

  data() {
    return {
      form: {
        key: "",
        expire: -1,
        members: []
      },
      addValue: ""
    };
  },

  created() {
    document.title = "Redis Manager - Edit set";
  },

  mounted() {
    this.load(this.$route.query.key);
  },

  methods: {

    expire() {
      this.$redis.expire(this.form.key, this.form.expire).then(response => {
        this.$message({
          type: "success",
          message: "Saved!"
        });
      });
    },

    srem(member) {

      this.$confirm(
        "Remove member [" + member + "] from set [" + this.form.key + "] ?",
        "Notice",
        {
          confirmButtonText: "Remove",
          cancelButtonText: "Cancel",
          type: "warning"
        }
      ).then(() => {
        this.$redis.srem(this.form.key, member).then(response => {
          this.$message({
            type: "success",
            message: "Removed!"
          });

          this.load(this.form.key);
        });
      });
    },

    sadd() {

      this.$redis
          .sadd(this.form.key, [this.addValue], null)
          .then(response => {
            this.$message({
              type: "success",
              message: "Saved!"
            });

            this.addValue = ''
            this.load(this.form.key)
          });
    },

    load(key) {
      this.$redis.smembers(key).then(response => {
        if (isEmpty(response.data)) {
          this.$router.push({ path: "/" });
          return;
        }

        this.form.key = response.data.key;
        this.form.expire = response.data.expire;
        this.form.members = response.data.value;
      });
    }
  }
};
</script>
