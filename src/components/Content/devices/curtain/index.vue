<template>
   <div class="curtain-right-page">
       <div>{{device.device}}</div>
            <div>
                <i class="fa fa-columns icon" ></i>
            </div>
            <div class="fa fa-play-circle-o curtain-icon"  @click="switch_change(true)"></div>
            <div  class="fa fa-pause-circle-o curtain-icon" style="margin-left:16px" @click="stop()"></div>
            <div  class="fa fa-stop-circle-o curtain-icon" style="margin-left:16px" @click="switch_change(false)"></div>
            <!-- <el-switch v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <template>
                <div class="block">
                    <el-slider v-show="false" v-model="deviceProperty.brightness" :min='0' :max='100' :step="1" @change="slider_change">
                    </el-slider>
                </div>
            </template> -->
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
      deviceProperty: {
        brightness: 0,
        on_off: false
      },
      status: true
    };
  },
  // props: ['device'],
  methods: {
    switch_change(val) {
      this.status = val;
      curtainApi.switch_change(val, this.device, this.deviceProperty);
    },
    stop() {
      curtainApi.stop(this.status, this.device, this.deviceProperty);
    }
  },
  mounted() {
    console.log("curtain vue");
    curtainApi.readStatus(this.device, this.deviceProperty);
  },
  destroyed(){
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