<template>
    <el-col :span="24">
        <el-col :span="8">
            <div>
                <el-row class="m-t-30" style="font-size:30px;text-align:center">
                    <i class="fa" :class="modestyle(deviceProperty.mode)"></i>
                    <span> {{deviceProperty.tmp}} ℃</span>
                </el-row>
                <i class="fa fa-thermometer big-font-icon"></i>
            </div>
        </el-col>
        <el-col :span="12" class="m-b-20 p-l-20 p-r-20 ovf-hd" style="margin-top:80px">
            <el-switch v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <div v-if="deviceProperty.on_off" class="m-t-20">
                <el-row v-if="deviceProperty.mode=='auto'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="deviceProperty.autotmp" :min='0' :max='32' :step="1" @change="autotmp_change">
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
                                <el-slider v-model="deviceProperty.cooltmp" :min='0' :max='32' :step="1" @change="cooltmp_change">
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
                                <el-slider v-model="deviceProperty.heattmp" :min='0' :max='32' :step="1" @change="heattmp_change">
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
                <el-col :span="20" :offset="4" class="m-t-20">
                    <el-button @click="autobtn()">Auto</el-button>
                    <el-button @click="fanbtn()">Fan</el-button>
                    <el-button @click="coolbtn()">Cool</el-button>
                    <el-button @click="heatbtn()">Heat</el-button>
                </el-col>
            </div>
        </el-col>
    </el-col>
</template>

<script>
import acApi from './ac/ac.js'
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
                mode: "cool"
            }
        }
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
                    return "fa-refresh"
                    break
                case "cool":
                    return "fa-snowflake-o"
                    break
                case "heat":
                    return "fa-sun-o"
                    break
                case "fan":
                    return "fa-superpowers"
                    break
            }
        },
        switch_change(val) {
            acApi.switch_change(val, this.device, this.deviceProperty)
        },
        autotmp_change(val) {
            acApi.autotmp_change(val, this.device, this.deviceProperty)
        },
        cooltmp_change(val) {
            acApi.cooltmp_change(val, this.device, this.deviceProperty)
        },
        heattmp_change(val) {
            acApi.heattmp_change(val, this.device, this.deviceProperty)
        },
        wind_change(val) {
            acApi.wind_change(val, this.device, this.deviceProperty)
        },

        autobtn() {
            acApi.autobtn(this.device, this.deviceProperty)
        },
        fanbtn() {
            acApi.fanbtn(this.device, this.deviceProperty)
        },
        coolbtn() {
            acApi.coolbtn(this.device, this.deviceProperty)
        },
        heatbtn() {
            acApi.heatbtn(this.device, this.deviceProperty)
        },


    },
    mounted() {
        console.log("ac")

        acApi.readStatus(this.device, this.deviceProperty)
    },
    components: {

    },
    computed: {
        device() {
            var device = this.$store.state.device

            return device
        }
    },
}
</script>