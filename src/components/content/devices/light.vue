<template>
    <el-col :span="24">
        <el-col :span="8">
            <div>
                <i class="fa fa-lightbulb-o big-font-icon"></i>
            </div>
        </el-col>
        <el-col :span="8" class="m-b-20 p-l-20 p-r-20 ovf-hd" style="margin-top:80px">
            <el-switch v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <template>
                <div class="block">
                    <el-slider v-model="deviceProperty.brightness" :min='0' :max='100' :step="1" @change="slider_change">
                    </el-slider>
                </div>
            </template>
        </el-col>
    </el-col>
</template>

<script>
import lightApi from './light/light.js'
export default {
    data() {
        return {
            deviceProperty:{
                brightness : 0,
                on_off : false
            }
            

        }
    },
    // props: ['device'],
    methods: {
        switch_change(val) {
            lightApi.switch_change(val,this.device,this.deviceProperty)
        },
        slider_change(val) {
            lightApi.slider_change(val,this.device,this.deviceProperty)
        },
    },
    mounted() {
        console.log('light vue')
        lightApi.readStatus(this.device,this.deviceProperty)
    },
    components: {

    },
    computed:{
        device(){
            var device = this.$store.state.device
            return device
        }
    }
}
</script>