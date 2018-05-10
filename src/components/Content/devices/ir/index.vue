<template>
    <el-col :span="24" v-loading="ir_loading">
      <div>{{device.device}}</div>
        <div class="ir" style="width:300px;height:550px;padding:0;text-align: center;">
          <div class="el-row">
                <span class="page-btn fl" @click="changePage('control')">Control</span>
                <span class="page-btn fr" @click="changePage('number')">Number</span>
            </div>
            <div class="control" v-show="showPage == 'control'">
              <div class="el-row">
                  <span class="ir-btn fl" @click="clickToSendUdp('operation_6')" @dblclick="clickToShowDialog('operation_6',true)">{{btnName('operation_6','on')}}</span>
                  <span class="ir-btn fl" @click="clickToSendUdp('operation_7')" @dblclick="clickToShowDialog('operation_7',true)">{{btnName('operation_7','off')}}</span>
                  <span class="ir-btn fr" @click="clickToSendUdp('operation_8')" @dblclick="clickToShowDialog('operation_8',true)">{{btnName('operation_8','2')}}</span>
                  <span class="ir-btn fr" @click="clickToSendUdp('operation_9')" @dblclick="clickToShowDialog('operation_9',true)">{{btnName('operation_9',true)}}</span>
              </div>
              <div class="el-row">
                  <span class="ir-btn fl" @click="clickToSendUdp('operation_10')" @dblclick="clickToShowDialog('operation_10',true)">{{btnName('operation_10','3')}}</span>
                  <span class="ir-btn fl" @click="clickToSendUdp('operation_11')" @dblclick="clickToShowDialog('operation_11',true)">{{btnName('operation_11','4')}}</span>
                  <span class="ir-btn fr" @click="clickToSendUdp('operation_12')" @dblclick="clickToShowDialog('operation_12',true)">{{btnName('operation_12','6')}}</span>
                  <span class="ir-btn fr" @click="clickToSendUdp('operation_13')" @dblclick="clickToShowDialog('operation_13',true)">{{btnName('operation_13','5')}}</span>
              </div>
              <div style="margin:20px 0">
                  <div>
                      <div class="triangle-up" @click="clickToSendUdp('operation_2')" @dblclick="clickToShowDialog('operation_2',false)"></div>
                  </div>
                  <div class="home-item">
                    <div style="display:inline-block;margin:0 auto;">
                      <div class="triangle-left" @click="clickToSendUdp('operation_3')" @dblclick="clickToShowDialog('operation_3',false)"></div>
                      <span class="home-btn" @click="clickToSendUdp('operation_1')" @dblclick="clickToShowDialog('operation_1',false)">OK
                      </span>
                      <div class="triangle-right" @click="clickToSendUdp('operation_4')" @dblclick="clickToShowDialog('operation_4',false)"></div>
                    </div>
                  </div>
                  <div  class="triangle-down" @click="clickToSendUdp('operation_5')" @dblclick="clickToShowDialog('operation_5',false)"></div>
              </div>
              <div  class="el-row">
                  <span class="ir-btn fl" @click="clickToSendUdp('operation_14')" @dblclick="clickToShowDialog('operation_14',true)">{{btnName('operation_14','7')}}</span>
                  <span class="ir-btn fl" @click="clickToSendUdp('operation_15')" @dblclick="clickToShowDialog('operation_15',true)">{{btnName('operation_15','8')}}</span>
                  <span class="ir-btn fr" @click="clickToSendUdp('operation_16')" @dblclick="clickToShowDialog('operation_16',true)">{{btnName('operation_16','10')}}</span>
                  <span class="ir-btn fr" @click="clickToSendUdp('operation_17')" @dblclick="clickToShowDialog('operation_17',true)">{{btnName('operation_17','9')}}</span>
              </div>
              <div  class="el-row">
                  <span class="ir-btn fl" @click="clickToSendUdp('operation_18')" @dblclick="clickToShowDialog('operation_18',true)">{{btnName('operation_18','11')}}</span>
                  <span class="ir-btn fl" @click="clickToSendUdp('operation_19')" @dblclick="clickToShowDialog('operation_19',true)">{{btnName('operation_19','12')}}</span>
                  <span class="ir-btn fr" @click="clickToSendUdp('operation_20')" @dblclick="clickToShowDialog('operation_20',true)">{{btnName('operation_20','14')}}</span>
                  <span class="ir-btn fr" @click="clickToSendUdp('operation_21')" @dblclick="clickToShowDialog('operation_21',true)">{{btnName('operation_21','13')}}</span>
              </div>
            </div>
            <div class="number"  v-show="showPage == 'number'">
              <div class="el-row">
                  <span class="ir-btn" @click="clickToSendUdp('operation_22')" @dblclick="clickToShowDialog('operation_22',false)">1</span>
                  <span class="ir-btn" @click="clickToSendUdp('operation_23')" @dblclick="clickToShowDialog('operation_23',false)">2</span>
                  <span class="ir-btn" @click="clickToSendUdp('operation_24')" @dblclick="clickToShowDialog('operation_24',false)">3</span>
              </div>
              <div class="el-row">
                  <span class="ir-btn" @click="clickToSendUdp('operation_25')" @dblclick="clickToShowDialog('operation_25',false)">4</span>
                  <span class="ir-btn" @click="clickToSendUdp('operation_26')" @dblclick="clickToShowDialog('operation_26',false)">5</span>
                  <span class="ir-btn" @click="clickToSendUdp('operation_27')" @dblclick="clickToShowDialog('operation_27',false)">6</span>
              </div>
              <div class="el-row">
                  <span class="ir-btn" @click="clickToSendUdp('operation_28')" @dblclick="clickToShowDialog('operation_28',false)">7</span>
                  <span class="ir-btn" @click="clickToSendUdp('operation_29')" @dblclick="clickToShowDialog('operation_29',false)">8</span>
                  <span class="ir-btn" @click="clickToSendUdp('operation_30')" @dblclick="clickToShowDialog('operation_30',false)">9</span>
              </div>
              <div class="el-row">
                  <span class="ir-btn" @click="clickToSendUdp('operation_31')" @dblclick="clickToShowDialog('operation_31',false)">※</span>
                  <span class="ir-btn" @click="clickToSendUdp('operation_32')" @dblclick="clickToShowDialog('operation_32',false)">0</span>
                  <span class="ir-btn" @click="clickToSendUdp('operation_33')" @dblclick="clickToShowDialog('operation_33',false)">#</span>
              </div>
            </div>
        </div>
        <el-dialog title="Configure button" :visible.sync="showDialog">
          <el-form>
            <el-form-item v-show="dialogType">
              <el-input v-model="ir_name" auto-complete="off">
                <template slot="prepend">Name</template>
              </el-input>
            </el-form-item>
            <el-form-item >
              <el-input v-model="ir_value" auto-complete="off">
                <template slot="prepend">Value</template>
              </el-input>
            </el-form-item>
            <!-- <el-form-item label="Device ID" prop="deviceid" :rules="[
                                  { required: true, message: 'The Device ID must not be null'}
                                ]">
                <el-input type="deviceid" v-model="property" class="h-40 w-200"></el-input>
            </el-form-item> -->
          </el-form>
          <div slot="footer" class="dialog-footer">
            <el-button @click="showDialog = false">Cancel</el-button>
            <el-button type="primary" @click="submit()" >Save</el-button>
          </div>
        </el-dialog>
    </el-col>
</template>
<style>
.ir {
  position: relative;
  /* border: 1px solid #d1dbe5;
  border-radius: 4px; */
  overflow: hidden;
  text-align: center;
  /* box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12), 0 0 6px 0 rgba(0, 0, 0, 0.04); */
  /* background-color: #666666; */
  color: #aaa !important;
}
.ir .page-btn {
  display: inline-block;
  margin: 1px 3px;
  width: 110px;
  cursor: pointer;
  height: 30px;
  line-height: 30px;
  border: 2px solid #aaa;
  border-radius: 30px;
}
.ir .el-row {
  margin: 15px;
}
.ir .ir-btn {
  display: inline-block;
  margin: 1px 3px;
  overflow: hidden;
  cursor: pointer;
  user-select: none;
  width: 50px;
  height: 30px;
  line-height: 30px;
  border: 2px solid #aaa;
  border-radius: 30px;
}
.ir .number {
  margin-top: 50px;
}
.ir .number .ir-btn {
  user-select: none;
  margin: 0 15px;
}
.home-item {
  height: 140px;
  /* position: relative; */
}
.ir span:hover {
  color: #20a0ff;
  border-color: #20a0ff;
}
.ir .triangle-up:hover {
  border-bottom: 60px solid #20a0ff;
}
.ir .triangle-down:hover {
  border-top: 60px solid #20a0ff;
}
.ir .triangle-left:hover {
  border-right: 60px solid #20a0ff;
}
.ir .triangle-right:hover {
  border-left: 60px solid #20a0ff;
}
.ir .home-btn {
  float: left;
  margin: 18px auto;
  width: 100px;
  height: 100px;
  cursor: pointer;
  user-select: none;
  line-height: 100px;
  font-size: 30px;
  border: 2px solid #aaa;
  border-radius: 100px;
}
.ir .triangle-up {
  display: inline-block;
  width: 0;
  height: 0;
  cursor: pointer;
  user-select: none;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  border-bottom: 60px solid #aaa;
}
.ir .triangle-down {
  display: inline-block;
  width: 0;
  height: 0;
  cursor: pointer;
  user-select: none;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  border-top: 60px solid #aaa;
}
.ir .triangle-left {
  float: left;
  margin: 40px 18px 0 0;
  width: 1px;
  height: 1px;
  cursor: pointer;
  user-select: none;
  border-top: 30px solid transparent;
  border-bottom: 30px solid transparent;
  border-right: 60px solid #aaa;
}
.ir .triangle-right {
  float: left;
  margin: 40px 0 0 18px;
  width: 1px;
  height: 1px;
  cursor: pointer;
  user-select: none;
  border-top: 30px solid transparent;
  border-bottom: 30px solid transparent;
  border-left: 60px solid #aaa;
}
</style>
<script>
import irApi from "./api";
import http from "../../../../assets/js/http";
export default {
  data() {
    return {
      ir_id:"",
      ir_key: "",
      ir_value: "",
      ir_name: "",
      showDialog: false,
      showPage: "control",
      dialogType: true,
      operationObj: {},
      ir_loading: true
    };
  },
  // props: ['device'],
  methods: {
    changePage(val) {
      this.showPage = val;
    },
    btnName(ir_key, old_name) {
      if (this.operationObj[ir_key]) {
        return this.operationObj[ir_key].ir_name;
      } else {
        return old_name;
      }
    },
    clickToShowDialog(ir_key, dialogType) {
      this.ir_key = ir_key;
      if (this.operationObj[ir_key]) {
        this.ir_value = this.operationObj[ir_key].ir_value;
        this.ir_name = this.operationObj[ir_key].ir_name;
      } else {
        this.ir_value = "";
        this.ir_name = "";
      }
      this.dialogType = dialogType;
      this.showDialog = true;
    },
    clickToSendUdp(ir_key) {
      if (this.operationObj[ir_key]) {
        var ir_value = this.operationObj[ir_key].ir_value;
        irApi.buttonClick(this.device, ir_value);
      } else {
        // this.ir_value = this.operationObj[ir_key].ir_value;
        // this.ir_name = this.operationObj[ir_key].ir_name;
        // this.ir_key = ir_key;
      }
    },
    submit() {
      if (this.ir_value < 0 || this.ir_value > 255) {
        _g.toastMsg("error", "The value must in 0 to 255");
        this.showDialog = false;
        return;
      }
      var data = {
        device: this.device.id,
        ir_key: this.ir_key,
        ir_name: this.ir_name,
        ir_value: this.ir_value
      };
      var vm = this;
      if (this.operationObj[this.ir_key]) {
        this.apiPut("admin/ir/", data.device, data).then(res => {
          this.handelResponse(res, data => {
            vm.operationObj[vm.ir_key] = {};
            vm.operationObj[vm.ir_key].device = vm.device.id;
            vm.operationObj[vm.ir_key].ir_key = vm.ir_key;
            vm.operationObj[vm.ir_key].ir_name = vm.ir_name;
            vm.operationObj[vm.ir_key].ir_value = vm.ir_value;
            _g.toastMsg("success", data);
          });
          this.showDialog = false;
        });
      } else {
        this.apiPost("admin/ir", data).then(res => {
          this.handelResponse(res, data => {
            var obj = {};
            obj.device = vm.device.id;
            obj.ir_key = vm.ir_key;
            obj.ir_name = vm.ir_name;
            obj.ir_value = vm.ir_value;
            vm.$set(vm.operationObj, vm.ir_key, obj);
            _g.toastMsg("success", data);
          });
          this.showDialog = false;
        });
      }
    },
    getIrOperation(id) {
     const data = {
        params: {
          device: id
        }
      };
      var vm = this;
      this.apiGet("admin/ir", data).then(res => {
        this.handelResponse(res, data => {
          for (var operation of data) {
            var obj = {};
            obj.device = vm.device.id;
            obj.ir_key = operation.ir_key;
            obj.ir_name = operation.ir_name;
            obj.ir_value = operation.ir_value;
            vm.$set(vm.operationObj, operation.ir_key, obj);
            vm.ir_loading = false;
          }
        });
      });
    }
  },
  mounted() {
    console.log("ir vue");
  },
  components: {},
  computed: {
    device() {
      var device = this.$store.state.device;
      this.showPage = "control";
      this.getIrOperation(device.id);
      return device;
    }
  },
  mixins: [http]
};
</script>