<template>
    <el-card class="box-card" style="width:150px;padding:0;margin-right:30px;text-align: center;">
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
.device-box {
    padding: 0;
    margin-bottom: 10px
}
</style>

<script>
import lightApi from "../../content/devices/light/light.js"
import acApi from "../../content/devices/ac/ac.js"
import ledApi from "../../content/devices/led/led.js"
import musicApi from "../../content/devices/music/music.js"
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
            console.log('typelist:' + url + '/n');
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
                    this.device.on_ff = false
                    this.device.brightness = 0
                    lightApi.switch_change(val, this.device)
                    break
                case "ac":
                    acApi.switch_change(val, this.device)
                    break
                case "led":
                    this.device.on_off = false
                    this.device.brightness = 0
                    this.device.color = '#c0ccda'
                    this.device.red = "c0"
                    this.device.green = "cc"
                    this.device.blue = "da"
                    ledApi.switch_change(val, this.device)
                    break
                case "music":
                    // musicApi.switch_change(this.device)
                    break
            }
        },
        readOpen() {
            switch (this.device.devicetype) {
                case "light":
                    this.device.on_ff = false
                    this.device.brightness = 0
                    lightApi.readOpen(this.device)
                    break
                case "ac":
                    this.device.cooltmp = 26
                    this.device.autotmp = 0
                    this.device.heattmp = 0
                    this.device.tmp = 26
                    this.device.wind = 2
                    this.device.mode = "cool"
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
                    this.device.vol = 0,
                        this.device.on_off = false
                    this.device.music_name = "any"
                    this.device.music_autor = "any"
                    this.device.time_now = 0
                    this.device.time_over = 0
                    this.device.albumno = 0
                    this.device.albumlist = []
                    this.device.songno = 0
                    this.device.songlist = []
                    musicApi.readOpen(this.device)
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
            }
        },
    },
    created() {
        console.log('device list device')
        this.readOpen()
    },
    props: ['device'],
    components: {

    },
    computed: {
    }
}
</script>