<template>
    <el-card class="box-card" style="width:150px;padding:0;text-align: center;" >
        <div class="device-box">
            <div @click="deviceContral(device)">
                <i class="fa fa-lightbulb-o" style="font-size:80px;color:#ccc"></i>
            </div>
            <el-switch v-model="on_off" @change="switch_change">
            </el-switch>
            
        </div>
        <div style="border-top:1px solid #515151">
            
                <p>{{device.device}}</p>
        
        </div>
    </el-card>
</template>

<style>
    .device-box{
        padding: 0;
        margin-bottom: 10px
        
    }
</style>

<script>
import lightApi from "../../content/devices/light/light.js"
export default {
    data() {
        return {
            device: '',
            on_off: false
        }

    },
    props: ['device'],
    methods: {
        deviceContral(device) {
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
                    lightApi.switch_change(val, this.brightness, this.device)
                    break
                case "ac":
                    break
                case "led":
                    break
                case "music":
                    break
            }
        },
        readOpen() {
            switch (this.device.devicetype) {
                case "light":
                    lightApi.readStatus(this.on_off, this.device)
                    break
                case "ac":
                    break
                case "led":
                    break
                case "music":
                    break
            }

        }
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