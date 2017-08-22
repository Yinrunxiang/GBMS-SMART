<template>
    <el-col :span="24">
        <el-col :span="8">
            <div>
                <el-row class="m-t-30" style="font-size:30px;text-align:center">
                    <i class="fa" :class="modestyle(mode)"></i>
                    <span> {{tmp}} ℃</span>
                </el-row>
                <i class="fa fa-thermometer big-font-icon"></i>
            </div>
        </el-col>
        <el-col :span="12" class="m-b-20 p-l-20 p-r-20 ovf-hd" style="margin-top:80px">
            <el-switch v-model="on_off" @change="switch_change">
            </el-switch>
            <div v-show="on_off" class="m-t-20">
                <el-row v-show="mode=='auto'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="autotmp" :min='0' :max='32' :step="1" @change="autotmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4"  :offset="1">
                        <span class="fl" style="line-height:36px">{{autotmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row v-show="mode=='cool' || mode=='fan'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="cooltmp" :min='0' :max='32' :step="1" @change="cooltmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4"  :offset="1">
                        <span class="fl" style="line-height:36px">{{cooltmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row v-show="mode=='heat'">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Temperature</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="heattmp" :min='0' :max='32' :step="1" @change="heattmp_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4"  :offset="1">
                        <span class="fl" style="line-height:36px">{{heattmp}} ℃</span>
                    </el-col>
                </el-row>
                <el-row class="m-t-20">
                    <el-col :span="4">
                        <span class="fr" style="line-height:36px">Wind</span>
                    </el-col>
                    <el-col :span="14" :offset="1">
                        <template>
                            <div>
                                <el-slider v-model="wind" :min='0' :max='3' :step="1" :format-tooltip="formatwind(wind)" @change="wind_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <el-col :span="4"  :offset="1">
                        <span class="fl" style="line-height:36px">{{formatwind(wind)}}</span>
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
import http from '../../../assets/js/http'
export default {
    data() {
        return {
            on_off: false,
            cooltmp: 0,
            autotmp: 0,
            heattmp: 0,
            tmp: 0,
            wind: 0,
            mode: "",
            device:this.$store.state.device,
        }
    },
    // props: ['device'],
    methods: {
        switch_change(val) {
            if (val) {
                this.loading = true
                const data = {
                    params: {
                        operatorCodefst: "E3",
                        operatorCodesec: "D8",
                        targetDeviceID: this.device.deviceid,
                        additionalContentData: ("03,01").split(","),
                        macAddress: this.device.mac ? this.device.mac.split(".") : "",
                        dest_address: this.device.ip ? this.device.ip.split(".") : "",
                        dest_port: this.device.port ? this.device.port.split(".") : "",
                    }
                }
                this.apiGet('udp/sendUdp.php', data).then((res) => {
                    console.log('res = ', _g.j2s(res))
                    // _g.closeGlobalLoading()
                })
            } else {
                this.loading = true
                const data = {
                    params: {
                        operatorCodefst: "E3",
                        operatorCodesec: "D8",
                        targetDeviceID: this.device.deviceid,
                        additionalContentData: ("03,00").split(","),
                        macAddress: this.device.mac ? this.device.mac.split(".") : "",
                        dest_address: this.device.ip ? this.device.ip.split(".") : "",
                        dest_port: this.device.port ? this.device.port.split(".") : "",
                    }
                }
                this.apiGet('udp/sendUdp.php', data).then((res) => {
                    console.log('res = ', _g.j2s(res))
                    // _g.closeGlobalLoading()
                })
            }
        },
        autotmp_change(val) {

            this.autotmp = val;
            this.loading = true
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("08," + _g.toHex(val)).split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        cooltmp_change(val) {

            this.cooltmp = val;
            this.loading = true
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("04," + _g.toHex(val)).split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        heattmp_change(val) {

            this.heattmp = val;
            this.loading = true
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("07," + _g.toHex(val)).split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        wind_change(val) {

            this.wind = val;
            this.loading = true
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("05," + _g.toHex(val)).split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        formatwind(wind) {
            switch (wind) {
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
        autobtn() {
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("06,03").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        fanbtn() {
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("06,02").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        coolbtn() {
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("06,00").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        heatbtn() {
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("06,01").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        readStatus(e) {
            let data = {
                params: {
                    operatorCodefst: "E0",
                    operatorCodesec: "EC",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("00").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            console.log(this.device)
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
            // var socket = window.socket("http://" + document.domain + ":2120");
            window.socketio.removeAllListeners("new_msg");
            window.socketio.on("new_msg", function (msg) {
                var subnetid = msg.substr(34, 2);
                var deviceid = msg.substr(36, 2);
                // var channel = msg.substr(52, 2);
                if (
                    subnetid.toLowerCase() == e.device.subnetid.toLowerCase() &&
                    deviceid.toLowerCase() == e.device.deviceid.toLowerCase()
                ) {
                    //操作码
                    var msg1 = msg.substr(42, 4);
                    if (msg1.toLowerCase() == "e0ed") {
                        // var channel = msg.substr(50, 2);
                        // if (channel.toLowerCase() == item.channel.toLowerCase()) {
                        //空调开关
                        var ac = msg.substr(50, 2);
                        if (ac != "00") {
                            e.on_off = true
                            // brightnessSlider.slider("setValue", msg2);
                        } else {
                            e.on_off = false
                        }
                        //制冷温度
                        var cooltempdata = msg.substr(52, 2);
                        e.cooltmp = parseInt("0x" + cooltempdata);

                        //制热温度
                        var heattempdata = msg.substr(60, 2);
                        e.heattmp = parseInt("0x" + heattempdata);

                        //自动温度
                        var autotempdata = msg.substr(64, 2);
                        e.autotmp = parseInt("0x" + autotempdata);

                        //模式
                        var mode = msg.substr(54, 2);
                        if (
                            [
                                "00",
                                "01",
                                "02",
                                "03",
                                "04",
                                "05",
                                "06",
                                "07",
                                "08",
                                "09"
                            ].indexOf(mode) != -1
                        ) {
                            e.mode = 'cool'
                        } else if (
                            [
                                "10",
                                "11",
                                "12",
                                "13",
                                "14",
                                "15",
                                "16",
                                "17",
                                "18",
                                "19"
                            ].indexOf(mode) != -1
                        ) {
                            e.mode = 'heat'
                        } else if (
                            [
                                "20",
                                "21",
                                "22",
                                "23",
                                "24",
                                "25",
                                "26",
                                "27",
                                "28",
                                "29"
                            ].indexOf(mode) != -1
                        ) {
                            e.mode = 'fan'
                        } else {
                            e.mode = 'auto'
                        }

                        //风量大小
                        var fan = msg.substr(56, 2);
                        e.wind = parseInt("0x" + fan);
                        //local flag
                        var flag = msg.substr(58, 2);
                        //当前温度current
                        var current = msg.substr(60, 2);
                        e.tmp = parseInt("0x" + current)
                    } else if (msg1.toLowerCase() == "e3d9") {
                        var type = msg.substr(50, 2);
                        var value = msg.substr(52, 2);
                        switch (type) {
                            case "03":
                                if (value == "01") {
                                    e.on_off = true
                                } else {
                                    e.on_off = false
                                }
                                break;
                            case "04":
                                e.cooltmp = parseInt("0x" + value);
                                break;
                            case "05":
                                e.wind = parseInt("0x" + value);
                                break;
                            case "06":
                                value = parseInt("0x" + value);
                                switch (value) {
                                    case 0:
                                        e.mode = "cool"
                                        break;
                                    case 1:
                                        e.mode = "heat"
                                        break;
                                    case 2:
                                        e.mode = "fan"
                                        break;
                                    case 3:
                                        e.mode = "auto"
                                        break;
                                }
                                break;
                            case "07":
                                e.heattemp = parseInt("0x" + value);
                                break;
                            case "08":
                                e.autotemp = parseInt("0x" + value);
                                break;
                        }
                    }
                }
            });
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
        }

    },
    mounted() {
        this.readStatus(this)
    },
    components: {

    },
    mixins: [http]
}
</script>