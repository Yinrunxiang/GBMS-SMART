<template>
    <div>
        <div v-show="!setting" class="p-20">
            <div class="m-b-20 ovf-hd">
                <!-- <div class="fl">
                    <el-button type="info" class="" @click="addressSetting">
                        <i class="el-icon-plus"></i>&nbsp;&nbsp;Add
                    </el-button>
                    <el-button type="warning" class="" @click="deleteBtn">
                        <i class="el-icon-minus"></i>&nbsp;&nbsp;Delete
                    </el-button>
                </div> -->
                <div class="fl w-300 m-l-30">
                    <el-input placeholder="Please enter the model" v-model="keywords">
                        <el-button slot="append" icon="search" @click="search()"></el-button>
                    </el-input>
                </div>
            </div>
            <el-table :data="tableData" style="width: 100%" @selection-change="selectItem" @row-dblclick="rowDblclick">
                <el-table-column type="selection" width="50">
                </el-table-column>
                <el-table-column label="Room" prop="room" width="150">
                </el-table-column>
                <el-table-column label="Room Name" prop="room_name" width="150">
                </el-table-column>
                <el-table-column label="Floor" prop="floor" width="150">
                </el-table-column>
                <el-table-column label="Address" prop="address">
                </el-table-column>
            </el-table>
            <div class="pos-rel p-t-20">
                <div>
                </div>
                <div class="block pages">
                    <el-pagination @current-change="handleCurrentChange" layout="prev, pager, next" :page-size="limit" :current-page="currentPage" :total="dataCount">
                    </el-pagination>
                </div>
            </div>
        </div>
        <div v-show="setting">
            <add :add="add" :room="room" @goback="goback"></add>
        </div>
    </div>
</template>

<script>
import http from "../../../../assets/js/http";
import list from "../../../../assets/js/list";
import add from "./add";
export default {
  //  currentPage        页码
  //  keywords           关键字
  //  multipleSelection  被选中的数据
  //  limit              每页最大行数
  data() {
    return {
      tableData: [],
      // dataCount: null,
      currentPage: null,
      keywords: "",
      multipleSelection: [],
      limit: 15,
      add: true,
      setting: false,
      room: {}
    };
  },
  methods: {
    goback(bool) {
      this.setting = bool;
    },
    addressSetting() {
      this.add = true;
      this.setting = true;
      var room = {
        room: "",
        room_num: "",
        floor: "",
        address: "",
        status: "enabled"
      };
      this.room = room;
    },
    rowDblclick(row) {
      this.add = false;
      this.setting = true;
      this.room = row;
    },
    //获取被选中的数据
    selectItem(val) {
      this.multipleSelection = val;
    },
    //保存状态点击事件
    setStatusBtn(status) {
      const data = {
        params: {
          selections: this.multipleSelection,
          status: status
        }
      };
      this.apiGet("device/room.php?action=setStatus", data).then(res => {
        if (res[0]) {
          for (var selection of this.multipleSelection) {
            selection.status = status;
          }
          _g.toastMsg("success", res[1]);
        } else {
          _g.toastMsg("error", res[1]);
        }
      });
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
          const data = {
            params: {
              selections: this.multipleSelection
            }
          };
          this.apiGet("device/room.php?action=delete", data).then(res => {
            if (res[0]) {
              var room = vm.$store.state.room;
              for (var i = 0; i < room.length; i++) {
                for (var selection of vm.multipleSelection) {
                  if (room[i].room == selection.room) {
                    room.splice(i, 1);
                  }
                }
              }
              this.$store.dispatch("setRoom", room);
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
    getAllData() {
      // var pages = Math.ceil(this.dataCount/this.limit)
      var data = [];
      //   var devices = [];
      //   devices = devcice.cancat(this.devices);
      if (this.keywords != "") {
        for (var room of this.rooms) {
          if (room.room_name == this.keywords) {
            data.push(room);
          }
        }
      } else {
        data = this.rooms;
      }

      // var data = this.devices
      var start = this.limit * (this.currentPage - 1);
      var end = start + this.limit - 1;
      this.tableData = data.slice(start, end);
    },
    //初始化时统一加载
    init() {
      this.getKeywords();
      this.getCurrentPage();
      this.getAllData();
    }
  },
  created() {
    console.log("room");
    // this.getRoom()
    this.init();
  },
  components: {
    add
  },
  // watch: {
  //   tableData: {
  //     handler: function(val, oldVal) {
  //       this.countryArr();
  //     },
  //     deep: true
  //   }
  // },
  computed: {
    //从vuex中获取设备数据
    rooms() {
      return this.$store.state.room;
    },
    //从vuex中获取设备数据条数
    dataCount() {
      return this.$store.state.room.length;
    }
  },
  watch: {
    $route(to, from) {
      this.init();
    },
    rooms: {
      handler: function(val, oldVal) {
        this.init();
      },
      deep: true
    }
  },
  mixins: [http, list]
};
</script>