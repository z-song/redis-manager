<template>
    <div class="string-form">
        <el-form :model="form" :rules="rules" ref="form" label-width="100px">
            <el-form-item label="Key" prop="key">
                <el-input v-model="form.key"></el-input>
            </el-form-item>
            <el-form-item label="Expire" prop="expire">
                <el-input-number v-model="form.expire" :min="-1"></el-input-number>
            </el-form-item>
            <el-form-item label="Value" prop="value">
                <el-input type="textarea" v-model="form.value" :rows="10"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="submitForm('form')">Create</el-button>
                <el-button @click="resetForm('form')">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<style>
.string-form {
  margin-top: 20px;
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

      rules: {
        key: [{ required: true, message: "Key is required", trigger: "change" }],
        value: [{ required: true, message: "Value is required", trigger: "change" }],
        expire: [{ required: true, message: "Expire is required", trigger: "change" }]
      }
    };
  },

  created() {
    document.title = "Redis Manager - Create string";
  },

  methods: {
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (!valid) {
          return false;
        }
      });

      this.$redis
        .setex(this.form.key, this.form.expire, this.form.value)
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

    resetForm(formName) {
      this.$refs[formName].resetFields();
    }
  }
};
</script>
