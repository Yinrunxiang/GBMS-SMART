<template>
    <div  v-loading="isLoading">
        <div class="p-20 schedule-add">
            <el-row  :gutter="20">
            <el-col :xs= "22" :md = "{span:6}" class="  m-b-10">
             <el-input  class="w-100p" placeholder="Please enter the schedule" v-model="schedule.schedule">
                        <template slot="prepend">Schedule</template>
              </el-input>
            </el-col>
             <el-col :xs= "22" :md = "{span:6}" class="  m-b-10">
               <el-select class="w-100p" v-model="schedule.type" placeholder="">
                        <el-option
                          v-for="(item,key) in timeTypeArr"
                          :key="key"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
            </el-col>
               <el-col v-if="schedule.type == 'week'" :xs= "22" :md = "{span:6}" class=" week-select-div  m-b-10">
             <el-select class="w-100p week-select "
                          v-model="schedule.week"
                          multiple
                          placeholder="Please choose">
                          <el-option
                            v-for="(item,key) in weekArr"
                            :key="key"
                            :label="item.label"
                            :value="item.value">
                          </el-option>
                        </el-select>
            </el-col>
              <el-col v-if="schedule.type == 'once'" :xs= "22" :md = "{span:6}" class=" m-b-10">
             <el-date-picker class="w-100p"
                    v-model="schedule.time_1"
                    type="datetime"
                    @change = "dateChange()"
                    placeholder="Please choose">
                  </el-date-picker>
            </el-col>
             <el-col v-if="schedule.type == 'day' || schedule.type == 'week'" :xs= "22" :md = "{span:6}" class=" m-b-10">
            <el-time-picker class="w-100p"
                    v-model="schedule.time_2"
                    value-format="HH:mm"
                    :picker-options="{
                      format:'HH:mm'
                    }"
                    
                    placeholder="Please choose">
                  </el-time-picker>
            </el-col>
            <el-col :xs= "22" :md = "{span:6}" class=" m-b-10">
              <el-input  class="w-100p" v-model="schedule.comment">
                <template slot="prepend">Comment</template>
              </el-input>
            </el-col>
          </el-row>
            
            <el-row :gutter="20" class="ovf-hd">
              <el-col :xs= "22" :md = "{span:6}" class=" m-b-10">
                <el-select class="w-100p" v-model="dataType" placeholder="Please choose">
                    <el-option
                      v-for="(item,key) in dataTypeList"
                      :key="key"
                      :label="item.label"
                      :value="item.value">
                    </el-option>
                  </el-select>
              </el-col>
              <el-col v-show="dataType == '1'" :xs= "22" :md = "{span:6}" class=" m-b-10">
                  <el-cascader class="w-100p" :options="allAddress" change-on-select @change="addressChange" ></el-cascader>
              </el-col>
              <el-col v-show="dataType == '1'" :xs= "22" :md = "{span:6}" class=" m-b-10">
                   <el-input  class="w-100p" placeholder="Please enter the model" v-model="keywords">
                        <el-button slot="append" icon="el-icon-search" @click="search()"></el-button>
                    </el-input>
              </el-col>
            </el-row>
            <el-row :gutter="20">
              <el-col v-show="dataType == '1'" :xs= "22" :md = "{span:6}" class="  m-b-10">
                <el-table  ref="deviceTable" :data="tableData"  :height="400" >
                    <el-table-column
                      width="40" >
                      <template slot-scope="scope" >
                        <div class="tx-c" @click="selectDevice(scope.row)" style="cursor: pointer;">
                        <a  class = "el-icon-circle-plus-outline" style="margin-top:3px;font-size:20px;color:#409EFF;" ></a>
                        </div>
                      </template>
                    </el-table-column>
                    <el-table-column label="Device" prop="device">
                    </el-table-column>
                </el-table>
              </el-col>
              <el-col v-show="dataType == '2'" :xs= "22" :md = "{span:6}" class="  m-b-10">
                <el-table  :data="marcoData"  :height="400" >
                <el-table-column
                  width="40" >
                  <template slot-scope="scope" >
                    <div class="tx-c" @click="selectMarco(scope.row)" style="cursor: pointer;">
                    <a  class = "el-icon-circle-plus-outline" style="margin-top:3px;font-size:20px;color:#409EFF;" ></a>
                    </div>
                  </template>
                </el-table-column>
                <el-table-column label="Macro" prop="macro">
                </el-table-column>
                </el-table>
              </el-col>
              <el-col  :xs= "22" :md = "{span:18}" class="  m-b-10">
                    <el-table :data="commands" :height="400">
                <el-table-column
                  width="60">
                  <template slot-scope="scope">
                    <el-button  size="small" class = "el-icon-close" style="color:#FF4949;" align="center" @click="deleteCommand(scope)"></el-button>
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
                          <!-- <el-slider v-if="commands[scope.$index].mode == 'heat'" v-model="commands[scope.$index].operation_2" :min='0' :max='32' :step="1" >
                          </el-slider>
                          <el-slider v-if="commands[scope.$index].mode == 'auto'" v-model="commands[scope.$index].operation_3" :min='0' :max='32' :step="1">
                          </el-slider> -->
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
                          v-for="(item,key) in modes"
                          :key="key"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
                      <el-select @change="sourceChange(commands[scope.$index])" v-if="commands[scope.$index].devicetype == 'music'" v-model="commands[scope.$index].operation_2" placeholder="">
                        <el-option
                          v-for="(item,key) in musicSource"
                          :key="key"
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
                          v-for="(item,key) in grades"
                          :key="key"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
                      <el-select @change="albumChange(commands[scope.$index])" v-if="commands[scope.$index].devicetype == 'music'" v-model="commands[scope.$index].operation_3" placeholder="">
                        <el-option
                          v-for="(item,key) in commands[scope.$index].deviceProperty.albumlist"
                          :key="key"
                          :label="item.albumName"
                          :value="item.albumNo">
                        </el-option>
                      </el-select>
                    </template>
                </el-table-column>
                <el-table-column  width="200"  align="center">
                    <template slot-scope="scope">
                      <el-select v-if="commands[scope.$index].devicetype == 'music'" v-model="commands[scope.$index].operation_4" placeholder="">
                        <el-option
                          v-for="(item,key) in commands[scope.$index].deviceProperty.songList"
                          :key="key"
                          :label="item.songName"
                          :value="item.songNo">
                        </el-option>
                      </el-select>
                    </template>
                </el-table-column>
                <el-table-column label="Address" prop="address_name" width="150" align="center">
                </el-table-column>
                <el-table-column label="Floor" prop="floor_name" width="150" align="center">
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
              </el-col>
            </el-row>
            
            
            
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
.schedule-add .vc-container {
  z-index: 9999;
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
      marcoData: [],
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
      dataType: "1",
      dataTypeList: [
        {
          value: "1",
          label: "Device"
        },
        {
          value: "2",
          label: "Marco"
        }
      ],
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
          value: '3',
          label: "Low"
        },
        {
          value: '2',
          label: "Medial"
        },
        {
          value: '1',
          label: "High"
        },
        {
          value: '0',
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
      this.schedule.time_1 = _g.formatDate(this.schedule.time_1);
    },
    sourceChange(command) {
      command.deviceProperty.source = command.operation_2;
      command.operation_3 = "";
      command.operation_4 = "";
      musicApi.source_change(command.deviceProperty.source, command);
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
        musicApi.readSong(command, command.deviceProperty);
      }
    },
    albumChange(command) {
      command.deviceProperty.songList = [];
      command.operation_4 = "";
      for (var song of command.deviceProperty.songListAll) {
        if (song.albumNo == command.operation_3) {
          command.deviceProperty.songList.push(song);
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
    //
    addCommand(device) {
      for (var index in this.commands) {
        if (this.commands[index].id == device.id) {
          this.commands.splice(index, 1);
        }
      }
      if (device.on_off == "1" || device.on_off == "on") {
        device.on_off = true;
      }
      if (device.on_off == "0" || device.on_off == "off") {
        device.on_off = false;
      }
      if (device.devicetype == "ac") {
        device.operation_1 = device.operation_1
          ? parseInt(device.operation_1)
          : 0;
        device.mode = device.mode ? device.mode : "auto";
        device.grade = device.grade ? parseInt(device.grade) : 0;
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
        device.deviceProperty = {};
        if (musicObj) {
          device.deviceProperty = musicObj;
        } else {
          device.deviceProperty.source = device.operation_2;
          device.deviceProperty.albumlist = [];
          device.deviceProperty.songList = [];
          device.deviceProperty.songListAll = [];
          musicApi.readSong(device, device.deviceProperty);
        }
      }
      if (device.devicetype == "light") {
        device.mode = device.mode ? device.mode : 0;
        device.mode = parseInt(device.mode);
      }
      this.commands.push(device);
      this.devicesId.push(device.id);
    },
    //获取被选中的数据
    selectDevice(device) {
      if (this.devicesId.indexOf(device.id) != -1) {
        // this.$confirm("Are you sure to delete the selected data?", "Tips", {
        //   confirmButtonText: "Yse",
        //   cancelButtonText: "No",
        //   type: "warning"
        // })
        //   .then(() => {
        //     this.addCommand(device);
        //   })
        //   .catch(() => {});
        _g.toastMsg("warning", "Schedule list already exists for the device");
        return;
      }
      this.addCommand(device);
    },
    selectMarco(marco) {
      this.isLoading = true;
      this.apiGet("admin/macro/" + marco.id, {}).then(res => {
        this.handelResponse(res, data => {
          for (var command of data) {
            command.operation_1 = command.status_1;
            command.operation_2 = command.status_2;
            command.operation_3 = command.status_3;
            command.operation_4 = command.status_4;
            command.operation_5 = command.status_5;
            this.addCommand(command);
          }
          this.isLoading = false;
        });
      });
    },
    deleteCommand(scope) {
      for (var index in this.commands) {
        if (this.commands[index].id == scope.row.id) {
          this.commands.splice(index, 1);
        }
      }
    },
    save() {
      if (this.schedule.schedule == "" || this.schedule.type == "") {
        _g.toastMsg("error", "Please enter the name, type, time of schedule");
        return;
      }
      if (this.schedule.time_1 == "" && this.schedule.time_ == "") {
        _g.toastMsg("error", "Please enter the name, type, time of schedule");
        return;
      }
      this.isLoading = true;
      this.schedule.mon = "0";
      this.schedule.tues = "0";
      this.schedule.wed = "0";
      this.schedule.thur = "0";
      this.schedule.fri = "0";
      this.schedule.sat = "0";
      this.schedule.sun = "0";
      for (var week of this.schedule.week) {
        if (week == "mon") {
          this.schedule.mon = "1";
        }
        if (week == "tues") {
          this.schedule.tues = "1";
        }
        if (week == "wed") {
          this.schedule.wed = "1";
        }
        if (week == "thur") {
          this.schedule.thur = "1";
        }
        if (week == "fri") {
          this.schedule.fri = "1";
        }
        if (week == "sat") {
          this.schedule.sat = "1";
        }
        if (week == "sun") {
          this.schedule.sun = "1";
        }
      }
      var devices = [];
      for (var command of this.commands) {
        var device = {};
        device.id = command.id;
        device.on_off = command.on_off ? "1" : "0";
        device.mode = command.mode;
        device.grade = command.grade;
        device.operation_1 = command.operation_1;
        device.operation_2 = command.operation_2;
        device.operation_3 = command.operation_3;
        device.operation_4 = command.operation_4;
        device.operation_5 = command.operation_5;
        devices.push(device);
      }
      this.schedule.devices = devices;

      const data = this.schedule;
      // console.log(data);
      this.apiPost("admin/schedule", data).then(res => {
        this.handelResponse(res, data => {
          _g.toastMsg("success", data);
          this.goback();
        });
        this.isLoading = false;
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
    getMarco() {
      const data = {
        params: {
          keywords: this.keywords,
          page: this.currentPage,
          limit: this.limit
        }
      };
      this.apiGet("admin/macro", data).then(res => {
        this.handelResponse(res, data => {
          console.log(data)
          this.marcoData = data;
        });
      });
    },
    search_command() {
      this.commands = [];
      this.devicesId = [];
      var vm = this;
      this.apiGet("admin/schedule/" + this.schedule.id, {}).then(res => {
        this.handelResponse(res, data => {
          for (var command of data) {
            vm.devicesId.push(parseInt(command.id));
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
              command.deviceProperty = {};
              if (musicObj) {
                command.deviceProperty = musicObj;
              } else {
                command.deviceProperty.source = command.status_2;
                command.deviceProperty.albumlist = [];
                command.deviceProperty.songList = [];
                command.deviceProperty.songListAll = [];
                musicApi.readSong(command, command.deviceProperty);
              }
            }
            if (command.devicetype == "light") {
              command.mode = parseInt(command.mode);
            }
            vm.commands.push(command);
          }
        });
        vm.isLoading = false;
      });
    }
  },
  created() {
    console.log("schedule-add");
    this.init();
  },
  mounted() {
    this.getMarco();
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
    schedule() {
      return this.data;
    },
    allAddress() {
      var allAddress = [];
      for (var address of this.$store.state.address) {
        var addressObj = {
          value: address.id,
          label: address.address,
          children: []
        };
        for (var floor of this.$store.state.floor) {
          if (floor.address == address.id) {
            var floorObj = {
              value: floor.id,
              label: "floor " + floor.floor,
              children: []
            };
            for (var room of this.$store.state.room) {
              if (room.floor == floor.id && room.address == address.id) {
                var roomObj = {
                  value: room.id,
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