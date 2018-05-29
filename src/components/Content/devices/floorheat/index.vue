<template>
    <div class="right-page-content floorheat">
        <div>{{device.device}}</div>
        <div class="m-t-20">
                <i class="fa fa-thermometer icon"></i>
        </div>
        <div >
            <el-switch class="m-t-20" v-model="device.deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <div v-show="device.deviceProperty.on_off" class="m-t-20">
                <el-row>
                  <div class="m-t-20">
                    <div class="i-b temp-show" >
                      <i class="fa fa-home temp-show-icon"></i>
                      <div  class="i-b  p-t-5" >
                        <div>{{device.deviceProperty.insideTemperature}}℃</div>
                        <div>{{device.deviceProperty.insideTemperature}}F</div>
                      </div>
                    </div>
                    <div class="i-b temp-show" >
                      <div  class="i-b  p-t-5" >
                        <div>{{device.deviceProperty.outsideTemperature}}℃</div>
                        <div>{{device.deviceProperty.outsideTemperature}}F</div>
                      </div>
                      <i class="fa fa-tree temp-show-icon"></i>
                    </div>
                  </div>
                  <div class="m-t-20">
                    <div v-show="device.deviceProperty.mode == 'manual'" class="i-b now-temp-show fl btn-group" style="margin-left:26px" @click="addTemperatureButtonClick()"><i class="fa fa-caret-up now-temp-show-icon"></i></div>
                    <div class="i-b">
                      <div>{{modeTemperature}}℃</div>
                      <div>{{modeTemperature}}F</div>
                    </div>
                    <div v-show="device.deviceProperty.mode == 'manual'" class="i-b now-temp-show fr btn-group" style="margin-right:26px" @click="reduceTemperatureButtonClick()" ><i class="fa fa-caret-down now-temp-show-icon"></i></div>
                  </div>
                    <div class="m-t-20">
                        <el-button :class="{active:device.deviceProperty.mode == 'manual'}" size="small" class="mode-btn" style="margin:0" @click="manualButtonClick()"><i class="fa fa-hand-pointer-o"></i></el-button>
                        <el-button :class="{active:device.deviceProperty.mode == 'day'}"  size="small" class="mode-btn" style="margin:0"  @click="dayButtonClick()"><i class="fa fa-sun-o"></i></el-button>
                        <el-button :class="{active:device.deviceProperty.mode == 'night'}"  size="small" class="mode-btn" style="margin:0"  @click="nightButtonClick()"><i class="fa fa-moon-o"></i></el-button>
                        <el-button :class="{active:device.deviceProperty.mode == 'away'}"  size="small" class="mode-btn" style="margin:0"  @click="awayButtonClick()"><i class="fa fa-sign-out"></i></el-button>
                    </div>
                    <div class="m-t-20">
                        <el-button :class="{active:device.deviceProperty.mode == 'alarm'}"  size="small" class="mode-btn" style="margin:0" @click="alarmButtonClick()"><i class="fa fa-clock-o"></i></el-button>
                    </div>
                    <div class="m-t-20" v-show="device.deviceProperty.mode == 'alarm'">
                      <el-time-select 
                        class="i-b"
                        style="width:120px"
                        v-model="device.deviceProperty.dayTime"
                        @change = "timeChange"
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
                        v-model="device.deviceProperty.nightTime"
                        @change = "timeChange"
                        :picker-options="{
                            start: '00:00',
                            step: '00:01',
                            end: '18:30',
                            minTime:device.deviceProperty.dayTime
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
  color: #a0b6ba;
  text-align: center;
}
.floorheat .temp-show {
  width: 120px;
  height: 50px;
  font-size: 16px;
  border: 1px solid #c0ccda;
  border-radius: 5px;
}
.floorheat .temp-show .temp-show-icon {
  display: inline-block;
  width: 36px;
  height: 36px;
  font-size: 36px;
  color: #a0b6ba;
  text-align: center;
}
.floorheat .now-temp-show {
  width: 40px;
  height: 36px;
  font-size: 16px;
  border: 1px solid #c0ccda;
  border-radius: 5px;
}
.floorheat .now-temp-show .now-temp-show-icon {
  display: inline-block;
  /* width: 36px;
  height: 36px; */
  font-size: 36px;
  color: #a0b6ba;
  text-align: center;
}
.floorheat .mode-btn {
  text-align: center;
  width: 59px;
  font-size: 20px;
  color: #a0b6ba;
  border-radius: 5px;
}
.floorheat .active {
  /* background-color: #ecf6ff !important; */
  border: 1px solid #44b5df !important;
}
</style>
<script>
import acApi from "./api";
export default {
  data() {
    return {
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
      acApi.switch_change(val, this.device);
    },
    addTemperatureButtonClick() {
      acApi.addTemperatureButtonClick(this.device);
    },
    reduceTemperatureButtonClick() {
      acApi.reduceTemperatureButtonClick(this.device);
    },
    manualButtonClick() {
      acApi.manualButtonClick(this.device);
    },
    dayButtonClick() {
      acApi.dayButtonClick(this.device);
    },
    nightButtonClick() {
      acApi.nightButtonClick(this.device);
    },
    awayButtonClick() {
      acApi.awayButtonClick(this.device);
    },
    alarmButtonClick() {
      acApi.alarmButtonClick(this.device);
    },
    timeChange(val) {
      acApi.timeChange(this.device);
    }
  },
   created(){
 
  },
  mounted() {
    console.log("floorheat");
    acApi.readStatus(this.device);
  },
  destroyed(){
    acApi.closeSocket();
  },
  components: {},
  computed: {
    device() {
      var device = this.$store.state.device;
      // this.deviceProperty = device.deviceProperty;
      return device;
    },
    modeTemperature() {
      var modeTemperature = "";
      switch (this.device.deviceProperty.mode) {
        case "manual":
          modeTemperature = this.device.deviceProperty.manualTemperature;
          break;
        case "day":
          modeTemperature = this.device.deviceProperty.dayTemperature;
          break;
        case "night":
          modeTemperature = this.device.deviceProperty.nightTemperature;
          break;
        case "away":
          modeTemperature = this.device.deviceProperty.awayTemperature;
          break;
        case "alarm":
          var date = new Date(),
            hour = date.getHours(),
            minute = date.getMinutes(),
            nowTime = parseInt(hour + "" + minute);
          var dayTime = parseInt(this.device.deviceProperty.dayTime.split(":").join()),
            nightTime = parseInt(
              this.device.deviceProperty.nightTime.split(":").join()
            );
          if (nowTime >= dayTime && nowTime < nightTime) {
            modeTemperature = this.device.deviceProperty.dayTemperature;
          } else {
            modeTemperature = this.device.deviceProperty.nightTemperature;
          }

          break;
      }
      return modeTemperature;
    }
  }
};
</script>