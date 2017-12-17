<template>
    <div class="zset-form">
        <el-form label-width="100px">
            <el-form-item label="Key" prop="key">
                <el-input v-model="key" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="Expire" prop="expire">
                <el-input-number v-model="expire" :min="-1"></el-input-number>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="updateExpire()">Update expire</el-button>
            </el-form-item>

            <div class="line"></div>

            <el-form-item label="Member">
                <el-input v-model="add.member" class="member" placeholder="Member"></el-input>
                <el-input-number v-model="add.score" class="score" placeholder="Score" @keyup.enter.native="zadd"></el-input-number>
            </el-form-item>

            <el-form-item>
                <el-button @click="zadd" type="primary">Add</el-button>
            </el-form-item>

            <div class="line"></div>

            <el-form-item label="Members"> 
                <el-table
                  :data="members"
                  v-loading="loading"
                  style="width: 100%">
                  <el-table-column
                    prop="member"
                    label="Member">
                  </el-table-column>
                  <el-table-column
                    prop="score"
                    label="Score">
                  </el-table-column>
                  <el-table-column label="Remove">
                  <template slot-scope="scope">
                    <el-button
                      size="mini"
                      type="primary"
                      @click="zset(scope.row)"><i class="el-icon-edit"></i></el-button>
                    <el-button
                      size="mini"
                      type="danger"
                      @click="zrem(scope.row)"><i class="el-icon-delete"></i></el-button>
                  </template>
                </el-table-column>
                </el-table>
            </el-form-item>

        </el-form>
    
      <el-dialog
          :title="'Update ' + edit.member"
          :visible.sync="dialogVisible"
          width="30%">
          <span><el-input-number v-model="edit.score"></el-input-number></span>
          <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false">Cancel</el-button>
            <el-button type="primary" @click="submitZset()">Submit</el-button>
          </span>
        </el-dialog>

    </div>
</template>
<style>

.zset-form {
  margin-top: 20px;
}

.zset-form .member {
  width: 150px;
}

.zset-form .score {
  width: 300px;
}

</style>
<script>
    import isEmpty from 'lodash/isEmpty'

export default {
  data() {
    return {
      key: "",
      expire: -1,
      members: [],
      loading: false,
      dialogVisible: false,
      add: {
        member: "",
        score: ""
      },
      edit: {
        member: "",
        score: ""
      },
    };
  },

  created() {
    document.title = "Redis Manager - Edit sorted set";
  },

  mounted() {
    this.load(this.$route.query.key);
  },

  methods: {

    updateExpire() {
      this.$redis.expire(this.key, this.expire).then(response => {
        this.$message({
          type: "success",
          message: "Saved!"
        });
      });
    },

    zrem(member) {

      this.$confirm(
        "Remove member [" + member.member + "] from sorted set [" + this.key + "] ?",
        "Notice",
        {
          confirmButtonText: "Remove",
          cancelButtonText: "Cancel",
          type: "warning"
        }
      ).then(() => {
        this.$redis.zrem(this.key, member.member).then(response => {
          this.$message({
            type: "success",
            message: "Removed!"
          });

          this.load(this.key);
        });
      });

    },

    zadd() {

      this.$redis
          .zadd(this.key, [this.add])
          .then(response => {
            this.$message({
              type: "success",
              message: "Saved!"
            });

            this.add.member = ''
            this.add.score = ''
            this.load(this.key);
          });
    },

    zset(member) {
      this.dialogVisible = true;

      this.edit.member = member.member;
      this.edit.score = member.score;
    },

    submitZset() {
      this.$redis
          .zset(this.key, this.edit.member, this.edit.score)
          .then(response => {
            this.$message({
              type: "success",
              message: "Saved!"
            });

            this.load(this.key);
            this.dialogVisible = false;
          });
    },

    load(key) {
      
      this.loading = true;

      this.$redis.zall(key).then(response => {
        if (isEmpty(response.data)) {
          this.$router.push({ path: "/" });
          return;
        }

        this.key = response.data.key;
        this.expire = response.data.expire;

        let data = [];

        for (let member in response.data.value) {
          data.push({
            member,
            score: response.data.value[member]
          });
        }
        this.members = data;
      });

      this.loading = false;
    },

  }
};
</script>
