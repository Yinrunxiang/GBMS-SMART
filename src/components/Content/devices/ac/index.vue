<template>
    <div class="right-page-content ac">
        <div>{{device.device}}</div>
        <div class="m-t-20">
                <i class="fa fa-thermometer icon"></i>
        </div>
        <div >
            <el-row class="m-t-30" style="font-size:30px;text-align:center">
                    <i class="fa" :class="modestyle(deviceProperty.mode)"></i>
                    <span> {{deviceProperty.tmp}} ℃</span>
                </el-row>
            <el-switch class="m-t-20" v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <div v-if="deviceProperty.on_off" class="m-t-20">
                <el-row v-if="deviceProperty.mode=='auto'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="deviceProperty.autotmp" :min='deviceProperty.auto_strat' :max='deviceProperty.auto_end' :step="1" @change="autotmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <span class="fl" style="line-height:36px">{{deviceProperty.autotmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row v-if="deviceProperty.mode=='cool' || deviceProperty.mode=='fan'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="deviceProperty.cooltmp" :min='deviceProperty.cool_strat' :max='deviceProperty.cool_end' :step="1" @change="cooltmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <span class="fl" style="line-height:36px">{{deviceProperty.cooltmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row v-if="deviceProperty.mode=='heat'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="deviceProperty.heattmp" :min='deviceProperty.heat_strat' :max='deviceProperty.heat_end' :step="1" @change="heattmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <span class="fl" style="line-height:36px">{{deviceProperty.heattmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row class="m-t-20">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Wind</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="deviceProperty.wind" :min='0' :max='3' :step="1" :format-tooltip="formatTooltip" @change="wind_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <span class="fl" style="line-height:36px">{{formatTooltip(deviceProperty.wind)}}</span>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col class="m-t-20">
                        <el-button style="margin:0" @click="autobtn()">Auto</el-button>
                        <el-button style="margin:0"  @click="fanbtn()">Fan</el-button>
                        <el-button style="margin:0"  @click="coolbtn()">Cool</el-button>
                        <el-button style="margin:0"  @click="heatbtn()">Heat</el-button>
                    </el-col>
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
.ac .icon {
  display: inline-block;
  width: 200px;
  height: 200px;
  font-size: 200px;
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
        cooltmp: 26,
        autotmp: 0,
        heattmp: 0,
        tmp: 26,
        wind: 2,
        mode: "cool",
        cool_strat:0,
        cool_end:36,
        heat_strat:0,
        heat_end:36,
        auto_strat:0,
        auto_end:36,
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
    acApi.readTmpRange(this.device, this.deviceProperty)
  },
  destroyed(){
    acApi.closeSocket();
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
    //   acApi.readStatus(device, this.deviceProperty);
      return device;
    }
  }
};
</script>