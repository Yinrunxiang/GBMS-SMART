<template>
    <div class="container-out" style="height:100%">
        <div v-show="showAll" style="width:100%;height:100%">
            <div ref="roomWatts" class="roomWatts" style=""></div>
            <div v-show="showHotel" class="hotel-content">
                <div class="icon-list">
                    <div @click="hotelBack">
                        <i class="fa fa-reply"></i>
                    </div>
                    <div @click="settingClick">
                        <i class="el-icon-setting"></i>
                    </div>
                </div>
                <div class="container-in">
                    <div class="container-home">
                        <div class="build">
                            <a class="build-img">
                                <img src="../../../assets/images/build.jpg">
                            </a>
                            <!-- <p class="p-title">Build</p> -->
                        </div>
                        <div class="floor">

                            <div v-for="(floor, floor_key, floor_index) in addressProperty.floor_num">
                                <el-tooltip placement="right" transition="">
                                    <div v-if="addressProperty.floorList[addressProperty.floor_num -floor_key -1]?true:false" slot="content">
                                        <p v-for="(val, key, index) in addressProperty.floorList[addressProperty.floor_num -floor_key -1].deviceTypeNumber">{{key}}:{{val}}</p>
                                    </div>
                                    <div class="floor-centent" @click="floorClick(addressProperty.floor_num -floor_key)" @mouseover="floorOver(addressProperty.floor_num -floor_key)">Floor{{addressProperty.floor_num -floor_key}}
                                    </div>
                                </el-tooltip>
                            </div>
                            <!-- <p class="p-title">Floor</p> -->
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="showFloor" class="floor-content">
                <div class="icon-list">
                    <div @click="floorBack">
                        <i class="fa fa-reply"></i>
                    </div>
                </div>
                <div class="floorImga">
                    <div v-for="(room, room_key, room_index) in room_num">
                        <el-tooltip placement="right" transition="">
                            <div v-if="roomList[room_key]?true:false" slot="content">
                                <p v-for="(val, key, index) in roomList[room_key].deviceTypeNumber">{{key}}:{{val}}</p>
                            </div>
                            <div :class="'room'+room" class="room" @click="roomClick(room)" @mouseover="roomOver(room)"></div>
                        </el-tooltip>
                    </div>
                </div>
            </div>
            <div v-show="showRoom" id="parentConstrain" class="room-content" style="position:absolute;width:100%;height:100%;background-color:#fff;">
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
                    <div @click="roomBack">
                        <i class="fa fa-reply"></i>
                    </div>
                </div>
                <div class="roomImga">
                    <deviceTap v-for="device in deviceList" :device="device" :setting="setting" @deviceDbclick="deviceDbclick"></deviceTap>

                </div>
                <!-- <div class="device-list">
                                                                                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                </div> -->
            </div>
        </div>
        <div v-show="showDeviceUpdate">
            <deviceUpdate :device="thisdevice" :notHotel="notHotel" @changeUpdate="changeUpdate"></deviceUpdate>
        </div>
        <div v-if="showDevicePage">
            <devicePage :device="thisdevice" @changeContral="changeContral"></devicePage>
        </div>
        <div v-show="showHotelUpdate">
            <addressUpdate :add="addressAdd" :address="addressUpdateData" @goback="addressBack"></addressUpdate>
        </div>
    </div>
</template>





<script>
import deviceList from '../../Common/device/deviceList'
import deviceTap from '../../Common/device/deviceTap'
import devicePage from '../../Common/device/devicePage'
import deviceUpdate from '../plan/update'
import addressUpdate from '../setting/address/add'
// import $ from 'jquery'
// import '../../../assets/css/drag.css'
import '../../../assets/js/jquery-1.9.1.min.js'
import '../../../assets/js/drag.js'
import echarts from 'echarts'
export default {
    data() {
        return {
            showHotel: true,
            showAll: true,
            showFloor: false,
            showRoom: false,
            hotelName: "",
            floorName: "",
            roomName: "",
            floorList: [],
            roomList: [],
            deviceList: [],
            showDeviceUpdate: false,
            showDevicePage: false,
            notHotel: false,
            thisdevice: {},
            typeList: ['light', 'ac', 'led', 'music'],
            showTypeList: false,
            setting: false,
            roomWatts: {},
            showHotelUpdate: false,
            addressAdd: false,
            addressUpdateData:{},
            floorTypeNumber: {},
            roomTypeNumber: {},
            room_num: 13,
        }
    },
    // prop:[address],
    methods: {
        changeUpdate(data) {
            this.showDeviceUpdate = data
            this.showRoom = !data
            this.showAll = !data
        },
        changeContral(data) {
            this.showDevicePage = data
            this.showRoom = !data
            this.showAll = !data
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
            this.showAll = showRoom
            this.showRoom = showRoom
            this.showDeviceUpdate = showDeviceUpdate
            this.showDevicePage = !showDeviceUpdate
            this.thisdevice = device
            if (this.setting) {
                this.thisdevice.subnetid = this.thisdevice.subnetid ? parseInt('0x' + this.thisdevice.subnetid) : ""
                this.thisdevice.deviceid = this.thisdevice.deviceid ? parseInt('0x' + this.thisdevice.deviceid) : ""
                this.thisdevice.channel = this.thisdevice.channel ? parseInt('0x' + this.thisdevice.channel) : ""
                this.thisdevice.channel_spare = this.thisdevice.channel_spare ? parseInt('0x' + this.thisdevice.channel_spare) : ""
            }
        },
        settingClick() {
            this.showHotelUpdate = true
            this.showHotel = false
            this.showAll = false
        },
        addressBack(bool) {
            this.showHotelUpdate = bool
            this.showHotel = !bool
            this.showAll =!bool
        },
        hotelBack() {
            let url = '/home/global'
            router.push(url)
        },
        floorClick(val) {
            this.showFloor = true
            this.showHotel = false
            this.showRoom = false
            this.showTypeList = false
            this.floorName = val
            var deviceList = []
            for (let floor of this.floorProperty) {
                if (floor.floor == val) {
                    this.room_num = floor.room_num ? parseInt(floor.room_num) : 0

                }
            }
            for (let floor of this.floorList) {
                if (floor.name == val) {
                    this.roomList = floor.roomList
                    for (let room of floor.roomList) {
                        for (let type of room.typeList) {
                            for (let device of type.deviceList) {
                                deviceList.push(device)
                            }
                        }
                    }
                }
            }
            this.deviceList = deviceList

        },
        floorOver(num) {
            for (var floor of this.floorList) {
                if (floor.name == num) {
                    this.floorTypeNumber = floor.deviceTypeNumber
                    // this.roomList = floor.roomList
                }
            }
        },
        floorBack() {
            this.showHotel = true
            this.showFloor = false
        },
        roomClick(val) {
            window.socketio.removeAllListeners("new_msg");
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
            var deviceList = []
            for (var room of this.roomList) {
                if (room.name == val) {
                    for (var type of room.typeList) {
                        for (var device of type.deviceList) {
                            deviceList.push(device)
                        }

                    }

                }
            }
            this.deviceList = deviceList
            // this.roomWatts = echarts.init(this.$refs.roomWatts);
        },
        roomOver(val) {
            for (var room of this.roomList) {
                if (room.name == val) {
                    this.roomTypeNumber = room.deviceTypeNumber
                }
            }

        },
        roomBack() {
            this.showFloor = true
            this.showRoom = false
        },
        initMode(value) {
            if (value == "auto") {
                value == "mode_auto"
            }
            return value
        },
        initWind(value) {
            switch (value) {
                case 0:
                    return "wind_auto";
                    break;
                case 1:
                    return "hign";
                    break;
                case 2:
                    return "medial";
                    break;
                case 3:
                    return "low";
                    break;
            }
        },
    },
    created() {
        console.log("report")
        _g.closeGlobalLoading()

    },
    mounted() {
        this.hotelName = this.address.name
        this.floorList = this.address.floorList
        for(var address of this.$store.state.address){
            if(this.address.name == address.address){
                this.addressUpdateData = address
            }
        }
        this.$nextTick(function() {
            var deviceList = []
            if (this.address.floorList) {
                for (var floor of this.address.floorList) {
                    if (floor.roomList) {
                        for (var room of floor.roomList) {
                            if (room.typeList) {
                                for (var type of room.typeList) {
                                    if (type.deviceList) {
                                        for (var device of type.deviceList) {
                                            deviceList.push(device)
                                        }
                                    }

                                }
                            }

                        }
                    }

                }
            }

            this.deviceList = deviceList
        })

    },
    components: {
        deviceList,
        deviceTap,
        deviceUpdate,
        addressUpdate,
        devicePage
    },
    computed: {
        //获取酒店
        address() {
            // console.log(this.$route.query.address.floor)
            
            return this.$route.query.address
        },
        //获取酒店信息
        addressProperty() {
            var initAddress = {
                floor_num: 0
            }
            for (var address of this.$store.state.address) {
                if (address.address == this.hotelName) {
                    this.$route.query.address.floor_num = address.floor_num ? parseInt(address.floor_num) : 0
                    // console.log(address)
                    initAddress = this.$route.query.address
                }
            }
            return initAddress

        },
        //获取楼层信息
        floorProperty() {
            return this.$store.state.floor
        },
        // floorProperty() {
        //     var initFloor = {
        //         room_num: 0
        //     }
        //     for (var floor of this.$store.state.floor) {
        //         if (floor.floor == this.floorName) {
        //             floor.room_num = floor.room_num ? parseInt(floor.room_num) : 0
        //             initFloor = floor
        //         }
        //     }
        //     return initFloor
        // },
        //获取房间信息
        roomProperty() {
            for (var room of this.$store.state.room) {
                if (room.room == this.roomName) {
                    return room
                }
            }
        },
    },
    watch: {
        //计算功耗
        deviceList: {
            handler: function(val, oldVal) {
                var ac_breeds = this.$store.state.ac_breed
                var light_breeds = this.$store.state.light_breed
                var led_breeds = this.$store.state.led_breed
                var wattsTotal = 0
                for (var device of val) {
                    if (device.on_off) {
                        switch (device.devicetype) {
                            case "ac":
                                for (var ac_breed of ac_breeds) {
                                    if (device.breed == ac_breed.breed) {
                                        var modeWatts = parseInt(ac_breed[this.initMode(device.mode)])
                                        modeWatts = modeWatts ? modeWatts : 0
                                        var windWatts = parseInt(ac_breed[this.initWind(device.wind)])
                                        windWatts = windWatts ? windWatts : 0
                                        device.watts = modeWatts + windWatts
                                        wattsTotal += device.watts
                                    }
                                }
                                break
                            case "light":
                                for (var light_breed of light_breeds) {
                                    if (device.breed == light_breed.breed) {
                                        device.watts = parseInt(light_breed.watts)
                                        wattsTotal += device.watts ? device.watts : 0
                                    }
                                }
                                break
                            case "led":
                                for (var led_breed of led_breeds) {
                                    if (device.breed == led_breed.breed) {
                                        device.watts = parseInt(led_breed.watts)
                                        wattsTotal += device.watts ? device.watts : 0
                                    }
                                }
                                break
                        }
                    }
                }
                // this.nextTick(function() {
                // var roomWatts = echarts.init(this.$refs.roomWatts);
                var roomWattsOption = {
                    tooltip: {
                        formatter: "{b} : {c}w"
                    },
                    backgroundColor: new echarts.graphic.RadialGradient(0.5, 0.5, 0.4, [{
                        offset: 0,
                        // color: 'rgba(75,87,105,0.8)'
                        color: 'rgba(255,255,255,0.5)'

                    }, {
                        offset: 1,
                        color: 'rgba(255,255,255,0.5)'
                    }]),
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
                            min: 0,
                            max: 3000,
                            radius: '99%',
                            //分割单位
                            splitNumber: 10,
                            //刻度背景
                            axisLine: {
                                lineStyle: { width: 5 }
                            },
                            //刻度线
                            splitLine: {
                                length: 10
                            },
                            //指针
                            pointer: {
                                width: 6
                            },
                            detail: {
                                formatter: '{value}w',
                                fontSize: 24
                            },
                            data: [{ value: wattsTotal }]
                            // data: [{ value: wattsTotal, name: 'Watts' }]
                        }
                    ]
                };
                var roomWatts = echarts.init(this.$refs.roomWatts);
                roomWatts.setOption(roomWattsOption, true);
            },
            deep: true
        }
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

.roomWatts {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 250px;
    height: 250px;

    z-index: 10;
}

.roomWatts div {
    border-radius: 250px;
}


.setting-icon {
    position: absolute;
    right: 5px;
    top: 5px;
    z-index: 100;
}

.hotel-content {
    position: relative;
    width: 100%;
    height: 100%;
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

.floorImga .room:hover {
    border: 2px solid #20A0FF;
}

.icon-list {
    position: absolute;
    top: 10px;
    right: 5px;
}

.icon-list div {
    width: 35px;
    height: 35px;
    line-height: 35px;
    font-size: 16px;
    background-color: #50bfff;
    color: #fff;
    border-radius: 50px;
    text-align: center;
    margin-bottom: 8px;
}

.icon-list div:hover {
    background-color: #73ccff;
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