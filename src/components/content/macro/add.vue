<template>
    <div  v-loading="isLoading">
        <div class="p-20 macro-add">
          <el-row class="m-b-10">
            <el-input  class="fl w-230" placeholder="Please enter the macro" v-model="macro.macro">
                <template slot="prepend">Macro</template>
            </el-input>
          </el-row>
            <div class="m-b-10 ovf-hd">
                <div class="fl" >
                  <el-cascader :options="allAddress" change-on-select @change="addressChange" style="width:230px;"></el-cascader>
                </div>
                <div class="fl w-230" style="margin-left:23px;">
                    <el-input placeholder="Please enter the model" v-model="keywords">
                        <el-button slot="append" icon="el-icon-search" @click="search()"></el-button>
                    </el-input>
                </div>
            </div>
            <el-table ref="deviceTable" :data="tableData"  :height="400" style="width: 20%;display: inline-block"  @selection-change="selectItem">
                <el-table-column type="selection" width="50">
                </el-table-column>
                <el-table-column label="Device" prop="device">
                </el-table-column>
            </el-table>
            <el-table :data="commands" :height="400" style="width: 75%;display: inline-block" class=" m-l-20">
                <el-table-column
                  width="60">
                  <template slot-scope="scope">
                    <el-button  size="small" class = "el-icon-close" style="color:#FF4949;" align="center" @click="deleteCommand(scope)"></el-button>
                  </template>
                </el-table-column>
                <el-table-column
                  width="200">
                  <template slot-scope="scope">
                    <el-input-number  v-model="commands[scope.$index].time"></el-input-number>
                  </template>
                </el-table-column>
                <el-table-column label="Device" prop="device" width="120" align="center">
                </el-table-column>
                <el-table-column label="Type" prop="devicetype" width="120" align="center">
                </el-table-column>
                <el-table-column label="Switch" prop="on_off" width="120"  align="center">
                    <template slot-scope="scope">
                        <el-switch  v-model="commands[scope.$index].on_off" @change="switch_change(commands[scope.$index])" >
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column  prop="device" width="200" align="center" >
                    <template slot-scope="scope">
                        <div v-if="commands[scope.$index].devicetype == 'ac'">
                          <el-slider v-model="commands[scope.$index].operation_1" :min='0' :max='32' :step="1">
                          </el-slider>
                        </div>
                        <div v-if="commands[scope.$index].devicetype == 'light'">
                          <el-slider v-model="commands[scope.$index].mode" :min='0' :max='100' :step="1">
                          </el-slider>
                        </div>
                        <div v-if="commands[scope.$index].devicetype == 'music'">
                          <el-slider v-model="commands[scope.$index].operation_1" :min='0' :max='100' :step="1">
                          </el-slider>
                        </div>
                        <div v-if="commands[scope.$index].devicetype == 'led'">
                          <colorPicker  :color="commands[scope.$index].mode" v-on:accept="(val)=>{
                            commands[scope.$index].mode = val.hex
                          }"></colorPicker>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column width="120" prop="mode" align="center" >
                    <template slot-scope="scope">
                      <el-select v-if="commands[scope.$index].devicetype == 'ac'" v-model="commands[scope.$index].mode" placeholder="">
                        <el-option
                          v-for="item in modes"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
                      <el-select @change="sourceChange(commands[scope.$index])" v-if="commands[scope.$index].devicetype == 'music'" v-model="commands[scope.$index].operation_2" placeholder="">
                        <el-option
                          v-for="item in musicSource"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
                    </template>
                </el-table-column>
                <el-table-column  prop="grade" width="120"  align="center">
                    <template slot-scope="scope">
                      <el-select v-if="commands[scope.$index].devicetype == 'ac'" v-model="commands[scope.$index].grade" placeholder="">
                        <el-option
                          v-for="item in grades"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
                      <el-select @change="albumChange(commands[scope.$index])" v-if="commands[scope.$index].devicetype == 'music'" v-model="commands[scope.$index].operation_3" placeholder="">
                        <el-option
                          v-for="album in commands[scope.$index].deviceProperty.albumlist"
                          :key="album.albumNo"
                          :label="album.albumName"
                          :value="album.albumNo">
                        </el-option>
                      </el-select>
                    </template>
                </el-table-column>
                <el-table-column  width="200"  align="center">
                    <template slot-scope="scope">
                      <el-select v-if="commands[scope.$index].devicetype == 'music'" v-model="commands[scope.$index].operation_4" placeholder="">
                        <el-option
                          v-for="song in commands[scope.$index].deviceProperty.songList"
                          :key="song.songNo"
                          :label="song.songName"
                          :value="song.songNo">
                        </el-option>
                      </el-select>
                    </template>
                </el-table-column>
                <el-table-column label="Address" prop="address" width="150" align="center">
                </el-table-column>
                <el-table-column label="Floor" prop="floor" width="150" align="center">
                </el-table-column>
                <el-table-column label="Room" prop="room_name" width="150" align="center">
                </el-table-column>
                <!-- <el-table-column label="status_1" prop="operation_1" width="150" align="center">
                </el-table-column>
                <el-table-column label="status_2" prop="operation_2" width="150" align="center">
                </el-table-column>
                <el-table-column label="status_3" prop="operation_3" width="150" align="center">
                </el-table-column>
                <el-table-column label="status_4" prop="operation_4" width="150" align="center">
                </el-table-column>
                <el-table-column label="status_5" prop="operation_5" width="150" align="center">
                </el-table-column> -->
                
            </el-table>
            <div class="pos-rel p-t-20">
                <div class="fr" style="margin-right:35px;">
                    <el-button type="primary" @click="save()" >Save</el-button>
                <el-button @click="goback()">Cancel</el-button>
                </div>
                <!-- <div class="block pages">
                    <el-pagination @current-change="handleCurrentChange" layout="prev, pager, next" :page-size="limit" :current-page="currentPage" :total="dataCount">
                    </el-pagination>
                </div> -->
            </div>
        </div>
    </div>
</template>

<style>
.macro-add .vc-container {
  z-index: 9999;
}
.macro-add .week-select-div {
  position: relative;
  width: 230px;
  height: 40px;
}
.macro-add .week-select-div .week-select {
  position: absolute;
  top: 0;
  left: 0;
}
</style>

<script>
// import setting from "./setting";
import http from "../../../assets/js/http.js";
import list from "../../../assets/js/list.js";
import colorPicker from "vue-color-picker";
import musicApi from "../devices/music/api";
export default {
  //  currentPage        页码
  //  keywords           关键字
  //  multipleSelection  被选中的数据
  //  limit              每页最大行数
  data() {
    return {
      commands: [],
      tableData: [],
      devicesId: [],
      dataCount: null,
      currentPage: null,
      keywords: "",
      multipleSelection: [],
      limit: 15,
      add: true,
      address: "",
      floor: "",
      room: "",
      time: "",
      isLoading: true,
      modes: [
        {
          value: "cool",
          label: "Cool"
        },
        {
          value: "heat",
          label: "Heat"
        },
        {
          value: "fan",
          label: "Fan"
        },
        {
          value: "auto",
          label: "Auto"
        }
      ],
      grades: [
        {
          value: "low",
          label: "Low"
        },
        {
          value: "medial",
          label: "Medial"
        },
        {
          value: "hign",
          label: "Hign"
        },
        {
          value: "wind_auto",
          label: "Auto"
        }
      ],
      timeType: "",
      timeTypeArr: [
        {
          value: "once",
          label: "once"
        },
        {
          value: "day",
          label: "day"
        },
        {
          value: "week",
          label: "week"
        }
      ],
      week: [],
      weekArr: [
        {
          value: "sun",
          label: "Sun"
        },
        {
          value: "mon",
          label: "Mon"
        },
        {
          value: "tues",
          label: "Tues"
        },
        {
          value: "wed",
          label: "Wed"
        },
        {
          value: "thur",
          label: "Thur"
        },
        {
          value: "fri",
          label: "Fri"
        },
        {
          value: "sat",
          label: "Sat"
        }
      ],
      musicSource: [
        {
          value: "01",
          label: "SD card"
        },
        {
          value: "02",
          label: "FTP"
        }
      ]
    };
  },
  methods: {
    dateChange() {
      this.macro.time_1 = _g.formatDate(this.macro.time_1);
    },
    sourceChange(command){
      command.deviceProperty.source = command.operation_2;
      command.operation_3 = "";
      command.operation_4 = "";
      musicApi.source_change(command, command.deviceProperty);
      command.deviceProperty.albumlist = [];
      command.deviceProperty.songList = [];
      command.deviceProperty.songListAll = [];
      // command.deviceProperty.musicLoading = true;
      var music = Lockr.get(
        "music_" + command.id + "_" + command.deviceProperty.source
      );
      if (music) {
        command.deviceProperty.albumlist = music.albumlist;
        command.deviceProperty.songList = music.songList;
        command.deviceProperty.songListAll = music.songList;
        // command.deviceProperty.musicLoading = false;
      } else {
        musicApi.readStatus(command, command.deviceProperty);
      }
    },
    albumChange(command){
      command.deviceProperty.songList = []
      command.operation_4 = "";
      for(var song of command.deviceProperty.songListAll){
        if(song.albumNo == command.operation_3){
          command.deviceProperty.songList.push(song)
        }
      }
    },
    selectionChange(selection, row) {
      // console.log(selection);
      // console.log(row);
      for (var index in this.commands) {
        if (this.commands[index].id == selection.id) {
          this.commands.splice(index, 1);
        }
      }
    },
    goback() {
      this.$emit("goback", false);
    },
    headleChangeColor(val, index) {
      // this.commands[index].mode =val
    },
    switch_change(row) {
      // console.log(row);
    },
    addressChange(value) {
      var len = value.length;
      switch (len) {
        case 1:
          this.address = value[0];
          this.floor = "";
          this.room = "";
          break;
        case 2:
          this.address = value[0];
          this.floor = value[1];
          this.room = "";
          break;
        case 3:
          this.address = value[0];
          this.floor = value[1];
          this.room = value[2];
          break;
      }
      this.init();
    },
    //获取被选中的数据
    selectItem(val) {
      for (var device of val) {
        if (this.devicesId.indexOf(device.id) == -1) {
          // this.$set(device,"operation_1", device.operation_1 ? device.operation_1 : "");
          // this.$set(device,"operation_2", device.operation_2 ? device.operation_2 : "");
          // this.$set(device,"operation_3", device.operation_3 ? device.operation_3 : "");
          // this.$set(device,"operation_4", device.operation_4 ? device.operation_4 : "");
          // this.$set(device,"operation_5", device.operation_5 ? device.operation_5 : "");
          // device.operation_1 = "";
          // device.operation_2 = "";
          // device.operation_3 = "";
          // device.operation_4 = "";
          // device.operation_5 = "";
          if (device.devicetype == "ac") {
            device.operation_1 = device.operation_1
              ? parseInt(device.operation_1)
              : 0;
            device.mode = device.mode ? device.mode : "auto";
            device.grade = device.grade ? device.grade : "wind_auto";
          }
          if (device.devicetype == "music") {
            device.operation_1 = device.operation_1
              ? parseInt(device.operation_1)
              : 80;
            device.operation_2 = device.operation_2 ? device.operation_2 : "01";
            device.operation_3 = device.operation_3;
            device.operation_4 = device.operation_4;
            var musicObj = Lockr.get(
              "music_" + device.id + "_" + device.operation_2
            );
            device.deviceProperty = {}
            if(musicObj){
              device.deviceProperty = musicObj;
            }
            else{
              device.deviceProperty.source = device.operation_2
              device.deviceProperty.albumlist = []
              device.deviceProperty.songList = []
              device.deviceProperty.songListAll = []
              musicApi.readStatus(device,device.deviceProperty);
            }
          }
          if (device.devicetype == "light") {
            device.mode = device.mode ? device.mode : 0;
            device.mode = parseInt(device.mode);
          }
          this.commands.push(device);
          this.devicesId.push(device.id);
        }
      }
    },
    deleteCommand(scope) {
      const data = {
        params: {
          id: scope.row.macro_id
        }
      };
      this.apiGet(
        "device/macro.php?action=delete_command",
        data
      ).then(res => {
        if (res[0]) {
          _g.toastMsg("success", res[1]);
          this.search_command();
        } else {
          _g.toastMsg("error", res[1]);
        }
        // for (var key in this.form) {
        //     this.form[key] = ""
        // }
        // this.isLoading = !this.isLoading;
      });
    },
    save() {
      if (this.macro.macro == "") {
        _g.toastMsg("error", "Please enter the name of macro");
        return;
      }
      this.isLoading = true;
      var devices = []
      for (var command of this.commands) {
        var device= {}
        device.id = command.id
        device.on_off = command.on_off ? "1" : "0";
        device.mode = command.mode
        device.grade = command.grade
        device.operation_1 = command.operation_1
        device.operation_2 = command.operation_2
        device.operation_3 = command.operation_3
        device.operation_4 = command.operation_4
        device.operation_5 = command.operation_5
        device.time = command.time
        devices.push(device)
      }
      this.macro.devices = devices

      const data = {
        params: this.macro
      };
      // console.log(data);
      this.apiGet(
        "device/macro.php?action=insert_command",
        data
      ).then(res => {
        if (res[0]) {
          _g.toastMsg("success", res[1]);
          this.goback();
        } else {
          this.isLoading = true;
          _g.toastMsg("error", res[1]);
        }
        // for (var key in this.form) {
        //     this.form[key] = ""
        // }
        // this.isLoading = !this.isLoading;
      });
    },
    getAllDevices() {
      var data = [];
      for (var device of this.devices) {
        if (device.devicetype != "security" && device.devicetype != "ir") {
          if (this.address == "" || device.address == this.address) {
            if (this.floor == "" || device.floor == this.floor) {
              if (this.room == "" || device.room == this.room) {
                if (this.keywords == "" || device.device == this.keywords) {
                  data.push(device);
                }
              }
            }
          }
        }
      }
      this.tableData = data;
    },
    //初始化时统一加载
    init() {
      // this.getKeywords();
      // this.getCurrentPage();
      this.getAllDevices();
    },
    search_command() {
      for (var device of this.tableData) {
        this.$refs.deviceTable.toggleRowSelection(device, false);
      }
      this.commands = [];
      this.devicesId = [];
      const data = {
        params: {
          macro: this.macro.id
        }
      };
      var vm = this;
      this.apiGet(
        "device/macro.php?action=search_command",
        data
      ).then(res => {
        for (var command of res) {
          command.time = command.time? parseInt(command.time):0
          vm.devicesId.push(command.id);
          for (var index in vm.tableData) {
            if (vm.tableData[index].id == command.id) {
              vm.$refs.deviceTable.toggleRowSelection(
                vm.tableData[index],
                true
              );
            }
          }
          command.on_off = command.on_off == "1" ? true : false;
          command.operation_1 = parseInt(command.status_1);
          command.operation_2 = parseInt(command.status_2);
          command.operation_3 = parseInt(command.status_3);
          command.operation_4 = parseInt(command.status_4);
          command.operation_5 = parseInt(command.status_5);
          if (command.devicetype == "ac") {
            command.operation_1 = parseInt(command.status_1);
            command.operation_2 = parseInt(command.status_2);
            command.operation_3 = parseInt(command.status_3);
          }
          if (command.devicetype == "music") {
            command.operation_1 = command.status_1
              ? parseInt(command.status_1)
              : 0;
            command.operation_2 = command.status_2 ? command.status_2 : "01";
            command.operation_3 = command.status_3;
            command.operation_4 = command.status_4;
            var musicObj = Lockr.get(
              "music_" + command.id + "_" + command.operation_2
            );
            command.deviceProperty = {}
            if (musicObj) {
              command.deviceProperty = musicObj;
            } else {
              command.deviceProperty.source = command.status_2;
              command.deviceProperty.albumlist = [];
              command.deviceProperty.songList = [];
              command.deviceProperty.songListAll = [];
              musicApi.readStatus(command, command.deviceProperty);
            }
          }
          if (command.devicetype == "light") {
            command.mode = parseInt(command.mode);
          }
          vm.commands.push(command);
        }
        vm.isLoading = false;
      });
    }
  },
  created() {
    console.log("macro-add");
    this.init();
  },
  mounted() {
    this.search_command();
  },
  components: {
    // setting,
    colorPicker
  },
  computed: {
    devices() {
      return this.$store.state.devices;
    },
    macro() {
      return this.data;
    },
    allAddress() {
      var allAddress = [];
      for (var address of this.$store.state.address) {
        var addressObj = {
          value: address.address,
          label: address.address,
          children: []
        };
        for (var floor of this.$store.state.floor) {
          if (floor.address == address.address) {
            var floorObj = {
              value: floor.floor,
              label: "floor " + floor.floor,
              children: []
            };
            for (var room of this.$store.state.room) {
              if (
                room.floor == floor.floor &&
                room.address == address.address
              ) {
                var roomObj = {
                  value: room.room,
                  label: room.room_name
                };
                floorObj.children.push(roomObj);
              }
            }
            addressObj.children.push(floorObj);
          }
        }
        allAddress.push(addressObj);
      }
      // console.log(allAddress)
      return allAddress;
    }
  },
  props: ["data"],
  watch: {
    $route(to, from) {
      this.init();
    }
    // devices: {
    //   handler: function(val, oldVal) {
    //     this.init();
    //   },
    //   deep: true
    // }
  },
  mixins: [http, list]
};
</script>