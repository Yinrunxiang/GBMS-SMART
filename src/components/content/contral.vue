<template>
    <el-row class="panel m-w-1100">
        <el-col :span="24" class="contral-panel-center h-100p ">
            <aside class="w-180 h-100p ovf-hd" style="background: #324057;">
                <el-menu default-active="1" theme="dark" class="el-menu-vertical-demo" @select="selectCountry">
                    <el-menu-item :index="country.name" v-for="country in countryArr">
                        <i class="el-icon-menu"></i>{{country.name}}</el-menu-item>
                </el-menu>
            </aside>
            <section class="panel-c-c">
                <div class="grid-content bg-purple-light">
                    <el-col :span="24">
                        <transition name="fade" mode="out-in" appear>
                            <div class="p-20">
                                <deviceList :devicetype="devicetype" v-for="devicetype in typeList"></deviceList>
                            </div>
                        </transition>
                    </el-col>
                </div>
            </section>
        </el-col>
        <!-- <changePwd ref="changePwd"></changePwd> -->

    </el-row>
</template>
<style>

</style>
<script>
import http from '../../assets/js/http'
import deviceList from '../Common/device/deviceList'
export default {
    data() {
        return {
            typeList: [],
        }
    },
    methods: {
        selectCountry(key, keyPath) {
            for (var item of this.countryArr) {
                if (key == item.name) {
                    this.typeList = item.typeList
                }
            }
        },
        
    },
    created() {
        console.log('contral')
    },
    mounted() {

    },
    components: {
        deviceList,
    },
    computed: {
        devices() {
            var devices = []
            for (var device of this.$store.state.devices) {
                if (device.status == 'enabled') {
                    devices.push(device)
                }
            }
            return devices
        },
        countryArr() {
            return this.$store.state.countryArr
        },
        iconstyle(type) {
            switch (type) {
                case 'light':
                    return 'fa-lightbulb-o'
                    break
                case 'ac':
                    return 'fa-thermometer'
                    break
            }
        }
    },
    mixins: [http]
}
</script>
