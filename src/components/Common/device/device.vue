<template>
    <el-card class="box-card" style="width:150px;padding:0;margin-right:30px;margin-bottom:16px;text-align: center;">
        <i v-if="device.warn" class="el-icon-warning device-card-warn"></i>
        <div class="device-box">
            
            <div @click="deviceContral(device)">
                <i class="fa" :class="iconstyle(device.devicetype)" style="font-size:80px;color:#ccc"></i>
            </div>
            <div style="margin-top:5px">
                <el-switch v-model="device.on_off" @change="switch_change">
                </el-switch>
            </div>

        </div>
        <div style="padding:0;margin:0;height:30px;line-height:30px;color:#aaa;border-top:1px solid #515151">
            <p style="margin:0">{{device.device}}</p>
        </div>
    </el-card>
</template>

<style>
.box-card{
    position: relative;
}
.device-box {
    padding: 0;
    margin-bottom: 10px
}
.device-card-warn{
    position: absolute;
    top:5px;
    right:5px;
    font-size: 16px;
    color:#ff4949;
}
</style>

<script>
import lightApi from "../../content/devices/light/light.js"
import acApi from "../../content/devices/ac/ac.js"
import ledApi from "../../content/devices/led/led.js"
import musicApi from "../../content/devices/music/music.js"
import cutainApi from "../../content/devices/cutain/cutain.js"
export default {
    data() {
        return {

        }

    },
    props: ['device'],
    methods: {
        deviceContral(device) {
            this.$store.dispatch('showContral', true)
            let url = '/home/contral/' + device.devicetype
            // console.log('typelist:' + url + '/n');
            this.$store.dispatch('setDevice', device)
            if (url != this.$route.path) {
                router.push(url)
            } else {
                _g.shallowRefresh(this.$route.name)
            }
        },
        switch_change(val) {
            switch (this.device.devicetype) {
                case "light":
                    var deviceProperty = {
                        on_off: false,
                        brightness: 0,
                    }
                    lightApi.switch_change(val, this.device, deviceProperty)
                    break
                case "ac":
                    this.device.on_ff = false
                    acApi.switch_change(val, this.device)
                    break
                case "led":
                    this.device.on_off = false
                    var deviceProperty = {
                        on_off: false,
                        brightness: 0,
                        color: '#c0ccda',
                        red: "c0",
                        green: "cc",
                        blue: "da",
                    }
                    ledApi.switch_change(val, this.device, deviceProperty)
                    break
                case "music":
                    // musicApi.switch_change(this.device)
                    break
                case "cutain":
                    var deviceProperty = {
                        on_off: false,
                        brightness: 0,
                    }
                    cutainApi.switch_change(val, this.device, deviceProperty)
                    break
            }
        },
        readOpen() {
            switch (this.device.devicetype) {
                case "light":
                    this.device.on_ff = false
                    lightApi.readOpen(this.device)
                    break
                case "ac":
                    this.device.on_ff = false
                    acApi.readOpen(this.device)
                    break
                case "led":
                    this.device.on_off = false
                    this.device.brightness = 0
                    this.device.color = '#c0ccda'
                    this.device.red = "c0"
                    this.device.green = "cc"
                    this.device.blue = "da"
                    ledApi.readOpen(this.device)
                    break
                case "music":
                    // this.device.on_off = false
                    // musicApi.readOpen(this.device)
                    break
                case "cutain":
                    this.device.on_ff = false
                    cutainApi.readOpen(this.device)
                    break
            }

        },
        iconstyle(type) {
            switch (type) {
                case 'light':
                    return 'fa-lightbulb-o'
                    break
                case 'led':
                    return 'fa-lightbulb-o'
                    break
                case 'ac':
                    return 'fa-thermometer'
                    break
                case 'music':
                    return 'fa-music'
                    break
                case 'cutain':
                    return 'fa-columns'
                    break
            }
        },
    },
    created() {
        // console.log('device list device')
        this.readOpen()
    },
    props: ['device'],
    components: {

    },
    computed: {
    }
}
</script>