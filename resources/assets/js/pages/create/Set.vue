<template>
    <div class="string-form">
        <el-form :model="form" :rules="rules" ref="form" label-width="100px">
            <el-form-item label="Key" prop="key">
                <el-input v-model="form.key"></el-input>
            </el-form-item>
            <el-form-item label="Expire" prop="expire">
                <el-input-number v-model="form.expire" :min="-1"></el-input-number>
            </el-form-item>
            <el-form-item label="Members" prop="value">
                <el-tag
                        :key="member"
                        v-for="member in form.members"
                        closable
                        :disable-transitions="false"
                        @close="handleClose(member)">
                    {{member}}
                </el-tag>
                <el-input
                        class="input-new-members"
                        v-if="inputVisible"
                        v-model="inputValue"
                        ref="saveMemberInput"
                        size="small"
                        @keyup.enter.native="handleInputConfirm"
                        @blur="handleInputConfirm"
                >
                </el-input>
                <el-button v-else class="button-new-member" size="small" @click="showInput">+ New Member</el-button>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="submitForm('form')">Submit</el-button>
                <el-button @click="resetForm('form')">Reset</el-button>
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

    import includes from 'lodash/includes'

export default {

  data() {
    return {
      form: {
        key: "",
        expire: -1,
        members: []
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
      },
      inputVisible: false,
      inputValue: ""
    };
  },

  created() {
    document.title = "Redis Manager - Create set";
  },

  mounted() {
    if (this.action === "edit" && this.$route.query.key) {
      this.load(this.$route.query.key);
    }
  },

  methods: {
    handleClose(member) {
      this.form.members.splice(this.form.members.indexOf(member), 1);
    },

    showInput() {
      this.inputVisible = true;
      this.$nextTick(_ => {
        this.$refs.saveMemberInput.$refs.input.focus();
      });
    },

    handleInputConfirm() {
      let inputValue = this.inputValue;
      if (inputValue && !includes(this.form.members, inputValue)) {
        this.form.members.push(inputValue);
      }
      this.inputVisible = false;
      this.inputValue = "";
    },

    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (!valid) {
          return false;
        }

        this.$redis
          .sadd(this.form.key, this.form.members, this.form.expire)
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
    }
  }
};
</script>
