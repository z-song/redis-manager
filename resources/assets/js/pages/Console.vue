<template>
    <layout>
        <div class="command">
            
            <el-input placeholder="command" v-model="command" @keyup.enter.native="eval" @keyup.up.native="prev" @keyup.down.native="next" class="input-with-select">

                <el-select v-model="db" slot="prepend" placeholder="SELECT DB">
                    <el-option label="DEFAULT" value=""></el-option>
                    <el-option v-for="db in 16" :key="db-1" :label="'SELECT '+(db-1)" :value="db-1"></el-option>
                </el-select>

            </el-input>
        </div>

        <div class="results">
            <transition-group name="list" tag="p">
            <div v-for="(result, key) in results" :key="result.time" class="result">

                <div class="faild" v-if="!result.success">
                    <el-alert
                        :title="result.command"
                        type="error"
                        :description="result.data"
                        show-icon>
                    </el-alert>
                </div>

                <div class="success" v-else>
                    <el-tag type="primary">{{ result.command }}</el-tag>
                    <pre> {{ result.data | pretty }} </pre>
                    <i class="el-alert__closebtn el-icon-close" @click="clear(key)"></i>
                </div>

            </div>
            </transition-group>

        </div>


    </layout>
</template>

<<style>

.command {
    margin-top: 10px;
    padding-bottom: 5px;
}

.results  {
    width: 100%;
    min-height: 600px;
}

.success {
    position: relative;
    padding: 8px 16px;
    background-color: #ecf8ff;
    border-radius: 4px;
    border-left: 5px solid #50bfff;
}

.result {
    margin: 20px 0;
}

.el-select {
    width: 130px;
}

.list-enter-active {
  transition: all 1s;
}
.list-enter {
  opacity: 0;
  /* transform: translateY(100px); */
}

</style>

<script>
import Layout from "../components/Layout.vue";
import isEmpty from 'lodash/isEmpty'

export default {
  components: { Layout },

  data() {
    return {
      db: "",
      command: "",
      results: [],
      history: [],
      current: 0,
    };
  },

  filters: {
    pretty: function(value) {
      return JSON.stringify(value, null, 2);
    }
  },
  methods: {
    eval() {
      if (isEmpty(this.command)) {
        return;
      }

      this.$redis.eval(this.command, this.db).then(response => {
        response.data.time = new Date().getTime();
        response.data.command = this.command;

        this.results.unshift(response.data);

        this.record();

        this.command = "";
      });
    },

    clear(key) {
        this.results.splice(key, 1)
    },

    record() {
      this.history.push(this.command);
      this.current = this.history.length - 1;
    },
    prev() {
      console.log(this.history, this.current);
      if (!this.history[this.current - 1]) {
        return;
      }
      this.command = this.history[this.current--];
    },
    next() {
      console.log(this.history, this.current);

      if (!this.history[this.current + 1]) {
        return;
      }
      this.command = this.history[this.current++];
    },
  }
};
</script>