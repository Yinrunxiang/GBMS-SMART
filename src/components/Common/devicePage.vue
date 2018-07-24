<template>
    <div class="panel" style="background-color:#fff">
        <div class="icon-list">
            <div @click="goBack">
                <i class="fa fa-reply"></i>
            </div>
        </div>
        <div v-if="device.devicetype=='ac'">
            <ac></ac>
        </div>
        <div v-if="device.devicetype=='light'">
            <light></light>
        </div>
        <div v-if="device.devicetype=='led'">
            <led></led>
        </div>
        <div v-if="device.devicetype=='music'">
            <music></music>
        </div>
        <div v-if="device.devicetype=='security'">
            <security></security>
        </div>
    </div>
</template>

<script>
import ac from "../../Content/devices/ac"
import light from "../../Content/devices/light"
import led from "../../Content/devices/led"
import music from "../../Content/devices/music"
import security from "../../Content/devices/security"

export default {
    data() {
        return {
        }
    },
    methods: {
        goBack() {
            this.$emit("changeContral", false)
        },
    },
    created() {

    },
    mounted() {

    },
    props: ['device'],
    components: {
        ac,
        light,
        led,
        music,
        security
    },
}
</script>