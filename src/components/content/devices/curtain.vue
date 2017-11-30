<template>
    <el-col :span="24">
        <el-col :span="8">
            <div>
                <i class="fa fa-columns big-font-icon" style="font-size:200px"></i>
            </div>
        </el-col>
        <el-col :span="8" class=" m-b-20 p-l-20 p-r-20 ovf-hd" style="margin-top:170px">
            <div class="fa fa-play-circle-o curtain-icon fl" style="margin-left:16px" @click="switch_change(true)"></div>
            <div  class="fa fa-pause-circle-o curtain-icon fl" style="margin-left:16px" @click="stop()"></div>
            <div  class="fa fa-stop-circle-o curtain-icon fl" style="margin-left:16px" @click="switch_change(false)"></div>
            <!-- <el-switch v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <template>
                <div class="block">
                    <el-slider v-show="false" v-model="deviceProperty.brightness" :min='0' :max='100' :step="1" @change="slider_change">
                    </el-slider>
                </div>
            </template> -->
        </el-col>
    </el-col>
</template>

<style>
.curtain-icon {
  width: 60px;
  height: 60px;
  line-height: 60px;
  font-size: 60px;
  color: #c0ccda;
}

.curtain-icon:hover {
  color: #20a0ff;
  border-color: #20a0ff;
}
</style>


<script>
import curtainApi from "./curtain/curtain.js";
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
    console.log("light vue");
    curtainApi.readStatus(this.device, this.deviceProperty);
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