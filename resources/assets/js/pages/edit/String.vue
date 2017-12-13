<template>
    <div class="string-form">
        <el-form :model="form" label-width="100px">
            <el-form-item label="Key">
                <el-input v-model="form.key" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="Expire">
                <el-input-number v-model="form.expire"></el-input-number>
            </el-form-item>

          <el-form-item>
                <el-button type="primary" @click="expire()">Update expire</el-button>
            </el-form-item>

            <div class="line"></div>

            <el-form-item label="Value">
                <el-input type="textarea" v-model="form.value" :rows="10"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="save()">Save</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<style>
.string-form {
  margin-top: 20px;
}
.line {
  height: 1px;
  background-color: #e6e6e6;
  margin: 10px 0 20px 0;
}
</style>
<script>
export default {

  data() {
    return {
      form: {
        key: "",
        expire: -1,
        value: "",
      },
    };
  },

  created() {
    document.title = "Redis Manager - Edit string";
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

    save(formName) {

      this.$redis
        .set(this.form.key, this.form.value)
        .then(response => {
          this.$message({
            type: "success",
            message: "Saved!"
          });

          this.$router.push({ path: "/" });
        })
        .catch(error => {
          console.log(error.response);
        });
    },

    load(key) {
      this.$redis.get(key).then(response => {
        this.form = response.data;
      });
    }
  }
};
</script>
