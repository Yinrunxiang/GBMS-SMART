<template>
    <div  v-loading="isLoading">
        <div v-show="!setting" class="p-20">
            <el-row class="ovf-hd">
                <el-col class="m-b-10" :xs = "24" :md = "{span: 5}">
                  <el-row>
                    <el-col :span = "10">
                        <el-button type="primary" class="w-100p" @click="addressSetting">
                            <i class="el-icon-plus"></i>&nbsp;&nbsp;Add
                        </el-button>
                      </el-col>
                      <el-col :span = "10" :offset = "4">
                        <el-button type="warning"  class="w-100p" @click="deleteBtn">
                            <i class="el-icon-minus"></i>&nbsp;&nbsp;Delete
                        </el-button>
                      </el-col>
                    </el-row>
                </el-col>
                <el-col class="m-b-10" :xs = "24" :md = "{span: 5,offset:1}">
                    <el-input class="w-100p" placeholder="Please enter the model name" v-model="keywords">
                        <el-button slot="append" icon="el-icon-search" @click="search()"></el-button>
                    </el-input>
                </el-col>
                
            </el-row>
            <el-table :data="tableData" style="width: 100%" :height="400" @selection-change="selectItem" @row-dblclick="rowDblclick" >
                <el-table-column type="selection" width="50">
                </el-table-column>
               <el-table-column label="Macro" prop="macro" width="150">
                </el-table-column>
                <el-table-column label="Comment" prop="comment"  width="200">
                </el-table-column>
                <el-table-column>
                  <template slot-scope="scope">
                    <el-button align="left"  type="primary" @click="run(scope.row)">Run</el-button>
                  </template>
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
import udpArr from "../devices/udpArr";
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
      isLoading: true
    };
  },
  methods: {
    //执行宏指令
    run(macro) {
      const data = macro;
      this.apiPost("admin/macro/run", data).then(res => {
        this.handelResponse(res, data => {
          udpArr.sendUdpArr(data);
        });
      });
    },
    //返回宏命令页面
    goback(bool) {
      this.init();
      this.setting = bool;
    },
    //新增或修改宏指令
    addressSetting() {
      this.add = true;
      this.setting = true;
      var data = {
        id: ""
      };
      this.selectData = data;
    },
    //双击列表行，修改宏指令
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
          this.apiPost("admin/macro/delete", data).then(res => {
            this.handelResponse(res, data => {
              this.init();
              _g.toastMsg("success", data);
            });
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
      this.apiGet("admin/macro", data).then(res => {
        this.handelResponse(res, data => {
          this.tableData = data;
          this.isLoading = false;
        });
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
    console.log("macro");
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