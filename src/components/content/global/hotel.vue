<template>
    <div class="container-out" style="height:100%">
        <div v-show="showHotel" class="setting-icon">
            <!-- <router-link to="setting/address/update" :form='address'> -->
            <el-button size="small" type="info" @click="settingClick">
                <i class="el-icon-setting"></i>
            </el-button>
            <!-- </router-link> -->
        </div>
        <div v-show="showHotel" class="container-in">
            <div class="container-home">
                <div class="build">
                    <a class="build-img">
                        <img src="../../../assets/images/build.jpg">
                    </a>
                    <!-- <p class="p-title">Build</p> -->
                </div>
                <div class="floor">
                    <div v-for="num in addressProperty.floor_num" class="floor-centent" @click="floorClick(num)">Floor{{addressProperty.floor_num +1-num}}
                    </div>
                    <!-- <p class="p-title">Floor</p> -->
                </div>
            </div>
        </div>
        <div v-show="showFloor" class="floor-content">
            <div class="floorImga">
                <div class="room1" @click="roomClick('101')"></div>
                <div class="room2" @click="roomClick('101')"></div>
                <div class="room3" @click="roomClick('101')"></div>
                <div class="room4" @click="roomClick('101')"></div>
                <div class="room5" @click="roomClick('101')"></div>
                <div class="room6" @click="roomClick('101')"></div>
                <div class="room7" @click="roomClick('101')"></div>
                <div class="room8" @click="roomClick('101')"></div>
                <div class="room9" @click="roomClick('101')"></div>
                <div class="room10" @click="roomClick('101')"></div>
                <div class="room11" @click="roomClick('101')"></div>
                <div class="room12" @click="roomClick('101')"></div>
                <div class="room13" @click="roomClick('101')"></div>
            </div>
        </div>
        <div v-show="showRoom" id="parentConstrain" class="room-content" style="position:absolute;width:100%;height:100%;background-color:#fff;">
            <!-- <el-dropdown class="setting-icon" @command="handleCommand">
                                            <el-button type="primary">
                                                Add Device
                                                <i class="el-icon-caret-bottom el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item style="width:100px;" v-for="devicetype in typeList" :command="devicetype">{{devicetype}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown> -->
            <div ref="roomWatts" style="position: absolute;right: 0;bottom: 0;width:350px;height:350px;z-index:10;"></div>
            <el-popover ref="addDevice" placement="left" width="100" trigger="hover" style="padding:0;margin:0;">
                <div class="add-type-list" v-for="devicetype in typeList" style="padding:10px;width:100px;height: 25px;line-height:25px;font-size:16px;border-bottom: 1px solid #dfe6ec;" @click="addDeviceListClick(devicetype)">{{devicetype}}</div>
            </el-popover>
            <div class="icon-list">
                <div v-popover:addDevice>
                    <i class="fa fa-plus"></i>
                </div>
                <div @click="settingStatusClick">
                    <i class="el-icon-setting"></i>
                </div>
            </div>
            <div class="roomImga">
                <deviceTap v-for="device in deviceList" :device="device" :setting="setting" @deviceDbclick="deviceDbclick"></deviceTap>

            </div>
            <!-- <div class="device-list">
                                                                                                                                            
                                                                                                                                        </div> -->
        </div>
        <div v-show="showDeviceUpdate">
            <deviceUpdate :device="thisdevice" :notHotel="notHotel" @changeUpdate="changeUpdate"></deviceUpdate>
        </div>
        <!-- <div v-show="showTypeList" style="background-color: #fff">
                                                                                                                <deviceList :typeList="typeList"></deviceList>
                                                                                                            </div> -->
    </div>
</template>





<script>
import deviceList from '../../Common/device/deviceList'
import deviceTap from '../../Common/device/deviceTap'
import deviceUpdate from '../plan/update'
// import $ from 'jquery'
// import '../../../assets/css/drag.css'
import '../../../assets/js/jquery-1.9.1.min.js'
import '../../../assets/js/drag.js'
import echarts from 'echarts'
export default {
    data() {
        return {
            showHotel: true,
            showFloor: false,
            showRoom: false,
            hotelName: "",
            floorName: "",
            roomName: "",
            floorList: [],
            roomList: [],
            deviceList: [],
            showDeviceUpdate: false,
            notHotel: false,
            thisdevice: {},
            typeList: ['light', 'ac', 'led', 'music'],
            showTypeList: false,
            setting: false,
        }
    },
    // prop:[address],
    methods: {
        changeUpdate(data) {
            this.showDeviceUpdate = data
            this.showRoom = !data
        },
        settingStatusClick() {
            this.setting = !this.setting
        },
        addDeviceListClick(device) {
            var deviceObj = {}
            deviceObj.id = ""
            deviceObj.device = ""
            deviceObj.subnetid = ""
            deviceObj.deviceid = ""
            deviceObj.channel = ""
            deviceObj.address = this.address.name
            deviceObj.floor = this.floorName
            deviceObj.room = this.roomName
            deviceObj.devicetype = device
            deviceObj.on_off = ""
            deviceObj.status = ""
            deviceObj.icon = ""
            deviceObj.starttine = ""
            deviceObj.endtime = ""
            deviceObj.mode = ""
            deviceObj.grade = ""
            deviceObj.breed = ""
            deviceObj.x_axis = 0
            deviceObj.y_axis = 0
            this.deviceList.push(deviceObj)

        },
        deviceDbclick(showRoom, showDeviceUpdate, device) {
            this.showRoom = showRoom
            this.showDeviceUpdate = showDeviceUpdate
            this.thisdevice = device
        },
        settingClick() {
            let url = '/home/setting/address/update'
            router.push({ path: url, query: { address: this.address } })
        },
        floorClick(val) {
            this.showFloor = true
            this.showHotel = false
            this.showRoom = false
            this.showTypeList = false
            this.floorName = val
            for (var floor of this.floorList) {
                if (floor.name == val) {
                    this.roomList = floor.roomList
                }
            }
            var roomWatts = echarts.init(this.$refs.roomWatts);
            var roomWattsOption = {
                tooltip: {
                    formatter: "{b} : {c}w"
                },
                // toolbox: {
                //     feature: {
                //         restore: {},
                //         saveAsImage: {}
                //     }
                // },
                series: [
                    {
                        name: '',
                        type: 'gauge',
                        min:0,
                        max:3000,
                        splitNumber:10,
                        axisLine:{
                            lineStyle:{width:15}
                        },
                        splitLine:{
                            length:20
                        },
                        detail: { formatter: '{value}w' },
                        data: [{ value: 50, name: 'Watts' }]
                    }
                ]
            };
            setInterval(function() {
                roomWattsOption.series[0].data[0].value = parseInt(Math.random() * 1000);
                roomWatts.setOption(roomWattsOption, true);
            },2000)
        },
        roomClick(val) {
            this.showFloor = false
            this.showHotel = false
            this.showRoom = true
            this.showTypeList = false
            this.roomName = val
            // for (var room of this.roomList) {
            //     if (room.name == '101') {
            //         for(var type of room.typeList){
            //             this.deviceList.concat(type.deviceList)
            //         }

            //     }
            // }
            for (var floor of this.address.floorList) {
                for (var room of floor.roomList) {
                    if (room.name == '101') {
                        for (var type of room.typeList) {
                            for (var device of type.deviceList) {
                                this.deviceList.push(device)
                            }

                        }

                    }
                }
            }
        },
    },
    created() {
        console.log("report")


    },
    mounted() {
        this.hotelName = this.address.name
        this.floorList = this.address.floorList


    },
    components: {
        deviceList,
        deviceTap,
        deviceUpdate,
    },
    computed: {
        address() {
            // console.log(this.$route.query.address.floor)

            return this.$route.query.address
        },
        addressProperty() {
            for (var address of this.$store.state.address) {
                if (address.address = this.hotelName) {
                    address.floor_num = parseInt(address.floor_num)
                    // console.log(address)
                    return address
                }
            }

        },
        floorProperty() {
            for (var floor of this.$store.state.floor) {
                if (floor.floor = this.floorName) {
                    floor.room_num = parseInt(floor.room_num)
                    return floor
                }
            }
        },
        roomProperty() {
            for (var room of this.$store.state.room) {
                if (room.room = this.roomName) {
                    return room
                }
            }
        },
    }
}
</script>


<style>
.container-out {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: #ccc;
}

.setting-icon {
    position: absolute;
    right: 5px;
    top: 5px;
    z-index: 100;
}

.container-in {
    position: relative;
    width: 800px;
    height: 550px;
    padding: 0;
    margin: 10px auto;
    /* border: 1px solid #000;
    border-radius: 10px; */
}

.build {
    width: 200px;
    height: 300px;
    margin: 0 30px 0 100px;
}

.build-img {
    display: inline-block;
    width: 300px;
    height: 500px;
    margin: 30px auto;
}

.build-img img {
    width: 100%;
    height: 100%;
}

.p-title {
    text-align: center;
    font-size: 36px;
}

.floor {
    position: absolute;
    right: 50px;
    bottom: 75px;
    width: 200px;
    height: 300px;
    margin: 0 30px 0 100px;
}

.floor-centent {
    width: 200px;
    height: 30px;
    line-height: 30px;
    margin: 3px auto;
    /* background-color: rgba(0, 0, 0,0.7); */
    background-color: rgb(88, 183, 255);
    border: 1px solid #fff;
    /* border-radius: 5px; */
    text-align: center;
    color: #fff;
    font-size: 14px
}

.container-floor {
    text-align: center;
    color: #fff;
    font-size: 36px;
}

.floor-room {
    height: 130px;
    background-color: rgb(88, 183, 255);
    border: 1px solid #fff;
}

.floor-aisle {
    height: 80px;
    background-color: rgb(88, 183, 255);
    border: 1px solid #fff;
}

.container-room {
    text-align: center;
    color: #fff;
    font-size: 36px;
}

.container-room .room {
    background-color: rgb(88, 183, 255);
    border: 1px solid #fff;
}

.parlor {
    height: 400px;
}


.floor-content {
    width: 100%;
    height: 740px;
    background-color: #fff;
}

.floorImga {
    position: relative;
    width: 1020px;
    height: 740px;
    /* margin: 0px auto; */
    background-image: url("../../../assets/images/floor.jpg");
    background-repeat: no-repeat;
    background-size: 100% 100%;
    -moz-background-size: 100% 100%;
}

.floorImga>div:hover {
    border: 2px solid #20A0FF;
}







/* .add-type-list{
    width:100px;
    height: 25px;
    line-height:25px;
    font-size:16px;
    border-bottom: 1px solid #dfe6ec;
} */

.room-content .icon-list {
    position: absolute;
    top: 10px;
    right: 5px;
}

.room-content .icon-list div {
    width: 35px;
    height: 35px;
    line-height: 35px;
    font-size: 16px;
    background-color: #20A0FF;
    color: #fff;
    border-radius: 50px;
    text-align: center;
    margin-bottom: 8px;
}


.device-list {
    position: absolute;
    top: 50px;
    right: 50px;
    width: 200px;
    height: 50px;
}

.roomImga {
    position: relative;
    width: 895px;
    height: 487px;
    background-image: url("../../../assets/images/room.jpg");
    background-repeat: no-repeat;
    background-size: 100% 100%;
    -moz-background-size: 100% 100%;
}

.room1 {
    position: absolute;
    left: 0;
    top: 0;
    width: 170px;
    height: 335px;
}

.room2 {
    position: absolute;
    left: 170px;
    top: 0;
    width: 170px;
    height: 335px;
}

.room3 {
    position: absolute;
    left: 340px;
    top: 0;
    width: 170px;
    height: 335px;
}

.room4 {
    position: absolute;
    left: 510px;
    top: 0;
    width: 170px;
    height: 335px;
}

.room5 {
    position: absolute;
    left: 680px;
    top: 0;
    width: 170px;
    height: 335px;
}

.room6 {
    position: absolute;
    left: 850px;
    top: 0;
    width: 170px;
    height: 335px;
}

.room7 {
    position: absolute;
    left: 0;
    top: 400px;
    width: 170px;
    height: 335px;
}

.room8 {
    position: absolute;
    left: 170px;
    top: 400px;
    width: 170px;
    height: 335px;
}

.room9 {
    position: absolute;
    left: 340px;
    top: 400px;
    width: 170px;
    height: 335px;
}

.room10 {
    position: absolute;
    left: 510px;
    top: 400px;
    width: 170px;
    height: 335px;
}

.room11 {
    position: absolute;
    left: 680px;
    top: 400px;
    width: 170px;
    height: 335px;
}

.room12 {
    position: absolute;
    left: 850px;
    top: 400px;
    width: 170px;
    height: 335px;
}
</style>