<template>
    <div class="right-page-content floorheat">
        <div>{{device.device}}</div>
        <div class="m-t-20">
                <i class="fa fa-thermometer icon"></i>
        </div>
        <div >
            <el-switch class="m-t-20" v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <div v-show="deviceProperty.on_off" class="m-t-20">
                <el-row>
                  <div class="m-t-20">
                    <div class="i-b temp-show" >
                      <i class="fa fa-home temp-show-icon"></i>
                      <div  class="i-b  p-t-5" >
                        <div>{{deviceProperty.insideTemperature}}℃</div>
                        <div>{{deviceProperty.insideTemperature}}F</div>
                      </div>
                    </div>
                    <div class="i-b temp-show" >
                      <div  class="i-b  p-t-5" >
                        <div>{{deviceProperty.outsideTemperature}}℃</div>
                        <div>{{deviceProperty.outsideTemperature}}F</div>
                      </div>
                      <i class="fa fa-tree temp-show-icon"></i>
                    </div>
                  </div>
                  <div class="m-t-20">
                    <div v-show="deviceProperty.mode == 'manual'" class="i-b now-temp-show fl btn-group" style="margin-left:26px" @click="addTemperatureButtonClick()"><i class="fa fa-caret-up now-temp-show-icon"></i></div>
                    <div class="i-b">
                      <div>{{modeTemperature}}℃</div>
                      <div>{{modeTemperature}}F</div>
                    </div>
                    <div v-show="deviceProperty.mode == 'manual'" class="i-b now-temp-show fr btn-group" style="margin-right:26px" @click="reduceTemperatureButtonClick()" ><i class="fa fa-caret-down now-temp-show-icon"></i></div>
                  </div>
                    <div class="m-t-20">
                        <el-button :class="{active:deviceProperty.mode == 'manual'}" size="small" class="mode-btn" style="margin:0" @click="manualButtonClick()"><i class="fa fa-hand-pointer-o"></i></el-button>
                        <el-button :class="{active:deviceProperty.mode == 'day'}"  size="small" class="mode-btn" style="margin:0"  @click="dayButtonClick()"><i class="fa fa-sun-o"></i></el-button>
                        <el-button :class="{active:deviceProperty.mode == 'night'}"  size="small" class="mode-btn" style="margin:0"  @click="nightButtonClick()"><i class="fa fa-moon-o"></i></el-button>
                        <el-button :class="{active:deviceProperty.mode == 'away'}"  size="small" class="mode-btn" style="margin:0"  @click="awayButtonClick()"><i class="fa fa-sign-out"></i></el-button>
                    </div>
                    <div class="m-t-20">
                        <el-button :class="{active:deviceProperty.mode == 'alarm'}"  size="small" class="mode-btn" style="margin:0" @click="alarmButtonClick()"><i class="fa fa-clock-o"></i></el-button>
                    </div>
                    <div class="m-t-20" v-show="deviceProperty.mode == 'alarm'">
                      <el-time-select 
                        class="i-b"
                        style="width:120px"
                        v-model="deviceProperty.dayTime"
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
                        v-model="deviceProperty.nightTime"
                        @change = "timeChange"
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
      deviceProperty: {
        on_off: false,
        manualTemperature: 26,
        dayTemperature: 26,
        nightTemperature: 26,
        awayTemperature: 26,
        alarmTemperature: 26,
        mode: "manual",
        dayTime: "",
        nightTime: "",
        insideTemperature: 26,
        outsideTemperature: 26,
        insideSensor: {
          targetSubnetID: "",
          targetDeviceID: "",
          channel: ""
        }
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
    addTemperatureButtonClick() {
      acApi.addTemperatureButtonClick(this.device, this.deviceProperty);
    },
    reduceTemperatureButtonClick() {
      acApi.reduceTemperatureButtonClick(this.device, this.deviceProperty);
    },
    manualButtonClick() {
      acApi.manualButtonClick(this.device, this.deviceProperty);
    },
    dayButtonClick() {
      acApi.dayButtonClick(this.device, this.deviceProperty);
    },
    nightButtonClick() {
      acApi.nightButtonClick(this.device, this.deviceProperty);
    },
    awayButtonClick() {
      acApi.awayButtonClick(this.device, this.deviceProperty);
    },
    alarmButtonClick() {
      acApi.alarmButtonClick(this.device, this.deviceProperty);
    },
    timeChange(val) {
      acApi.timeChange(this.device, this.deviceProperty);
    }
  },
  mounted() {
    console.log("floorheat");
    acApi.readStatus(this.device, this.deviceProperty);
  },
  destroyed(){
    acApi.closeSocket();
  },
  components: {},
  computed: {
    device() {
      var device = this.$store.state.device;
         device.deviceProperty = this.deviceProperty
      return device;
    },
    modeTemperature() {
      var modeTemperature = "";
      switch (this.deviceProperty.mode) {
        case "manual":
          modeTemperature = this.deviceProperty.manualTemperature;
          break;
        case "day":
          modeTemperature = this.deviceProperty.dayTemperature;
          break;
        case "night":
          modeTemperature = this.deviceProperty.nightTemperature;
          break;
        case "away":
          modeTemperature = this.deviceProperty.awayTemperature;
          break;
        case "alarm":
          var date = new Date(),
            hour = date.getHours(),
            minute = date.getMinutes(),
            nowTime = parseInt(hour + "" + minute);
          var dayTime = parseInt(this.deviceProperty.dayTime.split(":").join()),
            nightTime = parseInt(
              this.deviceProperty.nightTime.split(":").join()
            );
          if (nowTime >= dayTime && nowTime < nightTime) {
            modeTemperature = this.deviceProperty.dayTemperature;
          } else {
            modeTemperature = this.deviceProperty.nightTemperature;
          }

          break;
      }
      return modeTemperature;
    }
  }
};
</script>