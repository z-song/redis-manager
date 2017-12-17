<template>
    <div class="hash-form">
        <el-form :model="form" label-width="100px">
            <el-form-item label="Key">
                <el-input v-model="form.key" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="Expire">
                <el-input-number v-model="form.expire" :min="-1"></el-input-number>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="expire()">Update expire</el-button>
            </el-form-item>

            <div class="line"></div>

            <el-form-item label="Field">
                <el-input v-model="add.field" class="field" placeholder="Key"></el-input>
                <el-input v-model="add.value" class="value" placeholder="Value" @keyup.enter.native="hadd"></el-input>
            </el-form-item>

            <el-form-item>
                <el-button @click="hadd" type="primary">Add</el-button>
            </el-form-item>

            <div class="line"></div>

            <el-form-item label="Fields"> 
                <el-table
                  :data="old"
                  v-loading="loading"
                  style="width: 100%">
                  <el-table-column
                    prop="field"
                    label="Field">
                  </el-table-column>
                  <el-table-column
                    prop="value"
                    label="Value">
                  </el-table-column>
                  <el-table-column label="Delete">
                  <template slot-scope="scope">
                    <el-button
                      size="mini"
                      type="primary"
                      @click="hset(scope.row)"><i class="el-icon-edit"></i></el-button>
                    <el-button
                      size="mini"
                      type="danger"
                      @click="hdel(scope.row)"><i class="el-icon-delete"></i></el-button>
                  </template>
                </el-table-column>
                </el-table>
            </el-form-item>

        </el-form>

        <el-dialog
          :title="'Update ' + edit.field"
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

.hash-form .field {
  width: 150px;
}

.hash-form .value {
  width: 300px;
}

.line {
  height: 1px;
  background-color: #e6e6e6;
  margin: 10px 0 20px 0;
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
        fields: [
          {
            field: "",
            value: ""
          }
        ]
      },
      add: {
        field: "",
        value: ""
      },
      edit: {
        field: "",
        value: ""
      },
      old: [],
      loading: false,
      dialogVisible: false
    };
  },

  created() {
    document.title = "Redis Manager - Edit hash";
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

    load(key) {
      
      this.loading = true;

      this.$redis.hgetall(key).then(response => {
        if (isEmpty(response.data)) {
          this.$router.push({ path: "/" });
          return;
        }

        this.form.key = response.data.key;
        this.form.expire = response.data.expire;

        let data = [];

        for (let field in response.data.value) {
          data.push({
            field,
            value: response.data.value[field]
          });
        }
        this.old = data;
      });

      this.loading = false;
    },

    hadd() {
      this.$redis
        .hset(this.form.key, this.add.field, this.add.value)
        .then(response => {
          this.$message({
            type: "success",
            message: "Added!"
          });

          this.add = {
            field: "",
            value: ""
          };

          this.load(this.form.key);
        });
    },

    hset(field) {

      this.dialogVisible = true;

      this.edit.field = field.field;
      this.edit.value = field.value;
    },

    submitEdit() {
      this.$redis
        .hset(this.form.key, this.edit.field, this.edit.value)
        .then(response => {
          this.$message({
            type: "success",
            message: "Saved!"
          });

          this.load(this.form.key);

          this.dialogVisible = false;
        });
    },

    hdel(field) {
      this.$confirm(
        "Remove field [" + field.field + "] from hash [" + this.form.key + "] ?",
        "Notice",
        {
          confirmButtonText: "Remove",
          cancelButtonText: "Cancel",
          type: "warning"
        }
      ).then(() => {
        this.$redis.hdel(this.form.key, field.field).then(response => {
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
