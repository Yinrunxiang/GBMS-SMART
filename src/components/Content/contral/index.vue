<template>
    <el-row class="w-100p h-100p">
        <el-col :span="24" class="contral-panel-center h-100p ">
          <el-row class="h-100p">
            <el-col :xs="8" :md="4">
            <aside class="h-100p" style="background: #eef1f6;overflow-x:hidden;overflow-y:scroll">
                <el-menu default-active="1" class="w-100p"  @select="selectCountry" >
                    <div v-for="(country,key) in countryArr" :key = "key">
                        <el-submenu :index="country.name">

                            <template slot="title">
                                <el-badge :value="country.warn" class="country-badge-div">
                                    <i class="el-icon-menu"></i>{{country.name}}
                                </el-badge>
                            </template>

                            <div v-for="(address,key) in country.addressList" :key = "key">
                                <el-badge :value="address.warn" class="address-badge-div">
                                    <el-menu-item :index="address.name" @click="menuClick" class="w-100p">
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
            </el-col>
            <el-col  :xs="16" :md="20">
            <section class="panel-c-c" style="overflow-x:hidden;">
                <div class="grid-content bg-purple-light">
                    <el-col :span="24">
                        <transition name="fade" mode="out-in" appear>
                            <div v-if="showContral">
                                <router-view></router-view>
                            </div>
                            <div v-if="!showContral" style="background-color:#fff;">
                                <el-collapse v-model="activeFloor" accordion >
                                    <el-collapse-item v-for="(floor,floor_key) in address.floorList" :name='floor_key' style="padding-left:15px;" :key = "floor_key">
                                        <template slot="title">
                                            <span>Floor {{floor.name}}</span>
                                            <el-badge :value="floor.warn">
                                            </el-badge>
                                        </template>
                                        <el-collapse @change="roomChange"  accordion>
                                            <el-collapse-item v-for="(room,key) in floor.roomList" :name='room.address+","+room.floor+","+room.room'  style="position: relative;padding-left:15px;"  :key = "key">
                                                <template slot="title">
                                                    <span>Room {{room.room_name}}</span>
                                                    <el-badge :value="room.warn">
                                                    </el-badge>
                                                    <div v-show='room_key == room.address+","+room.floor+","+room.room' class="fr">
                                                        <i class="fa fa-heart contral-mood-icon" @click.stop="clickToShowMoodSetting(room)"></i>
                                                    </div>
                                                </template>
                                                <transition name="fade" mode="out-in" appear>
                                                    <deviceList ref="devicelist" :room='room.address+","+room.floor+","+room.room' :typeList="room.typeList"></deviceList>
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
            </el-row>
           
        </el-col>
        <!-- <changePwd ref="changePwd"></changePwd> -->
        <el-dialog width="80%" title="Configure Mood" :visible.sync="showMoodSetting" v-if="showMoodSetting">
          <mood :room = "room" @close="showMoodSetting=false"></mood>
        </el-dialog>
        <right-page v-if="showRightPage">
          
        </right-page>
    </el-row>
    
</template>
<style>
.contral {
  position: relative;
}
.contral-mood-icon {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 36px;
  height: 36px;
  line-height: 36px !important;
  font-size: 16px !important;
  text-align: center;
  border-radius: 36px;
  color: #fff;
  background-color: rgb(88, 183, 255);
}
.contral-mood-icon:hover {
  background-color: #73ccff;
}
/* .right-page{
  position: absolute;
  top:0;
  right:0;
} */
</style>
<script>
import http from "../../../assets/js/http";
import deviceList from "./deviceList";
import mood from "../../Common/mood";
import rightPage from "../../Common/rightPage";
export default {
  data() {
    return {
      address: {},
      typeList: [],
      activeFloor: 0,
      showMoodSetting: false,
      room: {},
      room_key: "",
      roomDevices: [],
      interval: ""
    };
  },
  methods: {
    //显示心情页面
    clickToShowMoodSetting(room) {
      this.showMoodSetting = true;
      this.room = room;
    },
    //隐藏房间列表
    roomClose() {
      var vm = this;
      var i = 0;
      var len = this.roomDevices.length;
      var forDevice = setInterval(function() {
        if (vm.roomDevices[i].on_off == true) {
          vm.roomDevices[i].on_off = false;
          vm.roomDevices[i].switch_change(false);
        }
        i++;
        if (i >= len) {
          clearInterval(forDevice);
        }
      }, 300);
    },
    //切换房间
    roomChange(val) {
      var vm = this;
      if (vm.interval) {
        clearInterval(vm.interval);
      }
      this.room_key = val;
      clearInterval(vm.interval);
      // window.socketio.removeAllListeners()
      if (typeof val == "string" && val == "") {
        return;
      }
      var deviceOpenList = [];
      // console.log(vm.$refs.devicelist);
      for (var devicelist of vm.$refs.devicelist) {
        if (devicelist.$attrs.room == val) {
          deviceOpenList = deviceOpenList.concat(devicelist.$refs.device);
        }
      }
      
      this.roomDevices = deviceOpenList;
      var i = 0;
      var len = deviceOpenList.length;
      if (len > 0) {
        vm.interval = setInterval(function() {
          deviceOpenList[i].readOpen();
          i = i + 1;
          if (i >= len) {
            clearInterval(vm.interval);
            return;
          }
        }, 300);
      }
    },
    //设备异常状态
    deviceWarn() {
      var warn = 0;
      for (var device of this.$store.state.devices) {
        if (device.on_off == true) {
          for (var breed of this.$store.state[device.devicetype + "_breed"]) {
            var run_time = parseInt(breed.run_time) * 36000;
            if (device.breed == breed.breed && device.run_time >= run_time) {
              warn += 1;
            }
          }
        }
      }
      return warn;
    },
    //切换国家
    selectCountry(key, keyPath) {
      var typeList = [];
      for (var country of this.countryArr) {
        for (var address of country.addressList) {
          if (key == address.name) {
            this.address = address;
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
      this.typeList = typeList;
    },
    menuClick() {
      this.$store.dispatch("showContral", false);
    }
  },
  created() {
    console.log("contral");
    _g.closeGlobalLoading();
  },
  mounted() {},
  components: {
    deviceList,
    mood,
    rightPage
  },
  // destroyed() {
  //   console.log('destroyed')
  //   clearInterval(this.interval);
  // },
  computed: {
    //设备数据
    devices() {
      console.log(this.$store.state.devices)
      return this.$store.state.devices;
    },
    showRightPage() {
      return this.$store.state.showRightPage;
    },
    //国家，酒店，楼层，房间数据
    countryArr() {
      // console.log(this.$store.state.countryArr)
      var countryArr = this.$store.state.countryArr;
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
      return countryArr;
    },
    iconstyle(type) {
      switch (type) {
        case "light":
          return "fa-lightbulb-o";
          break;
        case "ac":
          return "fa-thermometer";
          break;
      }
    },
    showContral() {
      return this.$store.state.showContral;
    }
  },
  mixins: [http]
};
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

