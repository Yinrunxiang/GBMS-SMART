<template>
    <el-row class="panel m-w-1100">
        <el-col :span="24" class="contral-panel-center h-100p ">
            <aside class="w-180 h-100p ovf-hd" style="background: #eef1f6;">
                <el-menu default-active="1"  class="el-menu-vertical-demo" @select="selectCountry">
                    <div v-for="country in countryArr">
                        <el-submenu :index="country.name">
                            <template slot="title"><i class="el-icon-menu"></i>{{country.name}}</template>
                            <div v-for="address in country.addressList">
                                <el-menu-item :index="address.name" @click="menuClick">
                                    {{address.name}}</el-menu-item>
                            </div>
                        </el-submenu>
                    </div>

                    <!-- <div v-for="country in countryArr">
                                    <el-menu-item :index="country.name" @click="menuClick">
                                        <i class="el-icon-menu"></i>{{country.name}}</el-menu-item>
                                </div> -->
                </el-menu>
            </aside>
            <section class="panel-c-c">
                <div class="grid-content bg-purple-light">
                    <el-col :span="24">
                        <transition name="fade" mode="out-in" appear>
                            <div v-if="showContral">
                                <router-view></router-view>
                            </div>
                            <div v-if="!showContral">
                                <deviceList :typeList="typeList"></deviceList>
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
            for (var country of this.countryArr) {
                for (var address of country.addressList) {
                    if (key == address.name) {
                        this.typeList = address.typeList
                    }
                }
            }
        },
        menuClick() {
            this.$store.dispatch('showContral', false)
        }

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
        },
        showContral() {
            return this.$store.state.showContral
        }
    },
    mixins: [http]
}
</script>
