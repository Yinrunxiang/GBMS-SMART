<template>
    <div>
        <div v-show="!setting" class="p-20">
            <div class="m-b-20 ovf-hd">
                <div class="fl">
                <el-cascader :options="allAddress" change-on-select @change="addressChange"></el-cascader>
                </div>
                <div class="fl w-300 m-l-30">
                    <el-input placeholder="Please enter the model" v-model="keywords">
                        <el-button slot="append" icon="el-icon-search" @click="search()"></el-button>
                    </el-input>
                </div>
            </div>
            <el-table :data="tableData" style="width: 100%" @selection-change="selectItem" @row-dblclick="rowDblclick"  :height="400">
                <el-table-column type="selection" width="50">
                </el-table-column>
                <el-table-column label="Room" prop="room" width="150">
                </el-table-column>
                <el-table-column label="Room Name" prop="room_name" width="150">
                </el-table-column>
                 <el-table-column label="Address" prop="address_name" width="150">
                </el-table-column>
                <el-table-column label="Floor" prop="floor_name" width="150">
                </el-table-column>
                <el-table-column label="Comment" prop="comment">
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
        <div v-if="setting">
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
      room: {},
      options_address: "",
      options_floor: "",
      options_room: ""
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
        image: "",
        comment: "",
        lat: "",
        lng: "",
        width: "",
        height: "",
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
            selections: this.multipleSelection
          };
          this.apiPost("admin/room/delete", data).then(res => {
            this.handelResponse(res, data => {
              _g.addDeviceProperty(data.device);
              vm.$store.dispatch("setRoom", data.room);
              vm.$store.dispatch("setDevices", data.device);
              _g.toastMsg("success", data.result);
            });
          });
        })
        .catch(() => {
          // catch error
        });
    },
    addressChange(value) {
      var len = value.length;
      switch (len) {
        case 1:
          this.options_address = value[0];
          this.options_floor = "";
          this.options_room = "";
          break;
        case 2:
          this.options_address = value[0];
          this.options_floor = value[1];
          this.options_room = "";
          break;
        case 3:
          this.options_address = value[0];
          this.options_floor = value[1];
          this.options_room = value[2];
          break;
      }
      this.search();
    },
    search() {
      router.push({
        path: this.$route.path,
        query: {
          options_address: this.options_address,
          options_floor: this.options_floor,
          options_room: this.options_room,
          keywords: this.keywords,
          page: 1
        }
      });
    },
    //换页事件
    handleCurrentChange(page) {
      router.push({
        path: this.$route.path,
        query: {
          options_address: this.options_address,
          options_floor: this.options_address,
          options_room: this.options_address,
          keywords: this.keywords,
          page: page
        }
      });
    },
    getCurrentPage() {
      let data = this.$route.query;
      if (data) {
        if (data.page) {
          this.currentPage = parseInt(data.page);
        } else {
          this.currentPage = 1;
        }
      }
    },
    //获取关键值
    getKeywords() {
      let data = this.$route.query;
      if (data) {
        if (data.keywords) {
          this.keywords = data.keywords;
        } else {
          this.keywords = "";
        }
      }
    },
    getAllData() {
      var data = [];
      let query = this.$route.query;
      if (query && query.options) {
        this.options_address = query.options_address
          ? query.options_address
          : "";
        this.options_floor = query.options_floor ? query.options_floor : "";
        this.options_room = query.options_room ? query.options_room : "";
      }
      for (var room of this.rooms) {
        if (
          this.options_address == "" ||
          room.address == this.options_address
        ) {
          if (this.options_floor == "" || room.floor == this.options_floor) {
            if (this.options_room == "" || room.id == this.options_room) {
              if (this.keywords == "" || room.room_name == this.keywords) {
                data.push(room);
              }
            }
          }
        }
      }

      // var data = this.devices
      var start = this.limit * (this.currentPage - 1);
      var end = start + this.limit;
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
    this.init();
  },
  components: {
    add
  },
  computed: {
    //从vuex中获取设备数据
    rooms() {
      return this.$store.state.room;
    },
    //从vuex中获取设备数据条数
    dataCount() {
      return this.$store.state.room.length;
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
              if (
                room.floor == floor.id &&
                room.address == address.id
              ) {
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