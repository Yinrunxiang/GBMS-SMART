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
                <el-table-column label="Device" prop="device" width="150">
                </el-table-column>
                <el-table-column label="Type" prop="devicetype" width="150">
                </el-table-column>
                <el-table-column label="Open" width="150" >
                    <template scope="scope">
                        <el-switch v-model="selectData[scope.$index].status_1" @change="switch_change(selectData[scope.$index].status_1)" >
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column label="Address" prop="address" width="150">
                </el-table-column>
                <el-table-column label="Floor" prop="floor" width="150">
                </el-table-column>
                <el-table-column label="Room" prop="room_name" width="150">
                </el-table-column>
                <el-table-column label="status_1" prop="status_1" width="150">
                </el-table-column>
                <el-table-column label="status_2" prop="status_2" width="150">
                </el-table-column>
                <el-table-column label="status_3" prop="status_3" width="150">
                </el-table-column>
                <el-table-column label="status_4" prop="status_4" width="150">
                </el-table-column>
                <el-table-column label="status_5" prop="status_5" width="150">
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


<script>
import setting from "./setting";
import http from "../../../assets/js/http.js";
import list from "../../../assets/js/list.js";

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
      address : "",
      floor : "",
      room : "",
    }
  },
  methods: {
    goback(bool) {
      this.deviceSetting = bool;
    },
    closeDeviceSetting() {
      this.deviceSetting = false;
    },
    rowDblclick(row) {
      this.settingDevice = row
      this.deviceSetting = true
    },
    switch_change(row){
     console.log(row)
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
          device.status_1 = device.on_off
          device.status_2 = device.mode
          device.status_3 = device.grade
          device.status_4 = device.operation_1
          device.status_5 = device.operation_2
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
    setting
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
    },
    devices: {
      handler: function(val, oldVal) {
        this.init();
      },
      deep: true
    }
  },
  mixins: [http, list]
};
</script>