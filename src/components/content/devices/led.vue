<template>
    <el-col :span="10" :offset="6">
        <el-row style="text-align:center;margin-top:50px;">
            <el-switch v-model="on_off" @change="switch_change">
            </el-switch>
            <colorPicker v-model="color" v-on:change="headleChangeColor"></colorPicker>
        </el-row>
        <el-row>
            <div style="text-align:center">
                <i class="fa fa-lightbulb-o big-font-icon led-light" style="margin:0;"></i>
            </div>
        </el-row>
    </el-col>
</template>

<script>
import http from '../../../assets/js/http'
import colorPicker from 'vue-color-picker'
import $ from 'jquery'
// import colorPicker from '../../Common/colorPicker'
export default {
    data() {
        return {
            on_off: false,
            brightness: 0,
            color: '#c0ccda',
            red: "c0",
            green: "cc",
            blue: "da",
            device:this.$store.state.device,
        }
    },
    methods: {
        //开关滑块
        //驱动颜色变化的数据为 255色的16位进制
        //UDP发送的颜色数据为  100色的16位进制
        switch_change(val) {
            if (val) {
                //255色转100色
                var red = _g.toHex(Math.round((parseInt('0x'+this.red) / 255) * 100))
                var green = _g.toHex(Math.round((parseInt('0x'+this.green) / 255) * 100))
                var blue = _g.toHex(Math.round((parseInt('0x'+this.blue) / 255) * 100))
                this.loading = true
                const data = {
                    params: {
                        operatorCodefst: "F0",
                        operatorCodesec: "80",
                        targetDeviceID: this.device.deviceid,
                        additionalContentData: (red + "," + green + "," + blue + ",00,00,00").split(","),
                        macAddress: this.device.mac ? this.device.mac.split(".") : "",
                        dest_address: this.device.ip ? this.device.ip : "",
                        dest_port: this.device.port ? this.device.port : "",
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
                        operatorCodefst: "F0",
                        operatorCodesec: "80",
                        targetDeviceID: this.device.deviceid,
                        additionalContentData: ("00,00,00,00,00,00").split(","),
                        macAddress: this.device.mac ? this.device.mac.split(".") : "",
                        dest_address: this.device.ip ? this.device.ip : "",
                        dest_port: this.device.port ? this.device.port : "",
                    }
                }
                this.apiGet('udp/sendUdp.php', data).then((res) => {
                    console.log('res = ', _g.j2s(res))
                    // _g.closeGlobalLoading()
                })
            }
        },
        //当颜色值发生改变时
        headleChangeColor(val) {
            // var color = val.hex
            // color = color.substring(1)
            // color = _g.strToarr(color)
            console.log(this.color)
            var color = val.hex
            $('.led-light').css('color', color)
            var red = _g.toHex(Math.round((val.rgb[0] / 255) * 100))
            var green = _g.toHex(Math.round((val.rgb[1] / 255) * 100))
            var blue = _g.toHex(Math.round((val.rgb[2] / 255) * 100))
            if (this.on_off) {
                const data = {
                    params: {
                        operatorCodefst: "F0",
                        operatorCodesec: "80",
                        targetDeviceID: this.device.deviceid,
                        additionalContentData: (red + "," + green + "," + blue + ",00,00,00").split(","),
                        // additionalContentData: ("64,64,64,00,00,00").split(","),
                        macAddress: this.device.mac ? this.device.mac.split(".") : "",
                        dest_address: this.device.ip ? this.device.ip : "",
                        dest_port: this.device.port ? this.device.port : "",
                    }
                }
                this.apiGet('udp/sendUdp.php', data).then((res) => {
                    console.log('res = ', _g.j2s(res))
                    // _g.closeGlobalLoading()
                })
            }

        },
        readStatus(e) {
            let data = {
                params: {
                    operatorCodefst: "00",
                    operatorCodesec: "33",
                    targetDeviceID: this.device.deviceid,
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
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
                    var tocolor = function (str) {
                        var col = _g.toHex(Math.round((parseInt('0x' + str)/100)*255))
                        return col
                    }
                    var msg1 = msg.substr(42, 4);
                    if (msg1 == "f081") {
                        var red = tocolor(msg.substr(52, 2))
                        var green = tocolor(msg.substr(54, 2))
                        var blue = tocolor(msg.substr(56, 2))
                        var color = "#" + red + green + blue

                        if (color != "#000000") {
                            e.on_off = true
                            e.red = red
                            e.green = green
                            e.blue = blue
                            e.color = color
                            $('.led-light').css('color', color)
                        } else {
                            e.on_off = false
                        }
                    } else if (msg1 == "0034") {

                        var red = tocolor(msg.substr(52, 2))
                        var green = tocolor(msg.substr(54, 2))
                        var blue = tocolor(msg.substr(56, 2))
                        var color = "#" + red + green + blue

                        if (color != "#000000") {
                            e.on_off = true
                            e.red = red
                            e.green = green
                            e.blue = blue
                            e.color = color
                            $('.led-light').css('color', color)
                        } else {
                            e.on_off = false
                        }
                    }
                }
            });
        }

    },
    mounted() {
        this.readStatus(this)
        $('.vc-target').hide()
        $('.led-light').click(function () {
            $('.vc-target').trigger('click')
        })
        $('.vc-container').css('z-index', 999)
        $('.led-light').css('color', this.color)
    },
    components: {
        colorPicker
    },
    mixins: [http]
}
</script>