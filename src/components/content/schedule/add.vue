<template>
    <div>
        <div class="p-20 schedule-add">
          <el-row class="m-b-10">
                   <el-input  class="fl w-230" placeholder="Please enter the schedule" v-model="schedule.schedule">
                        <template slot="prepend">Schedule</template>
                    </el-input>
                <div class="fl " style="margin-left:23px;">
                   <el-select class=" w-230" v-model="schedule.type" placeholder="">
                        <el-option
                          v-for="item in timeTypeArr"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
                </div>
                <div v-if="schedule.type == 'week'" class="fl week-select-div" style="margin-left:23px;">
                        <el-select class="week-select "
                          v-model="schedule.week"
                          multiple
                          placeholder="Please choose">
                          <el-option
                            v-for="item in weekArr"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                          </el-option>
                        </el-select>
                </div>
                <div  v-if="schedule.type == 'once'" class="fl" style="margin-left:23px;">
                   <el-date-picker class=" w-230"
                    v-model="schedule.time_1"
                    type="datetime"
                    @change = "dateChange()"
                    placeholder="Please choose">
                  </el-date-picker>
                </div>
                <div  v-if="schedule.type == 'day' || schedule.type == 'week'" class="fl" style="margin-left:23px;">
                   <el-time-select
                    v-model="schedule.time_2"
                    :picker-options="{
                      start: '00:00',
                      step: '00:01',
                      end: '23:59'
                    }"
                    placeholder="Please choose">
                  </el-time-select>
                </div>
            </el-row>
            <div class="m-b-10 ovf-hd">
                <div class="fl" >
                  <el-cascader :options="allAddress" change-on-select @change="addressChange" style="width:230px;"></el-cascader>
                </div>
                <div class="fl w-230" style="margin-left:23px;">
                    <el-input placeholder="Please enter the model" v-model="keywords">
                        <el-button slot="append" icon="search" @click="search()"></el-button>
                    </el-input>
                </div>
            </div>
            <el-table :data="tableData" :height="400" style="width: 20%;display: inline-block"  @selection-change="selectItem">
                <el-table-column type="selection" width="50">
                </el-table-column>
                <el-table-column label="Device" prop="device">
                </el-table-column>
            </el-table>
            <el-table :data="schedule.devices" :height="400" style="width: 75%;display: inline-block" class=" m-l-20">
                <el-table-column type="selection" width="50">
                </el-table-column>
                <el-table-column label="Device" prop="device" width="120" align="center">
                </el-table-column>
                <el-table-column label="Type" prop="devicetype" width="120" align="center">
                </el-table-column>
                <el-table-column label="Switch" prop="on_off" width="120"  align="center">
                    <template scope="scope">
                        <el-switch  v-model="schedule.devices[scope.$index].on_off" @change="switch_change(schedule.devices[scope.$index])" >
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column label="Slider" prop="device" width="200" align="center" >
                    <template scope="scope">
                        <div v-if="schedule.devices[scope.$index].devicetype == 'ac'">
                          <el-slider v-if="schedule.devices[scope.$index].mode == 'cool' || schedule.devices[scope.$index].mode == 'fan'" v-model="schedule.devices[scope.$index].operation_1" :min='0' :max='32' :step="1">
                          </el-slider>
                          <el-slider v-if="schedule.devices[scope.$index].mode == 'heat'" v-model="schedule.devices[scope.$index].operation_2" :min='0' :max='32' :step="1" >
                          </el-slider>
                          <el-slider v-if="schedule.devices[scope.$index].mode == 'auto'" v-model="schedule.devices[scope.$index].operation_3" :min='0' :max='32' :step="1">
                          </el-slider>
                        </div>
                        <div v-if="schedule.devices[scope.$index].devicetype == 'light'">
                          <el-slider v-model="schedule.devices[scope.$index].mode" :min='0' :max='100' :step="1">
                          </el-slider>
                        </div>
                        <div v-if="schedule.devices[scope.$index].devicetype == 'led'">
                          <colorPicker  v-model="schedule.devices[scope.$index].mode" v-on:accept="headleChangeColor"></colorPicker>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="Mode" width="120" prop="mode" align="center" >
                    <template scope="scope">
                      <el-select v-if="schedule.devices[scope.$index].devicetype == 'ac'" v-model="schedule.devices[scope.$index].mode" placeholder="">
                        <el-option
                          v-for="item in modes"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
                    </template>
                </el-table-column>
                <el-table-column label="Grade"  prop="grade" width="120"  align="center">
                    <template scope="scope">
                      <el-select v-if="schedule.devices[scope.$index].devicetype == 'ac'" v-model="schedule.devices[scope.$index].grade" placeholder="">
                        <el-option
                          v-for="item in grades"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
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
                <el-table-column label="status_1" prop="operation_1" width="150" align="center">
                </el-table-column>
                <el-table-column label="status_2" prop="operation_2" width="150" align="center">
                </el-table-column>
                <el-table-column label="status_3" prop="operation_3" width="150" align="center">
                </el-table-column>
                <el-table-column label="status_4" prop="operation_4" width="150" align="center">
                </el-table-column>
                <el-table-column label="status_5" prop="operation_5" width="150" align="center">
                </el-table-column>
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
        <setting v-if='deviceSetting'  :open = "deviceSetting" :device = "settingDevice" @change="closeDeviceSetting"></setting>
    </div>
</template>

<style>
.schedule-add .vc-container {
  z-index: 9999;
}
.schedule-add .week-select-div{
  position: relative;
  width:230px;
  height:40px;
}
.schedule-add .week-select-div .week-select{
  position: absolute;
  top: 0;
  left:0;
}
</style>

<script>
import setting from "./setting";
import http from "../../../assets/js/http.js";
import list from "../../../assets/js/list.js";
import colorPicker from "vue-color-picker";
export default {
  //  currentPage        页码
  //  keywords           关键字
  //  multipleSelection  被选中的数据
  //  limit              每页最大行数
  data() {
    return {
      tableData: [],
      devicesId: [],
      dataCount: null,
      currentPage: null,
      keywords: "",
      multipleSelection: [],
      limit: 15,
      add: true,
      deviceSetting: false,
      settingDevice: {},
      address: "",
      floor: "",
      room: "",
      time:"",
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
      ]
    };
  },
  methods: {
    dateChange(){
      console.log(_g.formatDate(this.schedule.time_1))
    },
    goback() {
      this.$emit('goback',false)
    },
    closeDeviceSetting() {
      this.deviceSetting = false;
    },
    headleChangeColor() {},
    rowDblclick(row) {
      this.settingDevice = row;
      this.deviceSetting = true;
    },
    switch_change(row) {
      console.log(row);
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
          if (device.devicetype == "ac") {
            device.operation_1 = parseInt(device.operation_1);
            device.operation_2 = parseInt(device.operation_2);
            device.operation_3 = parseInt(device.operation_3);
          }
          if (device.devicetype == "light") {
            device.mode = parseInt(device.mode);
          }
          this.schedule.devices.push(device);
          this.devicesId.push(device.id);
        }
      }
    },
    //删除按钮事件
    deleteBtn() {
      var vm = this;
      this.$confirm("Are you sure to delete the selected data?", "Tips", {
        confirmButtonText: "Yse",
        cancelButtonText: "No",
        type: "warning"
      })
        .then(() => {
          var ids = [];
          for (var selection of vm.multipleSelection) {
            ids.push(selection.Id);
          }
          const data = {
            params: {
              ids: ids
            }
          };
          this.apiPost("admin/record/deletes", data).then(res => {
            if (res[0]) {
              this.getAllData();
              _g.toastMsg("success", res[1]);
            } else {
              _g.toastMsg("error", res[1]);
            }
          });
        })
        .catch(() => {
          // catch error
        });
    },
    save(){
      for(var week of this.schedule.week){
        if(week == "mon"){
          this.schedule.mon = '1'
        }else{
          this.schedule.mon = '0'
        }
        if(week == "tues"){
          this.schedule.tues = '1'
        }else{
          this.schedule.tues = '0'
        }
        if(week == "wed"){
          this.schedule.wed = '1'
        }else{
          this.schedule.wed = '0'
        }
        if(week == "thur"){
          this.schedule.thur = '1'
        }else{
          this.schedule.thur = '0'
        }
        if(week == "fri"){
          this.schedule.fri = '1'
        }else{
          this.schedule.fri = '0'
        }
        if(week == "sat"){
          this.schedule.sat = '1'
        }else{
          this.schedule.sat = '0'
        }
        if(week == "sun"){
          this.schedule.sun = '1'
        }else{
          this.schedule.sun = '0'
        }
      }
      const data = {
        params: this.schedule
      };
      console.log(data)
      this.apiGet("device/schedule.php?action=insert_command", data).then(res => {
          if (res[0]) {
            _g.toastMsg("success", res[1]);
            vm.goback();
          } else {
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
      this.tableData = data;
    },
    //初始化时统一加载
    init() {
      this.getKeywords();
      this.getCurrentPage();
      this.getAllDevices();
    }
  },
  created() {
    console.log("schedule-add");
    this.init();
  },
  components: {
    setting,
    colorPicker
  },
  computed: {
    devices() {
      return this.$store.state.devices;
    },
    schedule(){
      return this.data
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
  props:["data"],
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