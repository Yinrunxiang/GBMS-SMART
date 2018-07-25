<template>
    <div class="p-20">
        <el-row :gutter="20" class="ovf-hd">
          <el-col class="m-b-10" :xs = "24" :md = "{span: 5}">
            <el-row>
              <el-col :span = "10">
                  <router-link  to="led/add">
                      <el-button  class="w-100p" type="primary">
                        <i class="el-icon-plus"></i>&nbsp;&nbsp;Add
                      </el-button>
                  </router-link>
                </el-col>
                <el-col :span = "10" :offset = "4">
                  <el-button  class="w-100p" type="warning"  @click="deleteBtn">
                  <i class="el-icon-minus"></i>&nbsp;&nbsp;Delete
                  </el-button>
                </el-col>
              </el-row>
            </el-col>
            <el-col class="m-b-10" :xs = "24" :md = "{span: 5,offset:1}">
              <el-input class="w-100p" placeholder="Please enter the model" v-model="keywords">
                  <el-button slot="append" icon="el-icon-search" @click="search()"></el-button>
              </el-input>
          </el-col>
        </el-row>
        <el-table :data="tableData" style="width: 100%" @selection-change="selectItem" @row-dblclick="rowDblclick" :height="400">
            <el-table-column type="selection" width="50">
            </el-table-column>
            <el-table-column label="Breed(W)" prop="breed" width="150">
            </el-table-column>
            <el-table-column label="Watts" prop="watts" width="150">
            </el-table-column>
            <el-table-column label="Run Time(h)" prop="run_time" width="200">
            </el-table-column>
            <el-table-column label="Status" prop="status">
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
</template>

<script>
import http from "../../../../../assets/js/http";
import list from "../../../../../assets/js/list";
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
      limit: 15
    };
  },
  methods: {
    rowDblclick(row) {
      var url = "led/update";
      router.push({ path: url, query: row });
    },
    //获取被选中的数据
    selectItem(val) {
      this.multipleSelection = val;
      // console.log(this.multipleSelection);
    },
    //保存状态点击事件
    setStatusBtn(status) {
      const data = {
        params: {
          selections: this.multipleSelection,
          status: status
        }
      };
      this.apiGet("device/led_breed.php?action=setStatus", data).then(res => {
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
          var ids  = []
          for(var item of this.multipleSelection){
            ids.push(item.id)
          }
          const data = {
            ids: ids
          };
          this.apiPost("admin/led_breed/delete", data).then(res => {
            this.handelResponse(res, data => {
                var led_breed = this.$store.state.led_breed;
                for (var i = 0; i < led_breed.length; i++) {
                  for (var selection of this.multipleSelection) {
                    if (led_breed[i].breed == selection.breed) {
                      led_breed.splice(i, 1);
                    }
                  }
                }
                this.$store.dispatch("setLedBreed", led_breed);
                _g.toastMsg("success", data);
            });
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
        for (var led_breed of this.led_breeds) {
          if (led_breed.breed == this.keywords) {
            data.push(led_breed);
          }
        }
      } else {
        data = this.led_breeds;
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
    this.init();
  },
  components: {},
  computed: {
    //从vuex中获取设备数据
    led_breeds() {
      // console.log(this.$store.state.led_breed);
      return this.$store.state.led_breed;
    },
    //从vuex中获取设备数据条数
    dataCount() {
      return this.$store.state.led_breed.length;
    }
  },
  watch: {
    $route(to, from) {
      this.init();
    },
    led_breeds: {
      handler: function(val, oldVal) {
        this.init();
      },
      deep: true
    }
  },
  mixins: [http, list]
};
</script>