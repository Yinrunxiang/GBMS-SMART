<template>
    <div class="container-out" style="height:100%">
        <div v-show="showAll" style="width:100%;height:100%">
            <div class="roomWattsContainer">
                <div ref="roomWatts" class="roomWatts" style=""></div>
            </div>
            
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
            <div v-show="showFloor" >
              <div v-show="!showFloorUpdate" class="floor-content">
                <div class="icon-list">
                    <div @click="floorBack">
                        <i class="fa fa-reply"></i>
                    </div>
                    <div @click="floorSetting">
                        <i class="el-icon-setting"></i>
                    </div>
                </div>
                <div class="floorImga">
                    <div v-for="(room, room_key, room_index) in room_num">
                        <el-tooltip placement="right" transition="">
                            <div v-if="roomList[room_key]?true:false" slot="content">
                                <p>Room: {{roomList[room_key].room_name}}</p>
                                <p v-for="(val, key, index) in roomList[room_key].deviceTypeNumber">{{key}}:{{val}}</p>
                            </div>
                            <div :class="'room'+room" class="room" @click="roomClick(room)" @mouseover="roomOver(room)"></div>
                        </el-tooltip>
                    </div>
                </div>
                </div>
                <floorUpdate v-if="showFloorUpdate" :floor="this.floor" :add="false" @goback="floorUpdateback"></floorUpdate>
            </div>
            <div v-if="showRoom" id="parentConstrain" class="room-content" style="position:absolute;width:100%;height:100%;background-color:#fff;">
              <div v-show="!showRoomUpdate">
                <el-popover ref="addDevice" placement="left" width="100" trigger="hover" style="padding:0;margin:0;">
                    <div class="add-type-list" v-for="devicetype in typeList" style="padding:10px;width:100px;height: 25px;line-height:25px;font-size:16px;border-bottom: 1px solid #dfe6ec;" @click="addDeviceListClick(devicetype)">{{devicetype}}</div>
                </el-popover>
                <div class="icon-list">
                    <div @click="roomBack">
                        <i class="fa fa-reply"></i>
                    </div>
                    <div v-popover:addDevice>
                        <i class="fa fa-plus"></i>
                    </div>
                    <!-- <div @click="settingStatusClick">
                        <i class="el-icon-setting"></i>
                    </div> -->
                    <div @click="roomSetting">
                        <i class="el-icon-setting"></i>
                    </div>
                    <div @click="roomClose">
                        <i class="fa fa-pause"></i>
                    </div>
                    <div @click="clickToShowMoodSetting">
                        <i class="fa fa-heart"></i>
                    </div>
                    
                </div>
                <div class="roomImga">
                    <deviceTap ref="device" v-for="device in deviceList" :device="device" :setting="setting" @deviceDbclick="deviceDbclick"></deviceTap>

                </div>
                </div>
                <room-update v-if="showRoomUpdate" :room="this.room" :add="false" @goback="roomUpdateback"></room-update>
            </div>
        </div>
        <div v-if="showDeviceUpdate">
            <deviceUpdate :device="thisdevice" :notHotel="notHotel" @changeUpdate="changeUpdate" @newDevice="addNewDevice"></deviceUpdate>
        </div>
        <div v-if="showDevicePage">
            <devicePage :device="thisdevice" @changeContral="changeContral"></devicePage>
        </div>
        <div v-if="showHotelUpdate">
            <addressUpdate :add="addressAdd" :address="addressUpdateData" @goback="addressBack"></addressUpdate>
        </div>
         <el-dialog title="Configure Mood" :visible.sync="showMoodSetting" v-if="showMoodSetting">
          <mood :room = "this.room" @close="showMoodSetting=false" @off="roomClose"></mood>
        </el-dialog>
        <!-- <changeName ref="changeName" :showChange = 'showChange' @change = 'showChangePage'></changeName> -->
    </div>
</template>





<script>
import deviceList from "../../Common/device/deviceList";
import deviceTap from "../../Common/device/deviceTap";
import devicePage from "../../Common/device/devicePage";
import deviceUpdate from "../plan/update";
import addressUpdate from "../setting/address/add";
import floorUpdate from "../setting/floor/add";
import roomUpdate from "../setting/room/add";
import mood from "./mood";
// import changeName from "../setting/room/changeName";
// import $ from 'jquery'
// import '../../../assets/css/drag.css'
import "../../../assets/js/jquery-1.9.1.min.js";
import "../../../assets/js/drag.js";
import echarts from "echarts";
export default {
  data() {
    return {
      showMoodSetting: false,
      showChange: false,
      //   showHotel: true,
      showAll: true,
      //   showFloor: false,
      //   showRoom: false,
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
      typeList: ["light", "ac", "led", "curtain", "music", "ir"],
      showTypeList: false,
      setting: false,
      roomWatts: {},
      showHotelUpdate: false,
      addressAdd: false,
      addressUpdateData: {},
      floorTypeNumber: {},
      roomTypeNumber: {},
      room_num: 13,
      showFloorUpdate: false,
      showRoomUpdate: false,
      floor: {},
      room: {}
    };
  },
  // prop:[address],
  methods: {
    changeRoomName() {
      this.showChange = true;
    },
    showChangePage(val) {
      this.showChange = val;
    },
    changeUpdate(data) {
      this.showDeviceUpdate = data;
      this.$store.dispatch("setShowRoom", !data);
      this.showAll = !data;
    },
    changeContral(data) {
      this.showDevicePage = data;
      this.$store.dispatch("setShowRoom", !data);
      this.showAll = !data;
    },
    settingStatusClick() {
      this.setting = !this.setting;
    },
    addDeviceListClick(device) {
      this.showAll = false;
      this.$store.dispatch("setShowRoom", false);
      this.showDeviceUpdate = true;
      var deviceObj = {};
      deviceObj.id = "";
      deviceObj.device = "";
      deviceObj.subnetid = "";
      deviceObj.deviceid = "";
      deviceObj.channel = "";
      deviceObj.address = this.address.name;
      deviceObj.floor = this.floorName;
      deviceObj.room = this.roomName;
      deviceObj.devicetype = device;
      deviceObj.on_off = "";
      deviceObj.status = "";
      deviceObj.icon = "";
      deviceObj.starttine = "";
      deviceObj.endtime = "";
      deviceObj.mode = "";
      deviceObj.grade = "";
      deviceObj.breed = "";
      deviceObj.x_axis = 0;
      deviceObj.y_axis = 0;
      this.thisdevice = deviceObj;
      // this.deviceList.push(deviceObj);
    },
    addNewDevice(device) {
      this.deviceList.push(device);
    },
    deviceDbclick(showRoom, showDeviceUpdate, device) {
      this.showAll = showRoom;
      this.$store.dispatch("setShowRoom", showRoom);
      this.showDeviceUpdate = showDeviceUpdate;
      this.showDevicePage = !showDeviceUpdate;
      this.thisdevice = device;
      if (this.setting) {
        this.thisdevice.subnetid = this.thisdevice.subnetid
          ? parseInt("0x" + this.thisdevice.subnetid)
          : "";
        this.thisdevice.deviceid = this.thisdevice.deviceid
          ? parseInt("0x" + this.thisdevice.deviceid)
          : "";
        this.thisdevice.channel = this.thisdevice.channel
          ? parseInt("0x" + this.thisdevice.channel)
          : "";
        this.thisdevice.channel_spare = this.thisdevice.channel_spare
          ? parseInt("0x" + this.thisdevice.channel_spare)
          : "";
      }
    },
    settingClick() {
      this.showHotelUpdate = true;
      this.$store.dispatch("setShowHotel", false);
      this.showAll = false;
    },
    addressBack(bool) {
      this.showHotelUpdate = bool;
      this.$store.dispatch("setShowHotel", !bool);
      this.showAll = !bool;
    },
    hotelBack() {
      this.$store.dispatch("setShowHotel", false);
      this.$store.dispatch("setShowFloor", false);
      this.$store.dispatch("setShowRoom", false);
      let url = "/home/global";
      router.push(url);
    },
    floorClick(val) {
      // this.showFloor = true;
      this.$store.dispatch("setShowHotel", false);
      this.$store.dispatch("setShowFloor", true);
      this.$store.dispatch("setShowRoom", false);
      this.showTypeList = false;
      this.floorName = val;
      var deviceList = [];
      for (let floor of this.floorProperty) {
        if (floor.floor == val && floor.address == this.address.name) {
          this.floor = floor;
          this.room_num = floor.room_num ? parseInt(floor.room_num) : 0;
        }
      }
      for (let floor of this.floorList) {
        if (floor.name == val) {
          this.roomList = floor.roomList;
          for (let room of floor.roomList) {
            for (let type of room.typeList) {
              for (let device of type.deviceList) {
                deviceList.push(device);
              }
            }
          }
        }
      }
      this.deviceList = deviceList;
    },
    floorOver(num) {
      for (var floor of this.floorList) {
        if (floor.name == num) {
          this.floorTypeNumber = floor.deviceTypeNumber;
          // this.roomList = floor.roomList
        }
      }
    },
    floorBack() {
      this.$store.dispatch("setShowHotel", true);
      this.$store.dispatch("setShowFloor", false);
    },
    floorSetting() {
      this.showFloorUpdate = true;
    },
    floorUpdateback(val) {
      this.showFloorUpdate = val;
    },
    roomClick(val) {
      // window.socketio.removeAllListeners("new_msg");
      this.$store.dispatch("setShowFloor", false);
      this.$store.dispatch("setShowHotel", false);
      this.$store.dispatch("setShowRoom", true);
      this.showTypeList = false;
      this.roomName = val;
      // for (var room of this.roomList) {
      //     if (room.name == '101') {
      //         for(var type of room.typeList){
      //             this.deviceList.concat(type.deviceList)
      //         }

      //     }
      // }
      var deviceList = [];
      // for (let roomPro of this.roomProperty) {
      //   if (roomPro.room == val) {
      //     this.room = roomPro;
      //   }
      // }
      for (var room of this.roomList) {
        if (room.name == val) {
          this.room.id = room.id;
          this.room.image = room.image;
          this.room.room = room.room;
          this.room.room_name = room.room_name;
          this.room.floor = room.floor;
          this.room.address = room.address;
          this.room.typeList = room.typeList
          for (var type of room.typeList) {
            for (var device of type.deviceList) {
              deviceList.push(device);
            }
          }
        }
      }
      this.deviceList = deviceList;

      clearInterval(interval);
      // window.socketio.removeAllListeners("new_msg");
      // console.log(val)
      var vm = this;
      var i = 0;
      var interval = setInterval(function() {
        var deviceList = vm.$refs.device;
        if (!deviceList) {
          clearInterval(interval);
          return;
        }
        var len = deviceList.length;
        if (i > len - 1) {
          clearInterval(interval);
          return;
        }
        deviceList[i].readOpen();
        i = i + 1;
      }, 100);
      // this.roomWatts = echarts.init(this.$refs.roomWatts);
    },
    roomOver(val) {
      for (var room of this.roomList) {
        if (room.name == val) {
          this.roomTypeNumber = room.deviceTypeNumber;
        }
      }
    },
    roomSetting() {
      this.showRoomUpdate = true;
    },
    roomUpdateback(val) {
      this.showRoomUpdate = val;
    },
    roomClose() {
      var deviceList = this.$refs.device;
      var i = 0;
      var len = deviceList.length;
      var forDevice = setInterval(function() {
        if (deviceList[i].device.on_off == true) {
          deviceList[i].device.on_off = false;
          deviceList[i].switch_change(false);
        }
        i++;
        if (i >= len) {
          clearInterval(forDevice);
        }
      }, 100);
    },
    roomBack() {
      this.$store.dispatch("setShowFloor", true);
      this.$store.dispatch("setShowRoom", false);
      // this.roomList = [];
    },
    initMode(value) {
      if (value == "auto") {
        value == "mode_auto";
      }
      return value;
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
    clickToShowMoodSetting() {
      this.showMoodSetting = true;
    }
  },
  created() {
    console.log("report");
    this.$store.dispatch("setShowHotel", true);
    _g.closeGlobalLoading();
  },
  destroyed() {
    this.$store.dispatch("setShowHotel", false);
    this.$store.dispatch("setShowFloor", false);
    this.$store.dispatch("setShowRoom", false);
  },
  mounted() {
    this.roomWatts = echarts.init(this.$refs.roomWatts);
    this.hotelName = this.address.name;
    this.floorList = this.address.floorList;
    console.log(this.address);
    for (var address of this.$store.state.address) {
      if (this.address.name == address.address) {
        this.addressUpdateData = address;
      }
    }
    this.$nextTick(function() {
      var deviceList = [];
      if (this.address.floorList) {
        for (var floor of this.address.floorList) {
          if (floor.roomList) {
            for (var room of floor.roomList) {
              if (room.typeList) {
                for (var type of room.typeList) {
                  if (type.deviceList) {
                    for (var device of type.deviceList) {
                      deviceList.push(device);
                    }
                  }
                }
              }
            }
          }
        }
      }

      this.deviceList = deviceList;
    });
  },
  components: {
    deviceList,
    deviceTap,
    deviceUpdate,
    addressUpdate,
    devicePage,
    floorUpdate,
    roomUpdate,
    mood
  },
  computed: {
    //获取酒店
    address() {
      // console.log(this.$route.query.address.floor)

      return this.$route.query.address;
    },
    showHotel() {
      return this.$store.state.showHotel;
    },
    showFloor() {
      return this.$store.state.showFloor;
    },
    showRoom() {
      return this.$store.state.showRoom;
    },

    //获取酒店信息
    addressProperty() {
      var initAddress = {
        floor_num: 0
      };
      for (var address of this.$store.state.address) {
        if (address.address == this.hotelName) {
          this.$route.query.address.floor_num = address.floor_num
            ? parseInt(address.floor_num)
            : 0;
          // console.log(address)
          initAddress = this.$route.query.address;
        }
      }
      return initAddress;
    },
    //获取楼层信息
    floorProperty() {
      return this.$store.state.floor;
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
          return room;
        }
      }
    }
  },
  watch: {
    //计算功耗
    deviceList: {
      handler: function(val, oldVal) {
        var ac_breeds = this.$store.state.ac_breed;
        var light_breeds = this.$store.state.light_breed;
        var led_breeds = this.$store.state.led_breed;
        var wattsTotal = 0;
        for (var device of val) {
          if (device.on_off) {
            switch (device.devicetype) {
              case "ac":
                for (var ac_breed of ac_breeds) {
                  if (device.breed == ac_breed.breed) {
                    var modeWatts = parseInt(
                      ac_breed[this.initMode(device.mode)]
                    );
                    modeWatts = modeWatts ? modeWatts : 0;
                    var windWatts = parseInt(
                      ac_breed[this.initWind(device.wind)]
                    );
                    windWatts = windWatts ? windWatts : 0;
                    device.watts = modeWatts + windWatts;
                    wattsTotal += device.watts
                      ? parseFloat((device.watts / 1000).toFixed(2))
                      : 0;
                  }
                }
                break;
              case "light":
                for (var light_breed of light_breeds) {
                  if (device.breed == light_breed.breed) {
                    device.watts = parseInt(light_breed.watts);
                    wattsTotal += device.watts
                      ? parseFloat((device.watts / 1000).toFixed(2))
                      : 0;
                  }
                }
                break;
              case "led":
                for (var led_breed of led_breeds) {
                  if (device.breed == led_breed.breed) {
                    device.watts = parseInt(led_breed.watts);
                    wattsTotal += device.watts
                      ? parseFloat((device.watts / 1000).toFixed(2))
                      : 0;
                  }
                }
                break;
            }
          }
        }
        // this.nextTick(function() {
        // var roomWatts = echarts.init(this.$refs.roomWatts);
        var roomWattsOption = {
          tooltip: {
            formatter: "{c}kw"
          },
          backgroundColor: new echarts.graphic.RadialGradient(0.5, 0.5, 0.4, [
            {
              offset: 0,
              // color: 'rgba(75,87,105,0.8)'
              color: "rgba(255,255,255,0.5)"
            },
            {
              offset: 1,
              color: "rgba(255,255,255,0.5)"
            }
          ]),
          // toolbox: {
          //     feature: {
          //         restore: {},
          //         saveAsImage: {}
          //     }
          // },
          series: [
            {
              name: "",
              type: "gauge",
              min: 0,
              max: 10,
              radius: "99%",
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
                formatter: "{value}kw",
                fontSize: 24
              },
              data: [{ value: parseFloat(wattsTotal.toFixed(2)) }]
              // data: [{ value: wattsTotal, name: 'Watts' }]
            }
          ]
        };
        // var roomWatts = echarts.init(this.$refs.roomWatts);
        this.roomWatts.setOption(roomWattsOption, true);
      },
      deep: true
    }
  }
};
</script>


<style>
.container-out {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
}
.roomWattsContainer {
  position: fixed;
  right: 15px;
  bottom: 10px;
  z-index: 10;
}
.roomWatts {
  width: 250px;
  height: 250px;
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
  background-color: #ccc;
}

.container-in {
  position: relative;
  width: 800px;
  height: 550px;
  padding: 0;
  margin: 0 auto;
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
  width: 250px;
  height: 400px;
  overflow: auto;
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
  font-size: 14px;
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
  border: 2px solid #20a0ff;
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