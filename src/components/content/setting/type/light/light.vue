<template>
    <div class="p-20">
        <div class="m-b-20 ovf-hd">
            <div class="fl">
                <router-link  to="light/add">
                <el-button type="info">
                      <i class="el-icon-plus"></i>&nbsp;&nbsp;Add
                    </el-button>
                </router-link>
                    <el-button type="warning" class="" @click="deleteBtn">
                        <i class="el-icon-minus"></i>&nbsp;&nbsp;Delete
                    </el-button>
            </div>
            <div class="fl w-300 m-l-30">
                <el-input placeholder="Please enter the model" v-model="keywords">
                    <el-button slot="append" icon="search" @click="search()"></el-button>
                </el-input>
            </div>
        </div>
        <el-table :data="tableData" style="width: 100%" @selection-change="selectItem" @row-dblclick="rowDblclick">
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
      var url = "light/update";
      router.push({ path: url, query: row });
    },
    //获取被选中的数据
    selectItem(val) {
      this.multipleSelection = val;
      console.log(this.multipleSelection);
    },
    //保存状态点击事件
    setStatusBtn(status) {
      const data = {
        params: {
          selections: this.multipleSelection,
          status: status
        }
      };
      this.apiGet("device/light_breed.php?action=setStatus", data).then(res => {
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
          this.apiGet(
            "device/light_breed.php?action=delete",
            data
          ).then(res => {
            if (res[0]) {
              var light_breed = this.$store.state.light_breed;
              for (var i = 0; i < light_breed.length; i++) {
                for (var selection of this.multipleSelection) {
                  if (light_breed[i].breed == selection.breed) {
                    light_breed.splice(i, 1);
                  }
                }
              }
              this.$store.dispatch("setLightBreed", light_breed);
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
        for (var light_breed of this.light_breeds) {
          if (light_breed.breed == this.keywords) {
            data.push(light_breed);
          }
        }
      } else {
        data = this.light_breeds;
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
      this.getAllData()
    }
  },
  created() {
    console.log("ac");
    this.init();
  },
  components: {},
  computed: {
    //从vuex中获取设备数据
    light_breeds() {
      return this.$store.state.light_breed;
    },
    //从vuex中获取设备数据条数
    dataCount() {
      return this.$store.state.light_breed.length;
    }
  },
  watch: {
    $route(to, from) {
      this.init();
    },
    light_breeds: {
      handler: function(val, oldVal) {
        this.init();
      },
      deep: true
    }
  },
  mixins: [http,list]
};
</script>