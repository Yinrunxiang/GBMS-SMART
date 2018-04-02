<template>
    <div class="right-page-content floorheat">
        <div>{{device.device}}</div>
        <div class="m-t-20">
                <i class="fa fa-thermometer icon"></i>
        </div>
        <div >
            <el-switch class="m-t-20" v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <div v-if="deviceProperty.on_off" class="m-t-20">
                <el-row>
                  <div class="m-t-20">
                    <div class="i-b">
                      <i class="fa fa-home"></i>
                      <div  class="i-b" >
                        <div>{{deviceProperty.insideTemperature}}℃</div>
                        <div>{{deviceProperty.insideTemperature}}F</div>
                      </div>
                    </div>
                    <div class="i-b">
                      <div  class="i-b" >
                        <div>{{deviceProperty.outsideTemperature}}℃</div>
                        <div>{{deviceProperty.outsideTemperature}}F</div>
                      </div>
                      <i class="fa fa-tree"></i>
                    </div>
                  </div>
                  <div class="m-t-20">
                    <div class="i-b"><i class="fa fa-caret-up"></i></div>
                    <div class="i-b">
                      <div>{{deviceProperty.temp}}℃</div>
                      <div>{{deviceProperty.temp}}F</div>
                    </div>
                    <div class="i-b"><i class="fa fa-caret-down"></i></div>
                  </div>
                    <div class="m-t-20">
                        <el-button style="margin:0" @click="handbtn()"><i class="fa fa-hand-pointer-o"></i></el-button>
                        <el-button style="margin:0"  @click="daybtn()"><i class="fa fa-sun-o"></i></el-button>
                        <el-button style="margin:0"  @click="nightbtn()"><i class="fa fa-moon-o"></i></el-button>
                        <el-button style="margin:0"  @click="awaybtn()"><i class="fa fa-sign-out"></i></el-button>
                    </div>
                    <div class="m-t-20">
                        <el-button style="margin:0" @click="clockbtn()"><i class="fa fa-clock-o"></i></el-button>
                    </div>
                    <div class="m-t-20">
                      <el-time-select 
                        class="i-b"
                        style="width:120px"
                        v-model="deviceProperty.dayTime"
                        :picker-options="{
                            start: '00:00',
                            step: '00:01',
                            end: '18:30'
                        }"
                        placeholder="Selection time">
                        </el-time-select>
                        <el-time-select  
                        class="i-b"
                        style="width:120px"
                        v-model="deviceProperty.nightTime"
                        :picker-options="{
                            start: '00:00',
                            step: '00:01',
                            end: '18:30',
                            minTime:deviceProperty.dayTime
                        }"
                        placeholder="Selection time">
                        </el-time-select>
                    </div>
                </el-row>
            </div>
        </div>
    </div>
</template>
<style>
.right-page-content {
  margin: 0 10px;
  text-align: center;
  width: 300px;
  height: 100%;
}
.floorheat .icon {
  display: inline-block;
  width: 100px;
  height: 100px;
  font-size: 100px;
  color: #c0ccda;
  text-align: center;
}
</style>
<script>
import acApi from "./api";
export default {
  data() {
    return {
      deviceProperty: {
        on_off: false,
        temp: 26,
        mode: "cool",
        dayTime: "",
        nightTime: "",
        insideTemperature: "",
        outsideTemperature: ""
      }
    };
  },
  // props: ['device'],
  methods: {
    formatTooltip(val) {
      switch (val) {
        case 0:
          return "Auto";
          break;
        case 1:
          return "Hign";
          break;
        case 2:
          return "Medial";
          break;
        case 3:
          return "Low";
          break;
      }
    },
    modestyle(mode) {
      switch (mode) {
        case "auto":
          return "fa-refresh";
          break;
        case "cool":
          return "fa-snowflake-o";
          break;
        case "heat":
          return "fa-sun-o";
          break;
        case "fan":
          return "fa-superpowers";
          break;
      }
    },
    switch_change(val) {
      acApi.switch_change(val, this.device, this.deviceProperty);
    },
    autotmp_change(val) {
      acApi.autotmp_change(val, this.device, this.deviceProperty);
    },
    cooltmp_change(val) {
      acApi.cooltmp_change(val, this.device, this.deviceProperty);
    },
    heattmp_change(val) {
      acApi.heattmp_change(val, this.device, this.deviceProperty);
    },
    wind_change(val) {
      acApi.wind_change(val, this.device, this.deviceProperty);
    },

    autobtn() {
      acApi.autobtn(this.device, this.deviceProperty);
    },
    fanbtn() {
      acApi.fanbtn(this.device, this.deviceProperty);
    },
    coolbtn() {
      acApi.coolbtn(this.device, this.deviceProperty);
    },
    heatbtn() {
      acApi.heatbtn(this.device, this.deviceProperty);
    }
  },
  mounted() {
    console.log("ac");

    acApi.readStatus(this.device, this.deviceProperty);
    acApi.readTmpRange(this.device, this.deviceProperty);
  },
  components: {},
  computed: {
    device() {
      var device = this.$store.state.device;
      this.deviceProperty.on_off = false;
      this.deviceProperty.cooltmp = 26;
      this.deviceProperty.autotmp = 0;
      this.deviceProperty.heattmp = 0;
      this.deviceProperty.tmp = 26;
      this.deviceProperty.wind = 2;
      this.deviceProperty.mode = "cool";
      acApi.readStatus(device, this.deviceProperty);
      return device;
    }
  }
};
</script>