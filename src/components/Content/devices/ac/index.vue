<template>
    <div class="right-page-content ac">
        <div>{{device.device}}</div>
        <div class="m-t-20">
                <i class="fa fa-thermometer icon"></i>
        </div>
        <div >
            <el-row class="m-t-30" style="font-size:30px;text-align:center">
                    <i class="fa" :class="modestyle(device.deviceProperty.mode)"></i>
                    <span> {{device.deviceProperty.tmp}} ℃</span>
                </el-row>
            <el-switch class="m-t-20" v-model="device.deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <div v-if="device.deviceProperty.on_off" class="m-t-20">
                <el-row v-if="device.deviceProperty.mode=='auto'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="device.deviceProperty.autoTmp" :min='device.deviceProperty.auto_strat' :max='device.deviceProperty.auto_end' :step="1" @change="autoTmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <span class="fl" style="line-height:36px">{{device.deviceProperty.autoTmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row v-if="device.deviceProperty.mode=='cool' || device.deviceProperty.mode=='fan'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="device.deviceProperty.coolTmp" :min='device.deviceProperty.cool_strat' :max='device.deviceProperty.cool_end' :step="1" @change="coolTmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <span class="fl" style="line-height:36px">{{device.deviceProperty.coolTmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row v-if="device.deviceProperty.mode=='heat'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="device.deviceProperty.heatTmp" :min='device.deviceProperty.heat_strat' :max='device.deviceProperty.heat_end' :step="1" @change="heatTmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <span class="fl" style="line-height:36px">{{device.deviceProperty.heatTmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row class="m-t-20">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Wind</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="device.deviceProperty.grade" :min='0' :max='3' :step="1" :format-tooltip="formatTooltip" @change="wind_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <span class="fl" style="line-height:36px">{{formatTooltip(device.deviceProperty.grade)}}</span>
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
    };
  },
  // props: ['device'],
  methods: {
      //初始化风速状态
    formatTooltip(val) {
      switch (val) {
        case 0:
          return "Auto";
          break;
        case 1:
          return "Hign";
          break;
        case 2:
          return "Medium";
          break;
        case 3:
          return "Low";
          break;
      }
    },
    //初始化模式
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
    //开关
    switch_change(val) {
      acApi.switch_change(val, this.device);
    },
    //改变自动温度
    autoTmp_change(val) {
      acApi.autoTmp_change(val, this.device);
    },
    //改变制冷温度
    coolTmp_change(val) {
      acApi.coolTmp_change(val, this.device);
    },
    //改变制热温度
    heatTmp_change(val) {
      acApi.heatTmp_change(val, this.device);
    },
    //改变风速
    wind_change(val) {
      acApi.wind_change(val, this.device);
    },
    //自动模式按钮
    autobtn() {
      acApi.autobtn(this.device);
    },
    //风扇模式按钮
    fanbtn() {
      acApi.fanbtn(this.device);
    },
    //制冷模式按钮
    coolbtn() {
      acApi.coolbtn(this.device);
    },
    //制热模式按钮
    heatbtn() {
      acApi.heatbtn(this.device);
    }
  },
  created() {
    
  },
  mounted() {
    console.log("ac");

    acApi.readStatus(this.device);
    acApi.readTmpRange(this.device);
  },
  destroyed() {
    acApi.closeSocket();
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