<template>
    <div>
        <div class="p-20">
            <div class="m-b-20 ovf-hd">
                <div class="fl" >
                  <el-cascader :options="allAddress" change-on-select @change="addressChange" style="width:230px;"></el-cascader>
                </div>
                <div class="fl w-300" style="margin-left:23px;">
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
            <el-table :data="selectData" :height="400" style="width: 75%;display: inline-block" class=" m-l-20">
                <el-table-column type="selection" width="50">
                </el-table-column>
                <el-table-column label="Device" prop="device" width="120" align="center">
                </el-table-column>
                <el-table-column label="Type" prop="devicetype" width="120" align="center">
                </el-table-column>
                <el-table-column label="Switch" prop="on_off" width="120"  align="center">
                    <template scope="scope">
                        <el-switch  v-model="selectData[scope.$index].on_off" @change="switch_change(selectData[scope.$index])" >
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column label="Slider" prop="device" width="200" align="center" >
                    <template scope="scope">
                        <div v-if="selectData[scope.$index].devicetype == 'ac'">
                          <el-slider v-if="selectData[scope.$index].mode == 'cool' || selectData[scope.$index].mode == 'fan'" v-model="selectData[scope.$index].operation_1" :min='0' :max='32' :step="1">
                          </el-slider>
                          <el-slider v-if="selectData[scope.$index].mode == 'heat'" v-model="selectData[scope.$index].operation_2" :min='0' :max='32' :step="1" >
                          </el-slider>
                          <el-slider v-if="selectData[scope.$index].mode == 'auto'" v-model="selectData[scope.$index].operation_3" :min='0' :max='32' :step="1">
                          </el-slider>
                        </div>
                        <div v-if="selectData[scope.$index].devicetype == 'light'">
                          <el-slider v-model="selectData[scope.$index].mode" :min='0' :max='100' :step="1">
                          </el-slider>
                        </div>
                        <div v-if="selectData[scope.$index].devicetype == 'led'">
                          <colorPicker  v-model="selectData[scope.$index].mode" v-on:accept="headleChangeColor"></colorPicker>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="Mode" width="120" prop="mode" align="center" >
                    <template scope="scope">
                      <el-select v-if="selectData[scope.$index].devicetype == 'ac'" v-model="selectData[scope.$index].mode" placeholder="">
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
                      <el-select v-if="selectData[scope.$index].devicetype == 'ac'" v-model="selectData[scope.$index].grade" placeholder="">
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
                <div>
                    <!-- <el-button size="small" type="success" @click="setStatusBtn('enabled')">Enabled</el-button>
                    <el-button size="small" type="warning" @click="setStatusBtn('disabled')">Disabled</el-button>
                    <el-button size="small" type="danger" @click="deleteBtn()">Delete</el-button> -->
                </div>
                <div class="block pages">
                    <el-pagination @current-change="handleCurrentChange" layout="prev, pager, next" :page-size="limit" :current-page="currentPage" :total="dataCount">
                    </el-pagination>
                </div>
            </div>
        </div>
        <setting v-if='deviceSetting'  :open = "deviceSetting" :device = "settingDevice" @change="closeDeviceSetting"></setting>
    </div>
</template>

<style>
.vc-container{
  z-index: 9999;
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
      selectData: [],
      selectDataId: [],
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
          value: "auto",
          label: "Auto"
        }
      ]
    };
  },
  methods: {
    goback(bool) {
      this.deviceSetting = bool;
    },
    closeDeviceSetting() {
      this.deviceSetting = false;
    },
    headleChangeColor(){},
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
        if (this.selectDataId.indexOf(device.id) == -1) {
          if (device.devicetype == "ac") {
            device.operation_1 = parseInt(device.operation_1);
            device.operation_2 = parseInt(device.operation_2);
            device.operation_3 = parseInt(device.operation_3);
          }
          if (device.devicetype == "light") {
            device.mode = parseInt(device.mode);
          }
          this.selectData.push(device);
          this.selectDataId.push(device.id);
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
    console.log("doctor");
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