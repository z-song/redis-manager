<template>
    <div class="hash-form">
        <el-form :model="form" :rules="rules" ref="form" label-width="100px">
            <el-form-item label="Key" prop="key">
                <el-input v-model="form.key"></el-input>
            </el-form-item>
            <el-form-item label="Expire" prop="expire">
                <el-input-number v-model="form.expire"></el-input-number>
            </el-form-item>

            <el-form-item
                    v-for="(field, index) in form.fields"
                    :label="'Field ' + index"
                    :key="index"
                    :prop="'fields.' + index + '.value'">
                <el-input v-model="field.name" class="name"></el-input>
                <el-input v-model="field.value" class="value"></el-input>
                <el-button @click.prevent="removeField(field)">Remove</el-button>
            </el-form-item>

            <el-form-item>
                <el-button @click="addField" type="success">Add Field</el-button>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="hmset('form')">Submit</el-button>
                <el-button @click="resetForm('form')">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<style>
.hash-form {
  margin-top: 20px;
}

.hash-form .name {
  width: 150px;
}

.hash-form .value {
  width: 300px;
}
</style>
<script>
export default {

  data() {
    return {
      form: {
        key: "",
        expire: -1,
        fields: [
          {
            name: "",
            value: ""
          }
        ]
      },
      rules: {
        key: [{ required: true, message: "请输入key", trigger: "change" }],
        expire: [{ required: true, message: "请输入expire", trigger: "change" }]
      }
    };
  },

  created() {
    document.title = "Redis Manager - Create Hash";
  },

  methods: {

    hmset(formName) {
      this.$refs[formName].validate(valid => {
        if (!valid) {
          return false;
        }

        this.$redis
          .hmset(this.form.key, this.form.fields, this.form.expire)
          .then(response => {
            this.$message({
              type: "success",
              message: "Saved!"
            });

              this.$router.push({ path: "/" });
          
          });
      });
    },

    resetForm(formName) {
      this.$refs[formName].resetFields();
    },

    removeField(item) {
      var index = this.form.fields.indexOf(item);
      if (index !== -1) {
        this.form.fields.splice(index, 1);
      }
    },

    addField() {
      this.form.fields.push({ value: "", name: "" });
    },
  }
};
</script>
