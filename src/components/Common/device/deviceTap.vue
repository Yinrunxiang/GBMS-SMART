<template>
    <div class="device-tap">
        <div class="icon" @click="iconClick">
            <i class="fa" :class="iconstyle(device.devicetype)"></i>
        </div>
        <el-switch class="device-switch" v-model="device.on_off" @change="switch_change">
                </el-switch>
    </div>
</template>

<style>
.device-tap {
    position: relative;
    width:100px;
    height:50px
}
.icon{
    position: absolute;
    top:0;
    left: 0;
    padding: 6px;
    width:24px;
    height:24px;
    font-size:24px;
    color:#fff;
    background-color: #20a0ff;
    border: 3px solid #20a0ff;;
    border-radius: 24px;
}
.icon i {
    margin-left: 5px; 
}
.device-switch{
    position: absolute;
    top:9px;
    left: 41px;
    border: 2px solid #20a0ff;
    border-radius: 15px;
    margin-left:-13px;
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
        iconClick(){
            
        },
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
                    var deviceProperty = {
                        on_off: false,
                        brightness: 0,
                    }
                    lightApi.switch_change(val, this.device,deviceProperty)
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
                    ledApi.switch_change(val, this.device,deviceProperty)
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