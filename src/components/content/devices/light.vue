<template>
    <el-col :span="24">
        <el-col :span="8">
            <div>
                <i class="fa fa-lightbulb-o big-font-icon"></i>
            </div>
        </el-col>
        <el-col :span="8" class="m-b-20 p-l-20 p-r-20 ovf-hd" style="margin-top:80px">
            <el-switch v-model="on_off" @change="switch_change">
            </el-switch>
            <template>
                <div class="block">
                    <el-slider v-model="brightness" :min='0' :max='100' :step="1" @change="slider_change">
                    </el-slider>
                </div>
            </template>
        </el-col>
    </el-col>
</template>

<script>
import http from '../../../assets/js/http'
export default {
    data() {
        return {
            on_off: false,
            brightness: 0,
            device:this.$store.state.device
        }
    },
    // props: ['device'],
    methods: {
        switch_change(val) {
            if (val) {
                if (this.brightness == 0) this.brightness = 100;
                // var o = {};
                // o.operatorCodefst = "00";
                // o.operatorCodesec = "31";
                // o.targetDeviceID = this.device.deviceid;
                // o.additionalContentData =
                //     this.device.channel + "," + _g.toHex(this.brightness) + ",00,00";
                // o.additionalContentData = o.additionalContentData.split(",");
                // o.macAddress = this.device.mac ? this.device.mac.split(".") : "";
                // o.dest_address = this.device.ip ? this.device.ip.split(".") : "";
                // o.dest_port = this.device.port ? this.device.port.split(".") : "";
                // console.log(o)
                this.loading = true
                const data = {
                    params: {
                        operatorCodefst: "00",
                        operatorCodesec: "31",
                        targetDeviceID: this.device.deviceid,
                        additionalContentData: (this.device.channel + "," + _g.toHex(this.brightness) + ",00,00").split(","),
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
                this.brightness == 0
                // var o = {};
                // o.operatorCodefst = "00";
                // o.operatorCodesec = "31";
                // o.targetDeviceID = this.device.deviceid;
                // o.additionalContentData = this.device.channel + ",00,00,00";
                // o.additionalContentData = o.additionalContentData.split(",");
                // o.macAddress = this.device.mac ? this.device.mac.split(".") : "";
                // o.dest_address = this.device.ip ? this.device.ip.split(".") : "";
                // o.dest_port = this.device.port ? this.device.port.split(".") : "";
                this.loading = true
                const data = {
                    params: {
                        operatorCodefst: "00",
                        operatorCodesec: "31",
                        targetDeviceID: this.device.deviceid,
                        additionalContentData: (this.device.channel + ",00,00,00").split(","),
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
        slider_change(val) {

            this.brightness = val;
            // let o = {};
            // o.operatorCodefst = "00";
            // o.operatorCodesec = "31";
            // o.targetDeviceID = this.device.deviceid;
            // o.additionalContentData =
            //     this.device.channel + "," + _g.toHex(val) + ",00,00";
            // o.additionalContentData = o.additionalContentData.split(",");
            // o.macAddress = this.device.mac ? this.device.mac.split(".") : "";
            // o.dest_address = this.device.ip ? this.device.ip.split(".") : "";
            // o.dest_port = this.device.port ? this.device.port.split(".") : "";
            this.loading = true
            const data = {
                params: {
                    operatorCodefst: "00",
                    operatorCodesec: "31",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: (this.device.channel + "," + _g.toHex(val) + ",00,00").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip.split(".") : "",
                    dest_port: this.device.port ? this.device.port.split(".") : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        readStatus(e) {
            let data = {
                params: {
                    operatorCodefst: "00",
                    operatorCodesec: "33",
                    targetDeviceID: this.device.deviceid,
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
                var channel = msg.substr(52, 2);
                if (
                    subnetid.toLowerCase() == e.device.subnetid.toLowerCase() &&
                    deviceid.toLowerCase() == e.device.deviceid.toLowerCase()
                ) {
                    var msg1 = msg.substr(42, 4);
                    if (msg1 == "0032") {
                        var channel = msg.substr(50, 2);
                        if (channel.toLowerCase() == e.device.channel.toLowerCase()) {
                            var msg2 = msg.substr(54, 2);
                            if (msg2 != "00") {
                                e.on_off = true
                            } else {
                                e.on_off = false
                                e.brightness = 0
                            }
                        }
                    } else if (msg1 == "0034") {
                        var len = 50 + parseInt("0x" + e.device.channel) * 2;
                        var msg2 = msg.substr(len, 2);
                        var msg3 = parseInt("0x" + msg2);
                        if (msg2 != "00") {
                            e.on_off = true
                            e.brightness = msg3
                        } else {
                            e.on_off = false
                            e.brightness = msg3
                        }
                    }
                }
            });
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