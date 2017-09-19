<template>
    <div class="panel m-w-1100" style="background-color:#fff">
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
    </div>
</template>

<script>
import ac from "../../content/devices/ac"
import light from "../../content/devices/light"
import led from "../../content/devices/led"
import music from "../../content/devices/music"

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
        music
    },
}
</script>