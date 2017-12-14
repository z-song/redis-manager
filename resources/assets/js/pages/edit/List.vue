<template>
    <div class="hash-form">
        <el-form :model="form" ref="form" label-width="100px">
            
            <el-form-item label="Key" prop="key">
                <el-input v-model="form.key" :disabled="true"></el-input>
            </el-form-item>

            <el-form-item label="Expire" prop="expire">
                <el-input-number v-model="form.expire"></el-input-number>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="expire()">Update expire</el-button>
            </el-form-item>

            <div class="line"></div>

            <el-form-item label="Push" prop="push">
                <el-input v-model="push"></el-input>
            </el-form-item>

            <el-form-item>
                <el-button-group>
                <el-button @click.prevent="leftPush()" type="primary">Left</el-button>
                <el-button @click.prevent="rightPush()" type="primary">Right</el-button>
                </el-button-group>
            </el-form-item>

            <el-form-item label="Members"> 
                
                <el-table
                  :data="old"
                  v-loading="loading"
                  style="width: 100%">
                  <el-table-column
                    prop="index"
                    label="Index">
                  </el-table-column>
                  <el-table-column
                    prop="value"
                    label="Value">
                  </el-table-column>
                  <el-table-column label="Action">
                  <template slot-scope="scope">
                    <el-button
                      size="mini"
                      type="primary"
                      @click="handleEdit(scope.$index, scope.row.value)"><i class="el-icon-edit"></i></el-button>
                    <el-button
                      size="mini"
                      type="danger"
                      @click="handleDelete(scope.$index)"><i class="el-icon-delete"></i></el-button>
                  </template>
                </el-table-column>
                </el-table>

            </el-form-item>

        </el-form>

        <el-dialog
          :title="'Update index '+ edit.index"
          :visible.sync="dialogVisible"
          width="30%">
          <span><el-input v-model="edit.value"></el-input></span>
          <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false">Cancel</el-button>
            <el-button type="primary" @click="submitEdit()">Submit</el-button>
          </span>
        </el-dialog>
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
    import isEmpty from 'lodash/isEmpty'

export default {
  data() {
    return {
      form: {
        key: "",
        expire: -1,
        members: [{ value: "" }]
      },
      old: [],
      push: "",
      loading: false,
      dialogVisible: false,
      edit: {
        value: "",
        index: ""
      }
    };
  },

  created() {
    document.title = "Redis Manager - Edit list";
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

    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (!valid) {
          return false;
        }

        this.$redis
          .rpush(this.form.key, this.form.members, this.form.expire)
          .then(response => {
            this.$message({
              type: "success",
              message: "Saved!"
            });

            this.$router.push({ path: "/" });
          });
      });
    },

    load(key) {
      this.loading = true;

      this.$redis.lall(key).then(response => {
        if (isEmpty(response.data)) {
          this.$router.push({ path: "/" });
          return;
        }

        this.form.key = response.data.key;
        this.form.expire = response.data.expire;

        let data = [];

        for (let index in response.data.value) {
          data.push({
            index,
            value: response.data.value[index]
          });
        }

        this.old = data;
      });

      this.loading = false;
    },

    leftPush() {
      this.$redis.lpush(this.form.key, [this.push]).then(response => {
        this.push = "";

        this.load(this.form.key);
      });
    },

    rightPush() {
      this.$redis.rpush(this.form.key, [this.push]).then(response => {
        this.push = "";

        this.load(this.form.key);
      });
    },

    handleEdit(index, value) {
      this.dialogVisible = true;
      this.edit.value = value;
      this.edit.index = index;
    },

    submitEdit() {
      this.dialogVisible = false;

      this.$redis
        .lset(this.form.key, this.edit.index, this.edit.value)
        .then(response => {
          this.$message({
            type: "success",
            message: "Updated!"
          });

          this.load(this.form.key);
        });
    },

    handleDelete(index) {
      this.$confirm(
        "Remove index [" + index + "] from " + this.form.key + "?",
        "Notice",
        {
          confirmButtonText: "Remove",
          cancelButtonText: "Cancel",
          type: "warning"
        }
      ).then(() => {
        this.$redis.ldel(this.form.key, index).then(response => {
          this.$message({
            type: "success",
            message: "Removed!"
          });

          this.load(this.form.key);
        });
      });
    }
  }
};
</script>
