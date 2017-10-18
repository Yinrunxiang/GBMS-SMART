<template>
    <el-row class="panel m-w-1100">
        <el-col :span="24" class="contral-panel-center h-100p ">
            <aside class="w-180 h-100p ovf-hd" style="background: #eef1f6;">
                <el-menu default-active="1" class="el-menu-vertical-demo" @select="selectCountry">
                    <div v-for="country in countryArr">
                        <el-submenu :index="country.name">

                            <template slot="title">
                                <el-badge :value="country.warn" class="country-badge-div">
                                    <i class="el-icon-menu"></i>{{country.name}}
                                </el-badge>
                            </template>

                            <div v-for="address in country.addressList">
                                <el-badge :value="address.warn" class="address-badge-div">
                                    <el-menu-item :index="address.name" @click="menuClick">
                                        {{address.name}}</el-menu-item>
                                </el-badge>
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
                                <!-- <el-collapse v-model="activeFloor" accordion>
                                                                        <el-collapse-item v-for="(floor,floor_key) in address.floorList" :title='"Floor  "+floor.name' :name='floor_key'>
                                                                            <el-collapse v-model="activeRoom" accordion>
                                                                                <el-collapse-item v-for="(room,room_key) in floor.roomList" :title='"Room  "+room.room_name' :name='room_key'>
                                                                                    <deviceList :typeList="room.typeList"></deviceList>
                                                                                </el-collapse-item>
                                                                            </el-collapse>
                                                                        </el-collapse-item>
                                                                    </el-collapse> -->
                                <el-collapse v-model="activeFloor" accordion>
                                    <el-collapse-item v-for="(floor,floor_key) in address.floorList" :name='floor_key'>
                                        <template slot="title">
                                            <span>Floor {{floor.name}}</span>
                                            <el-badge :value="floor.warn">
                                            </el-badge>
                                        </template>
                                        <el-collapse @change="roomChange"  accordion>
                                            <el-collapse-item v-for="(room,room_key) in floor.roomList" :name='room_key'>
                                                <template slot="title">
                                                    <span>Room {{room.room_name}}</span>
                                                    <el-badge :value="room.warn">
                                                    </el-badge>
                                                </template>
                                                <transition name="fade" mode="out-in" appear>
                                                    <deviceList ref="devicelist" :typeList="room.typeList"></deviceList>
                                                </transition>
                                            </el-collapse-item>
                                        </el-collapse>
                                    </el-collapse-item>
                                </el-collapse>
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
            address: {},
            typeList: [],
            activeFloor: 0,
          
        }
    },
    methods: {
        roomChange(val) {
            clearInterval(interval);
            if (typeof(val) == 'string' && val == '') {
                
                return
            }
            window.socketio.removeAllListeners("new_msg");
            // console.log(val)
            var vm = this
            var i = 0;
            var interval = setInterval(function() {
                var deviceList = vm.$refs.devicelist[val].$refs.device
                if(!deviceList){
                    clearInterval(interval);
                    return
                }
                var len = deviceList.length
                if (i > len-1) {
                    clearInterval(interval);
                    return
                }
                deviceList[i].readOpen()
                i = i + 1
            }, 300);

            // for (var device of deviceList) {
            //     // setTimeout(device.readOpen(),2000)
            //     device.readOpen()
            //     // console.log('OK')
            // }
        },
        deviceWarn() {
            var warn = 0
            for (var device of this.$store.state.devices) {
                if (device.on_off == 'on') {
                    for (var breed of this.$store.state[device.devicetype + "_breed"]) {
                        var run_time = parseInt(breed.run_time) * 36000
                        if (device.breed == breed.breed && device.run_time >= run_time) {
                            warn += 1
                        }
                    }
                }
            }
            return warn
        },
        selectCountry(key, keyPath) {

            var typeList = []
            for (var country of this.countryArr) {
                for (var address of country.addressList) {
                    if (key == address.name) {
                        this.address = address
                        // for (var floor of address.floorList) {
                        //     for (var room of floor.roomList) {
                        //         if (typeList.length == 0) {
                        //             typeList = typeList.concat(room.typeList)
                        //         } else {
                        //             for (var type1 of typeList) {
                        //                 for (var type2 of room.typeList) {
                        //                     if (type1.name == type2.name) {
                        //                         type1.deviceList = type1.deviceList.concat(type2.deviceList)
                        //                     }
                        //                 }
                        //             }
                        //         }

                        //     }
                        // }
                    }
                }
            }
            this.typeList = typeList
        },
        menuClick() {

            this.$store.dispatch('showContral', false)
        }

    },
    created() {
        console.log('contral')
        _g.closeGlobalLoading()
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
            var countryArr = this.$store.state.countryArr
            // var countryArr = []
            // countryArr.concat(this.$store.state.countryArr)
            // console.log(countryArr)
            // for (var country of countryArr) {
            //     // country.warn = 0
            //     // for (var device of country.deviceList) {
            //     //     if (device.on_off == 'on') {
            //     //         for (var breed of this.$store.state[device.devicetype + "_breed"]) {
            //     //             var run_time = parseInt(breed.run_time) * 36000
            //     //             if (device.breed == breed.breed && device.run_time >= run_time) {
            //     //                 country.warn += 1
            //     //             }
            //     //         }
            //     //     }
            //     // }
            //     for (var address of country.addressList) {
            //         address.warn = 0
            //         for (var device of address.deviceList) {
            //             if (device.on_off == 'on') {
            //                 for (var breed of this.$store.state[device.devicetype + "_breed"]) {
            //                     var run_time = parseInt(breed.run_time) * 36000
            //                     if (device.breed == breed.breed && device.run_time >= run_time) {
            //                         address.warn += 1
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }
            return countryArr
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

<style>
.country-badge-div {
    position: relative;
    width: 100%;
    height: 100%;
}

.country-badge-div sup {
    position: absolute !important;
    top: 10px !important;
    right: 2px !important;
}

.address-badge-div {
    position: relative;
    width: 100%;
    height: 100%;
}

.address-badge-div sup {
    position: absolute !important;
    top: 12px !important;
    right: 22px !important;
}
</style>

