<template>
    <div>
        <div>
            <p>{{devicetype.name}}</p>
        </div>
        <div class="w-100p">
            <device :device="device" v-for="device in devicetype.deviceList" class="fl"></device>
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
    
    methods: {
        
    },
    created() {
        console.log('device list')
    },
    mounted(){
        console.log(this.devicetype)
    },
    props: ['devicetype'],
    components: {
        device,
    },
    computed: {
        
    },
}
</script>