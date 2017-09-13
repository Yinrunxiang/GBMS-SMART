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
        <div v-show="showRoom" id="parentConstrain" class="mode" style="position:absolute;width:100%;height:100%;background-color:#fff;">
            <el-dropdown class="setting-icon" @command="handleCommand">
                <el-button type="primary">
                    Add Device
                    <i class="el-icon-caret-bottom el-icon--right"></i>
                </el-button>
                <el-dropdown-menu slot="dropdown">
                    <el-dropdown-item style="width:100px;" v-for="devicetype in typeList" :command="devicetype">{{devicetype}}</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
            <div class="roomImga">
                <deviceTap v-for="device in deviceList" :device="device" @deviceDbclick="deviceDbclick"></deviceTap>
            </div>
            <!-- <div class="device-list">
                                                                                            
                                                                                        </div> -->
        </div>
        <div v-show="showDeviceUpdate">
            <deviceUpdate :device="thisdevice" :notHotel="notHotel" @changeUpdate="changeUpdate" ></deviceUpdate>
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
            showTypeList: false
        }
    },
    // prop:[address],
    methods: {
        changeUpdate(data) {
            this.showDeviceUpdate = data
            this.showRoom = !data
        },
        handleCommand(command) {
            var deviceObj = {}
            deviceObj.id = ""
            deviceObj.device = ""
            deviceObj.subnetid = ""
            deviceObj.deviceid = ""
            deviceObj.channel = ""
            deviceObj.address = this.address.name
            deviceObj.floor = this.floorName
            deviceObj.room = this.roomName
            deviceObj.devicetype = command
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
        // console.log(this.addressProperty.floor_num)
        // console.log(this.deviceList)
        // this.$nextTick(function() {
        //     for (var device of this.$refs.device) {
        //         console.log($(device).attr('device').device)
        //         $(device).myDrag({
        //             parent: 'parent', //定义拖动不能超出的外框,拖动范围
        //             randomPosition: false, //初始化随机位置
        //             direction: 'all', //方向
        //             handler: false, //把手
        //             dragStart: function(x, y) { }, //拖动开始 x,y为当前坐标
        //             dragEnd: function(x, y) { }, //拖动停止 x,y为当前坐标
        //             dragMove: function(x, y) { } //拖动进行中 x,y为当前坐标
        //         });
        //     }
        // })

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
.build-img img{
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
    bottom:  75px;
    width: 200px;
    height: 300px;
    margin: 0 30px 0 100px;
}

.floor-centent {
    width: 200px;
    height: 30px;
    line-height: 30px;
    margin: 3px auto;
    background-color: rgba(0, 0, 0,0.7);
    /* background-color: rgb(88, 183, 255); */
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

.livingroom,
.bathroom {
    height: 200px
}
.floor-content{
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