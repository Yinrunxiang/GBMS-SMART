<template>
    <div  v-loading="isLoading">
        <div v-show="!setting" class="p-20">
            <div class="m-b-20 ovf-hd">
                <div class="fl">
                    <el-button type="primary" class="" @click="addressSetting">
                        <i class="el-icon-plus"></i>&nbsp;&nbsp;Add
                    </el-button>
                    <el-button type="warning" class="" @click="deleteBtn">
                        <i class="el-icon-minus"></i>&nbsp;&nbsp;Delete
                    </el-button>
                </div>
                <div class="fl w-300 m-l-30">
                    <el-input placeholder="Please enter the model" v-model="keywords">
                        <el-button slot="append" icon="el-icon-search" @click="search()"></el-button>
                    </el-input>
                </div>
            </div>
            <el-table :data="tableData" style="width: 100%" :height="400" @selection-change="selectItem" @row-dblclick="rowDblclick" >
                <el-table-column type="selection" width="50">
                </el-table-column>
               <el-table-column label="Schedule" prop="schedule" width="150">
                </el-table-column>
                <el-table-column label="Type" prop="type" width="150">
                </el-table-column>
                <el-table-column  label="Time" prop="time" width="150">
                </el-table-column>
                <el-table-column label="Comment" prop="comment">
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
        <div v-if="setting">
            <add :add="add" :data="selectData" @goback="goback"></add>
        </div>
    </div>
</template>

<script>
import http from "../../../assets/js/http.js";
import list from "../../../assets/js/list.js";
import add from "./add";
export default {
  //  currentPage        页码
  //  keywords           关键字
  //  multipleSelection  被选中的数据
  //  limit              每页最大行数
  data() {
    return {
      tableData: [],
      selectData: {},
      dataCount: null,
      currentPage: null,
      keywords: "",
      multipleSelection: [],
      limit: 15,
      add: true,
      setting: false,
      selectData: {},
      isLoading: true
    };
  },
  methods: {
    goback(bool) {
      this.init();
      this.setting = bool;
    },
    addressSetting() {
      this.add = true;
      this.setting = true;
      var data = {
        schedule: "",
        type: "",
        time_1: "",
        time_2: "",
        mon: "0",
        tues: "0",
        wed: "0",
        thur: "0",
        fri: "0",
        sat: "0",
        sun: "0",
        week: []
      };
      this.selectData = data;
    },
    rowDblclick(row) {
      this.add = false;
      this.setting = true;
      this.selectData = row;
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
          var ids = [];
          for (var selection of vm.multipleSelection) {
            ids.push(selection.id);
          }
          const data = {
              ids: ids
          };
          this.apiPost("admin/schedule/delete", data).then(res => {
            this.handelResponse(res, data => {
              this.init();
              _g.toastMsg("success", data);
            })
          });
        })
        .catch(() => {
          // catch error
        });
    },
    getAllData() {
      const data = {
        params: {
          keywords: this.keywords,
          page: this.currentPage,
          limit: this.limit
        }
      };
      this.apiGet("admin/schedule", data).then(res => {
        this.handelResponse(res, data => {
          var schedules = data;
          for (var schedule of schedules) {
            schedule.week = [];
            if (schedule.mon == "1") {
              schedule.week.push("mon");
            }
            if (schedule.tues == "1") {
              schedule.week.push("tues");
            }
            if (schedule.wed == "1") {
              schedule.week.push("wed");
            }
            if (schedule.thur == "1") {
              schedule.week.push("thur");
            }
            if (schedule.fri == "1") {
              schedule.week.push("fri");
            }
            if (schedule.sat == "1") {
              schedule.week.push("sat");
            }
            if (schedule.sun == "1") {
              schedule.week.push("sun");
            }
            if(schedule.type == 'day' || schedule.type == 'week'){
              schedule.time= schedule.time_2
            }
            if(schedule.type == 'once'){
              schedule.time= schedule.time_1
            }
            // for (var command of commands) {
            //   if (schedule.id == command.schedule) {
            //     schedule.devices.push[command];
            //   }
            // }
          }
          this.tableData = schedules;
        });
        this.isLoading = false;
      });
    },
    //初始化时统一加载
    init() {
      this.getKeywords();
      this.getCurrentPage();
      this.getAllData();
    }
  },
  created() {
    console.log("schedeule");
    this.init();
  },
  components: {
    add
  },
  computed: {},
  watch: {
    $route(to, from) {
      this.init();
    }
  },
  mixins: [http, list]
};
</script>