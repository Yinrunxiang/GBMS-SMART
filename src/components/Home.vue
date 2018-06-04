<template>
	<div class="panel m-w-1280" @click="hideRightPage()">
    <span class="version">ver {{version}}</span>
		<el-col :span="24" class="panel-top">
			<el-col class="w-180">
				<template v-if="logo_type == '1'">
					<!-- <img :src="img" class="logo"> -->
				</template>
				<template v-else>
					<a class="p-l-10"  style="cursor:pointer;color:#fff;"  @click="homeClick()"><img src="../assets/images/Gensen app of GBMS.png" class="logo">SMART GBMS</a>
				</template>
			</el-col>
      <el-col :span="18" class="h-60">
        <topMenu ref="topMenu"></topMenu>
      </el-col>
			<el-col  :span="2" class="pos-rel">
				<el-dropdown @command="handleMenu" class="user-menu">

					<p class="el-dropdown-link user-ground" style="cursor: default;color:#eee;">
						<i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;{{username}}
					</p>
					<el-dropdown-menu slot="dropdown">
						<el-dropdown-item command="changePwd">Setting</el-dropdown-item>
						<el-dropdown-item command="logout">Sign out</el-dropdown-item>
					</el-dropdown-menu>
				</el-dropdown>
        
			</el-col>
		</el-col>
		<el-col :span="24" class="panel-center">
			<!--<el-col :span="4">-->
			<aside class="w-180" style="overflow-x:hidden;overflow-y:auto;" ref="leftMenu">
				<leftMenu :menuData="menuData" ref="leftMenu"></leftMenu>
			</aside>
			<section class="panel-c-c" :class="{'hide-leftMenu': hasChildMenu}">
				<div class="grid-content bg-purple-light">
					<el-col :span="24">
						<transition name="fade" mode="out-in" appear>
							<router-view :dataReady="dataReady"></router-view>
						</transition>
					</el-col>
				</div>
			</section>
		</el-col>
		<changePwd ref="changePwd" :showChange = 'showChange' @change = 'showChangePage'></changePwd>

	</div>
</template>
<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-active {
  opacity: 0;
}

.panel {
  position: absolute;
  top: 0px;
  bottom: 0px;
  padding: 0;
  width: 100%;
}

.panel-top {
  height: 60px;
  line-height: 60px;
  background: #1f2d3d;
  color: #eee;
}

.panel-center {
  background: #324057;
  position: absolute;
  top: 60px;
  bottom: 0px;
  overflow: hidden;
}

.panel-c-c {
  background: #f1f2f7;
  position: absolute;
  right: 0px;
  top: 0px;
  bottom: 0px;
  left: 180px;
  overflow-y: auto;
  padding: 0px;
}

.logout {
  background: url(../assets/images/logout_36.png);
  background-size: contain;
  width: 20px;
  height: 20px;
  float: left;
}

.logo {
  margin: 6px 0 6px 10px;
  height: 44px;
  width: 44px;
  float: left;
  color: #fff;
  /* border:2px solid #ffffff;
  border-radius: 10px; */
}
.version {
  position: absolute;
  top: 5px;
  right: 5px;
  font-size: 10px;
  color: #fff;
}

.user-ground {
  margin: 0;
  padding-top: 15px;
  width: 100px;
  height: 30px;
  line-height: 30px;
}

/* .tip-logout {
	float: right;
	margin-right: 20px;
	padding-top: 5px;
	cursor: pointer;
} */

.admin {
  color: #eee;
  text-align: center;
}

.hide-leftMenu {
  left: 0px;
}
</style>
<script>
import leftMenu from "./Common/leftMenu.vue";
import topMenu from "./Common/topMenu.vue";
import changePwd from "./Account/changePwd.vue";
import http from "../assets/js/http";
export default {
  data() {
    return {
      username: "",
      // topMenu: [],
      childMenu: [],
      menuData: [
        {
          title: "Global",
          url: "/home/global",
          name: "global"
        },
        {
          title: "Contral",
          url: "/home/contral",
          name: "contral"
        },
        {
          title: "plan",
          url: "/home/plan",
          name: "plan"
        },
        {
          title: "report",
          url: "/home/report",
          name: "report"
        }
      ],
      hasChildMenu: false,
      menu: null,
      module: null,
      img: "",
      title: "",
      logo_type: null,
      dataReady: false,
      showChange: false,
      version: ""
    };
  },
  methods: {
    hideRightPage() {
      // console.log("123");
      this.$store.dispatch("setShowRightPage", false);
    },
    homeClick() {
      let url = "/home/global";
      router.push(url);
    },
    showChangePage(val) {
      this.showChange = val;
    },
    logout() {
      this.$confirm("Are you sure to exit?", "Warning", {
        confirmButtonText: "Yes",
        cancelButtonText: "No"
      }).then(() => {
        Lockr.rm("rememberPwd");
        Cookies.remove("rememberPwd");
        _g.toastMsg("success", "Exit success");
        setTimeout(() => {
          router.replace("/");
        }, 1500);
      });
    },
    changePwd() {
      this.showChange = true;
    },
    handleMenu(val) {
      switch (val) {
        case "logout":
          this.logout();
          break;
        case "changePwd":
          this.changePwd();
          break;
      }
    },
    getAcBreed() {
      this.apiGet("admin/ac_breed", {}).then(res => {
        this.handelResponse(res, data => {
          this.$store.dispatch("setAcBreed", data);
        });
      });
    },
    getLightBreed() {
      this.apiGet("admin/light_breed", {}).then(res => {
        this.handelResponse(res, data => {
          this.$store.dispatch("setLightBreed", data);
        });
      });
    },
    getLedBreed() {
      this.apiGet("admin/led_breed", {}).then(res => {
        this.handelResponse(res, data => {
          this.$store.dispatch("setLedBreed", data);
        });
      });
    },
    getAddress() {
      this.apiGet("admin/address", {}).then(res => {
        this.handelResponse(res, data => {
          this.$store.dispatch("setAddress", data);
        });
      });
    },
    getFloor() {
      this.apiGet("admin/floor", {}).then(res => {
        this.handelResponse(res, data => {
          this.$store.dispatch("setFloor", data);
        });
      });
    },
    getRoom() {
      this.apiGet("admin/room", {}).then(res => {
        this.handelResponse(res, data => {
          this.$store.dispatch("setRoom", data);
        });
      });
    },
    updateDatabase() {
      this.apiPost("admin/dataBase/updateDatabase", {}).then(res => {
        this.handelResponse(res, data => {
          this.version = data;
        });
      });
    },
    countryArr() {
      var countryArr = [];
      var countryList = [];
      var typeList = ["light", "ac", "led"];
      for (let address of this.$store.state.address) {
        if (countryList.indexOf(address.country) == -1) {
          countryList.push(address.country);
          var mapIportCountryObject = {};
          mapIportCountryObject.name = address.country;
          mapIportCountryObject.selected = true;
          mapIportCountryObject.deviceList = [];
          mapIportCountryObject.deviceTypeNumber = {};
          mapIportCountryObject.warn = 0;
          mapIportCountryObject.watts = 0;
          mapIportCountryObject.usd = 0;
          mapIportCountryObject.addressList = [];
          for (let address2 of this.$store.state.address) {
            if (address.country == address2.country) {
              var addressObject = {};
              addressObject.name = address2.address;
              addressObject.id = address2.id;
              addressObject.country = address2.country;
              addressObject.address = address2.address;
              addressObject.lat = address2.lat;
              addressObject.lng = address2.lng;
              addressObject.ip = address2.ip;
              addressObject.port = address2.port;
              addressObject.mac = address2.mac;
              addressObject.image_full = address2.image_full;
              addressObject.comment = address2.comment;
              addressObject.floorList = [];
              addressObject.deviceList = [];
              addressObject.warn = 0;
              addressObject.watts = 0;
              addressObject.usd = 0;
              addressObject.deviceTypeNumber = {};
              for (var floor of this.$store.state.floor) {
                if (floor.address == address2.id) {
                  var floorObject = {};
                  floorObject.id = floor.id;
                  floorObject.name = floor.floor;
                  floorObject.image_full = floor.image_full;
                  floorObject.comment = floor.comment;
                  floorObject.roomList = [];
                  floorObject.roomArr = [];
                  floorObject.deviceList = [];
                  floorObject.deviceTypeNumber = {};
                  floorObject.warn = 0;
                  floorObject.watts = 0;
                  floorObject.usd = 0;
                  for (var room of this.$store.state.room) {
                    if (
                      room.floor == floor.id &&
                      room.address == floor.address
                    ) {
                      var roomObject = {};
                      roomObject.name = room.room;
                      roomObject.id = room.id;
                      roomObject.image = room.image;
                      roomObject.room = room.room;
                      roomObject.room_name = room.room_name;
                      roomObject.floor = room.floor;
                      roomObject.address = room.address;
                      roomObject.width = room.width;
                      roomObject.height = room.height;
                      roomObject.lat = room.lat;
                      roomObject.lng = room.lng;
                      roomObject.image_full = room.image_full;
                      roomObject.comment = room.comment;
                      roomObject.typeList = [];
                      roomObject.typeArr = [];
                      roomObject.deviceList = [];
                      roomObject.deviceTypeNumber = {};
                      roomObject.warn = 0;
                      roomObject.watts = 0;
                      roomObject.usd = 0;
                      // for(var type of typeList){
                      //     var typeObject = {};
                      //     typeObject.name = type;
                      //     typeObject.deviceList = [];
                      //     roomObject.typeList.push(typeObject);

                      // }
                      floorObject.roomList.push(roomObject);
                    }
                  }
                  addressObject.floorList.push(floorObject);
                }
              }
              mapIportCountryObject.addressList.push(addressObject);
            }
          }
          countryArr.push(mapIportCountryObject);
        }
      }
      // console.log(countryArr);
      var warn = 0;
      // var initAddress = this.$store.state.address
      // var initFloor = this.$store.state.floor
      // var initRoom = this.$store.state.room
      //this.devices原始设备数据
      var addresss = this.$store.state.address;
      var ac_breeds = this.$store.state.ac_breed;
      var light_breeds = this.$store.state.light_breed;
      var led_breeds = this.$store.state.led_breed;
      for (var item of this.devices) {
        item.warn = false;
        var warnDeviceList = ["light", "ac", "led"];
        if (warnDeviceList.indexOf(item.devicetype) != -1) {
          if (item.on_off == true) {
            for (var breed of this.$store.state[item.devicetype + "_breed"]) {
              var run_time = parseInt(breed.run_time) * 36000;
              if (
                breed.run_time != 0 &&
                item.breed == breed.id &&
                item.run_time >= run_time
              ) {
                item.warn = true;
                warn += 1;
              }
            }
          }
        }
        item.watts = 0;
        item.usd = 0;
        if (item.on_off == true) {
          switch (item.devicetype) {
            case "ac":
              for (var ac_breed of ac_breeds) {
                if (item.breed == ac_breed.id) {
                  item.watts =
                    parseInt(ac_breed[item.mode]) +
                    parseInt(ac_breed[item.grade]);
                }
              }
              break;
            case "light":
              for (var light_breed of light_breeds) {
                if (item.breed == light_breed.id) {
                  item.watts = parseInt(light_breed.watts);
                }
              }
              break;
            case "led":
              for (var led_breed of led_breeds) {
                if (item.breed == led_breed.id) {
                  item.watts = parseInt(led_breed.watts);
                }
              }
              break;
          }
          if (item.watts) {
            item.watts = parseFloat(item.watts / 1000 / 6);
            item.usd = 0;
            for (var address of addresss) {
              if (address.address == item.address && address.kw_usd) {
                item.usd = item.watts * parseFloat(address.kw_usd);
              }
            }
          }
        }
        //筛选重复国家
        // if (countryList.indexOf(item.country) == -1) {
        //   countryList.push(item.country);
        //   var mapIportCountryObject = {};
        //   mapIportCountryObject.name = item.country;
        //   mapIportCountryObject.selected = true;
        //   mapIportCountryObject.addressList = [];
        //   mapIportCountryObject.addressArr = [];
        //   mapIportCountryObject.deviceList = [];
        //   mapIportCountryObject.deviceTypeNumber = {};
        //   mapIportCountryObject.warn = 0;
        //   // mapIportCountryObject.deviceList = {}
        //   countryArr.push(mapIportCountryObject);
        // }
        for (var country of countryArr) {
          //筛选重复类型
          if (item.country == country.name) {
            // if (country.addressArr.indexOf(item.address) == -1) {
            //   country.addressArr.push(item.address);
            //   var addressObject = {};

            //   addressObject.name = item.address;
            //   addressObject.id = item.addressid;
            //   addressObject.country = item.country;
            //   addressObject.address = item.address;
            //   addressObject.lat = item.lat;
            //   addressObject.lng = item.lng;
            //   addressObject.ip = item.ip;
            //   addressObject.port = item.port;
            //   addressObject.mac = item.mac;
            //   addressObject.floorList = [];
            //   addressObject.floorArr = [];
            //   addressObject.deviceList = [];
            //   addressObject.warn = 0;
            //   // addressObject.typeList = []
            //   // addressObject.typeArr = []
            //   addressObject.deviceTypeNumber = {};
            //   //设置楼层数组长度
            //   // let floor_num = 0
            //   // for(let address of initAddress){
            //   // 	if(address.address == item.address){
            //   // 		floor_num = address.floor_num
            //   // 	}
            //   // }
            //   // addressObject.floorList = Array.apply(null, {length: floor_num});
            //   country.addressList.push(addressObject);
            // }
            country.deviceList.push(item);
            if (item.warn) {
              country.warn += 1;
            }
            if (item.watts) {
              country.watts += item.watts;
            }
            if (item.usd) {
              country.usd += item.usd;
            }
            //计算各种设备类型的数量
            country.deviceTypeNumber[item.devicetype]
              ? (country.deviceTypeNumber[item.devicetype] += 1)
              : (country.deviceTypeNumber[item.devicetype] = 1);
            for (var address of country.addressList) {
              //筛选重复类型
              if (item.address == address.id) {
                // if (address.floorArr.indexOf(item.floor) == -1) {
                //   address.floorArr.push(item.floor);
                //   var floorObject = {};
                //   floorObject.name = item.floor;
                //   floorObject.roomList = [];
                //   floorObject.roomArr = [];
                //   floorObject.deviceList = [];
                //   floorObject.deviceTypeNumber = {};
                //   floorObject.warn = 0;
                //   address.floorList.push(floorObject);
                // }
                address.deviceList.push(item);
                if (item.warn) {
                  address.warn += 1;
                }
                if (item.watts) {
                  address.watts += item.watts;
                }
                if (item.usd) {
                  address.usd += item.usd;
                }
                address.deviceTypeNumber[item.devicetype]
                  ? (address.deviceTypeNumber[item.devicetype] += 1)
                  : (address.deviceTypeNumber[item.devicetype] = 1);
                for (var floor of address.floorList) {
                  //筛选重复类型
                  if (item.floor == floor.id) {
                    // if (floor.roomArr.indexOf(item.room) == -1) {
                    //   floor.roomArr.push(item.room);
                    //   var roomObject = {};
                    //   roomObject.name = item.room;
                    //   roomObject.room_name = item.room_name;
                    //   roomObject.typeList = [];
                    //   roomObject.typeArr = [];
                    //   roomObject.deviceList = [];
                    //   roomObject.deviceTypeNumber = {};
                    //   roomObject.warn = 0;
                    //   floor.roomList.push(roomObject);
                    // }
                    floor.deviceList.push(item);
                    if (item.warn) {
                      floor.warn += 1;
                    }
                    if (item.watts) {
                      floor.watts += item.watts;
                    }
                    if (item.usd) {
                      floor.usd += item.usd;
                    }
                    floor.deviceTypeNumber[item.devicetype]
                      ? (floor.deviceTypeNumber[item.devicetype] += 1)
                      : (floor.deviceTypeNumber[item.devicetype] = 1);
                    for (var room of floor.roomList) {
                      if (item.room == room.id) {
                        if (room.typeArr.indexOf(item.devicetype) == -1) {
                          room.typeArr.push(item.devicetype);
                          var typeObject = {};
                          typeObject.name = item.devicetype;
                          typeObject.deviceList = [];
                          room.typeList.push(typeObject);
                        }
                        room.deviceList.push(item);
                        if (item.warn) {
                          room.warn += 1;
                        }
                        if (item.watts) {
                          room.watts += item.watts;
                        }
                        if (item.usd) {
                          room.usd += item.usd;
                        }
                        room.deviceTypeNumber[item.devicetype]
                          ? (room.deviceTypeNumber[item.devicetype] += 1)
                          : (room.deviceTypeNumber[item.devicetype] = 1);
                        for (var type of room.typeList) {
                          //筛选重复类型
                          if (item.devicetype == type.name) {
                            type.deviceList.push(item);
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
      // console.log(countryArr);
      this.$store.dispatch("setWarn", warn);
      this.$store.dispatch("setCountryArr", countryArr);
      this.dataReady = true;
      // return countryArr
    },
    createSocket(devices) {
      var vm = this
      if (window.socketio && window.socketio.io) {
        window.socketio.removeAllListeners();
      }
      let userInfo = Lockr.get("userInfo");
      let port = userInfo.port;
      var socketio = socket("http://" + document.domain + ":" + port);
      window.socketio = socketio;
      socketio.on("udp", function(udp) {
        for (var device of devices) {
          if (
            device.subnetid == udp.subnetid &&
            device.deviceid == udp.deviceid
          ) {
            var udpDevice = {
              subnetid: udp.subnetid,
              deviceid: udp.deviceid,
              channel:udp.channel?udp.channel:"",
              operatorCode: udp.operatorCode
            };
            vm.$store.dispatch("setUdpDevice", udpDevice);
            switch (device.devicetype) {
              case "ac":
                for (var key in udp.deviceProperty) {
                  device.deviceProperty[key] = udp.deviceProperty[key];
                }
                break;
              case "light":
                if (device.channel == udp.channel) {
                  for (var key in udp.deviceProperty) {
                    device.deviceProperty[key] = udp.deviceProperty[key];
                  }
                }
                break;
              case "led":
                for (var key in udp.deviceProperty) {
                  device.deviceProperty[key] = udp.deviceProperty[key];
                }
                break;
              case "curtain":
                break;
              case "floorheat":
                if (udp.operatorCode == "03cc") {
                  for (var key in udp.deviceProperty) {
                    device.deviceProperty[key] = udp.deviceProperty[key];
                  }
                } else if (udp.operatorCode == "e3e8") {
                  if (
                    udp.subnetid == device.deviceProperty.insideSubnetID &&
                    udp.deviceid == device.deviceProperty.insideDeviceID
                  ) {
                    var insideChannel = parseInt(
                      "0x" + device.deviceProperty.insideChannel
                    );
                    var insideTemperature =
                      _g.getadditional(udp.msg, insideChannel + 8) == "00"
                        ? parseInt(
                            "0x" + _g.getadditional(udp.msg, insideChannel)
                          )
                        : 0 -
                          parseInt(
                            "0x" + _g.getadditional(udp.msg, insideChannel)
                          );
                  }
                  if (
                    udp.subnetid == device.deviceProperty.outsideSubnetID &&
                    udp.deviceid == device.deviceProperty.outsideDeviceID
                  ) {
                    var outsideChannel = parseInt(
                      "0x" + device.deviceProperty.outsideChannel
                    );
                    var outsideTemperature =
                      _g.getadditional(udp.msg, outsideChannel + 8) == "00"
                        ? parseInt(
                            "0x" + _g.getadditional(udp.msg, outsideChannel)
                          )
                        : 0 -
                          parseInt(
                            "0x" + _g.getadditional(udp.msg, outsideChannel)
                          );
                  }
                } else {
                  if (device.channel == udp.channel) {
                    for (var key in udp.deviceProperty) {
                      device.deviceProperty[key] = udp.deviceProperty[key];
                    }
                  }
                }
                break;
              case "security":
                break;
            }
          }
        }
      });
    }
    // addDeviceProperty(devices) {
    //   for (var device of devices) {
    //     switch (device.devicetype) {
    //       case "ac":
    //         device.deviceProperty = {
    //           on_off: device.on_off,
    //           coolTmp: 26,
    //           autoTmp: 0,
    //           heatTmp: 0,
    //           tmp: 26,
    //           grade: 2,
    //           mode: "cool",
    //           cool_strat: 0,
    //           cool_end: 36,
    //           heat_strat: 0,
    //           heat_end: 36,
    //           auto_strat: 0,
    //           auto_end: 36
    //         };
    //         break;
    //       case "light":
    //         device.deviceProperty = {
    //           brightness: 0,
    //           on_off: device.on_off
    //         };
    //         break;
    //       case "led":
    //         device.deviceProperty = {
    //           on_off: device.on_off,
    //           brightness: 0,
    //           color: device.mode
    //           // red: "c0",
    //           // green: "cc",
    //           // blue: "da"
    //         };
    //         break;
    //       case "curtain":
    //         device.deviceProperty = {
    //           on_off: device.on_off,
    //           brightness: 0
    //         };
    //         break;
    //       case "floorheat":
    //         device.deviceProperty = {
    //           on_off: device.on_off,
    //           manualTemperature: 26,
    //           dayTemperature: 26,
    //           nightTemperature: 26,
    //           awayTemperature: 26,
    //           alarmTemperature: 26,
    //           mode: "manual",
    //           dayTime: "",
    //           nightTime: "",
    //           insideTemperature: 26,
    //           outsideTemperature: 26,
    //           insideSensor: {
    //             targetSubnetID: "",
    //             targetDeviceID: "",
    //             channel: ""
    //           }
    //         };
    //         break;
    //       case "security":
    //         device.deviceProperty = {
    //           on_off: device.on_off
    //         };
    //         break;
    //       case "ir":
    //         device.deviceProperty = {
    //           on_off: device.on_off
    //         };
    //         break;
    //       case "music":
    //         device.deviceProperty = {
    //           vol: 20,
    //           mode: "random",
    //           on_off: device.on_off,
    //           music_name: "Waitting",
    //           music_autor: "Waitting",
    //           time_now: 0,
    //           time_over: 0,
    //           albumno: 0,
    //           albumlist: [],
    //           songno: 0,
    //           songList: [
    //             {
    //               songNo: 1,
    //               songName: "Waitting",
    //               select: true
    //             }
    //           ],
    //           songListAll: [],
    //           musicLoading: true,
    //           source: "01"
    //         };
    //         break;
    //     }
    //   }
    //   this.createSocket(devices);
    // }
  },
  created() {
    console.log("home");
    // let username = Lockr.get("username");
    // let password = Lockr.get("password");
    // this.username = username;
    // if (!username || !password) {
    //   _g.toastMsg("warning", "You are not logged in yet");
    //   setTimeout(() => {
    //     router.replace("/");
    //   }, 1500);
    //   return;
    // }

    // this.$store.dispatch("setShowHotel", true);
    // this.$store.dispatch("setShowFloor", false);
    // this.$store.dispatch("setShowRoom", false);
  },
  mounted() {
    var height = document.body.clientHeight - 60;
    var leftMenu = this.$refs.leftMenu;
    leftMenu.style.height = height + "px";
    this.getAcBreed();
    this.getLightBreed();
    this.getLedBreed();
    this.getAddress();
    this.getFloor();
    this.getRoom();
    this.updateDatabase();
    this.apiGet("admin/device", {}).then(res => {
      this.handelResponse(res, data => {
        _g.addDeviceProperty(data);
        this.$store.dispatch("setDevices", data);
        // var devices = [];
        var maxid = data[0].maxid;
        this.$store.dispatch("setMaxid", maxid);
        // for (var device of res) {
        //   if (device.status == "enabled") {
        //     devices.push(device);
        //   }
        // }
        // this.devices = devices
        this.countryArr();
      });
    });
  },
  components: {
    leftMenu,
    topMenu,
    changePwd
  },
  computed: {
    devices() {
      return this.$store.state.devices;
    },
    address() {
      return this.$store.state.address;
    },
    floor() {
      return this.$store.state.floor;
    },
    room() {
      return this.$store.state.room;
    },
    ac_breed() {
      return this.$store.state.ac_breed;
    },
    light_breed() {
      return this.$store.state.light_breed;
    },
    led_breed() {
      return this.$store.state.led_breed;
    },
    globalLoading() {
      return store.state.globalLoading;
    }
  },
  watch: {
    devices: {
      handler: function(val, oldVal) {
        // console.log("deviceChange");
        // _g.addDeviceProperty(val);
        this.createSocket(val);
        this.countryArr();
      },
      deep: false
    },
    address: {
      handler: function(val, oldVal) {
        this.countryArr();
      },
      deep: true
    },
    floor: {
      handler: function(val, oldVal) {
        this.countryArr();
      },
      deep: true
    },
    room: {
      handler: function(val, oldVal) {
        this.countryArr();
      },
      deep: true
    },
    ac_breed: {
      handler: function(val, oldVal) {
        this.countryArr();
      },
      deep: true
    },
    light_breed: {
      handler: function(val, oldVal) {
        this.countryArr();
      },
      deep: true
    },
    led_breed: {
      handler: function(val, oldVal) {
        this.countryArr();
      },
      deep: true
    }
  },
  mixins: [http]
};
</script>
