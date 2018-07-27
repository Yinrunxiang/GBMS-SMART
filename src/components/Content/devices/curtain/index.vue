<template>
   <div class="curtain-right-page">
       <div>{{device.device}}</div>
            <div>
                <i class="fa fa-columns icon" ></i>
            </div>
            <div class="fa fa-play-circle-o curtain-icon"  @click="switch_change(true)"></div>
            <div  class="fa fa-pause-circle-o curtain-icon" style="margin-left:16px" @click="stop()"></div>
            <div  class="fa fa-stop-circle-o curtain-icon" style="margin-left:16px" @click="switch_change(false)"></div>
   </div>
</template>

<style>
.curtain-right-page {
  margin: 0 10px;
  text-align: center;
  width: 300px;
  height: 100%;
}
.curtain-right-page .icon {
  display: inline-block;
  width: 200px;
  height: 200px;
  font-size: 200px;
  color: #c0ccda;
  text-align: center;
}
.curtain-icon {
  width: 60px;
  height: 60px;
  line-height: 50px;
  font-size: 50px;
  color: #c0ccda;
}

.curtain-icon:hover {
  color: #20a0ff;
  border-color: #20a0ff;
}
</style>


<script>
import curtainApi from "./api";
export default {
  data() {
    return {
      status: true
    };
  },
  // props: ['device'],
  methods: {
    //执行开关
    switch_change(val) {
      this.status = val;
      curtainApi.switch_change(val, this.device);
    },
    //停止
    stop() {
      curtainApi.stop(this.status, this.device);
    }
  },
  created() {
  },
  mounted() {
    console.log("curtain vue");
    //读取状态
    curtainApi.readStatus(this.device);
  },
  destroyed() {
    curtainApi.closeSocket();
  },
  components: {},
  computed: {
    device() {
      var device = this.$store.state.device;
      return device;
    }
  }
};
</script>