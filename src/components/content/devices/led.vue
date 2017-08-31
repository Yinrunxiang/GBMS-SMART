<template>
    <el-col :span="10" :offset="6">
        <el-row style="text-align:center;margin-top:50px;">
            <el-switch v-model="device.on_off" @change="switch_change">
            </el-switch>
            <colorPicker v-model="device.color" v-on:change="headleChangeColor"></colorPicker>
        </el-row>
        <el-row>
            <div style="text-align:center">
                <i class="fa fa-lightbulb-o big-font-icon led-light" style="margin:0;"></i>
            </div>
        </el-row>
    </el-col>
</template>

<script>
import ledApi from './led/led.js'
import colorPicker from 'vue-color-picker'
import $ from 'jquery'
// import colorPicker from '../../Common/colorPicker'
export default {
    data() {
        return {
            
        }
    },
    methods: {
        //开关滑块
        //驱动颜色变化的数据为 255色的16位进制
        //UDP发送的颜色数据为  100色的16位进制
        switch_change(val) {
            ledApi.switch_change(val,this.device)
        },
        //当颜色值发生改变时
        headleChangeColor(val) {
            ledApi.headleChangeColor(val,this.device)

        },

    },
    mounted() {
        ledApi.readStatus(this.device)
        $('.vc-target').hide()
        $('.led-light').click(function () {
            $('.vc-target').trigger('click')
        })
        $('.vc-container').css('z-index', 999)
        $('.led-light').css('color', this.color)
    },
    computed:{
        device(){
            var device = this.$store.state.device
            device.on_off = false
            device.brightness = 0
            device.color = '#c0ccda'
            device.red = "c0"
            device.green = "cc"
            device.blue = "da"
            return device
        }
    },
    components: {
        colorPicker
    }
}
</script>