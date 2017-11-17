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
                <el-table-column label="Floor Name" prop="floor" width="150">
                </el-table-column>
                <el-table-column label="Room Number" prop="room_num" width="150">
                </el-table-column>
                <el-table-column label="Address" prop="address">
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
        <div v-show="setting">
            <add :add="add" :floor="floor" @goback="goback"></add>
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
      limit: 10,
      add: true,
      setting: false,
      floor: {}
    };
  },
  methods: {
    goback(bool) {
      this.setting = bool;
    },
    addressSetting() {
      this.add = true;
      this.setting = true;
      var floor = {
        floor: "",
        room_num: "",
        address: "",
        status: "enabled"
      };
      this.floor = floor;
    },
    rowDblclick(row) {
      this.add = false;
      this.setting = true;
      this.floor = row;
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
      this.apiGet("device/floor.php?action=setStatus", data).then(res => {
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
          this.apiGet("device/floor.php?action=delete", data).then(res => {
            if (res[0]) {
              var floor = this.$store.state.floor;
              for (var i = 0; i < floor.length; i++) {
                for (var selection of this.multipleSelection) {
                  if (floor[i].floor == selection.floor) {
                    floor.splice(i, 1);
                  }
                }
              }
              this.$store.dispatch("setFloor", floor);
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
        for (var floor of this.floors) {
          if (floor.floor == this.keywords) {
            data.push(floor);
          }
        }
      } else {
        data = this.floors;
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
    // getFloor() {
    //   const data = {
    //     params: {
    //       action: "search"
    //     }
    //   };
    //   this.apiGet("device/floor.php", data).then(res => {
    //     this.$store.dispatch("setFloor", res);
    //   });
    // },
  },
  created() {
    console.log("floor");
    // this.getFloor()

    this.init();
  },
  mounted() {
    
  },
  components: {
    add
  },
  computed: {
    //从vuex中获取设备数据
    floors() {
      return this.$store.state.floor;
    },
    //从vuex中获取设备数据条数
    dataCount() {
      return this.$store.state.floor.length;
    }
  },
  watch: {
    $route(to, from) {
      this.init();
    },
    floors: {
      handler: function(val, oldVal) {
        this.init();
      },
      deep: true
    }
  },
  mixins: [http,list]
};
</script>