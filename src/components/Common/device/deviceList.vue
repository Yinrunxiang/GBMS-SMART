<template>
    <div class="p-l-10">
        <div v-for="devicetype in typeList">
            <el-row>
                <div style="margin-bottom:10px;padding-left:10px;height:30px;line-height:30px;background-color:#58B7FF;color:#fff;border-radius:5px">
                    <p>{{devicetype.name}}</p>
                </div>
                <div class="w-100p">
                    <div v-for="device in devicetype.deviceList">
                        <device ref="device"  :device="device" class="fl"></device>
                    </div>
                </div>
            </el-row>
        </div>
    </div>
</template>

<script>
import device from "./device.vue"
export default {
    data() {
        return {
        }
    },
    created() {
        // console.log('device list')
    },
    mounted() {
        // console.log(this.devicetype)
    },
    methods:{
        
    },
    props: ['typeList'],
    components: {
        device,
    },
    computed: {

    },
}
</script>