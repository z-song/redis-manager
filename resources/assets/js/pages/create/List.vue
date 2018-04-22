<template>
    <div class="hash-form">
        <el-form :model="form" :rules="rules" ref="form" label-width="100px">
            
            <el-form-item label="Key" prop="key">
                <el-input v-model="form.key"></el-input>
            </el-form-item>

            <el-form-item label="Expire" prop="expire">
                <el-input-number v-model="form.expire" :min="-1"></el-input-number>
            </el-form-item>

            <el-form-item
                    v-for="(member, index) in form.members"
                    :label="'Member ' + index"
                    :key="index"
                    :prop="'members.' + index + '.value'">

                <el-input v-model="member.value" class="value"></el-input>
                <el-button @click.prevent="removeMember(member)">Remove</el-button>
            </el-form-item>
            
            <el-form-item>
                <el-button @click="addMember" type="success">Add Member</el-button>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="push('form')">Submit</el-button>
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
        members: [{ value: "" }]
      },
      rules: {
        key: [{ required: true, message: "Key is required", trigger: "change" }],
        expire: [{ required: true, message: "Expire is required", trigger: "change" }],
        members: [
          {
            type: "array",
            required: true,
            message: "Please add at least one member",
            trigger: "change"
          }
        ]
      }
    };
  },

  created() {
    document.title = "Redis Manager - Create list";
  },

  methods: {

    push(formName) {
      this.$refs[formName].validate(valid => {
        if (!valid) {
          return false;
        }

        this.$redis
          .lstore(this.form.key, this.form.members, this.form.expire)
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

    removeMember(item) {
      var index = this.form.members.indexOf(item);
      if (index !== -1) {
        this.form.members.splice(index, 1);
      }
    },

    addMember() {
      this.form.members.push({ value: "" });
    },
  }
};
</script>
