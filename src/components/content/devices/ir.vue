<template>
    <el-col :span="24">
        <div class="ir" style="width:600px;height:550px;padding:0;margin:20px auto;text-align: center;">
            <div class="el-row">
                <a class="ir-btn fl" @click="clickToSendUdp('operation_6')" @dblclick="clickToShowDialog('operation_6')">on</a>
                <a class="ir-btn fl" @click="clickToSendUdp('operation_7')" @dblclick="clickToShowDialog('operation_7')">off</a>
                <a class="ir-btn fr" @click="clickToSendUdp('operation_8')" @dblclick="clickToShowDialog('operation_8')">2</a>
                <a class="ir-btn fr" @click="clickToSendUdp('operation_9')" @dblclick="clickToShowDialog('operation_9')">1</a>
            </div>
            <div class="el-row">
                <a class="ir-btn fl" @click="clickToSendUdp('operation_10')" @dblclick="clickToShowDialog('operation_10')">3</a>
                <a class="ir-btn fl" @click="clickToSendUdp('operation_11')" @dblclick="clickToShowDialog('operation_11')">4</a>
                <a class="ir-btn fr" @click="clickToSendUdp('operation_12')" @dblclick="clickToShowDialog('operation_12')">6</a>
                <a class="ir-btn fr" @click="clickToSendUdp('operation_13')" @dblclick="clickToShowDialog('operation_13')">5</a>
            </div>
            <div style="margin:40px 0">
                <div>
                    <div class="triangle-up" @click="clickToSendUdp('operation_2')" @dblclick="clickToShowDialog('operation_2')"></div>
                </div>
                <div class="home-item">
                  <div style="display:inline-block;margin:0 auto;">
                    <div class="triangle-left" @click="clickToSendUdp('operation_3')" @dblclick="clickToShowDialog('operation_3')"></div>
                    <a class="home-btn" @click="clickToSendUdp('operation_1')" @dblclick="clickToShowDialog('operation_1')">OK
                    </a>
                    <div class="triangle-right" @click="clickToSendUdp('operation_4')" @dblclick="clickToShowDialog('operation_4')"></div>
                  </div>
                </div>
                <div  class="triangle-down" @click="clickToSendUdp('operation_5')" @dblclick="clickToShowDialog('operation_5')"></div>
            </div>
            <div  class="el-row">
                <a class="ir-btn fl" @click="clickToSendUdp('operation_14')" @dblclick="clickToShowDialog('operation_14')">7</a>
                <a class="ir-btn fl" @click="clickToSendUdp('operation_15')" @dblclick="clickToShowDialog('operation_15')">8</a>
                <a class="ir-btn fr" @click="clickToSendUdp('operation_16')" @dblclick="clickToShowDialog('operation_16')">10</a>
                <a class="ir-btn fr" @click="clickToSendUdp('operation_17')" @dblclick="clickToShowDialog('operation_17')">9</a>
            </div>
            <div  class="el-row">
                <a class="ir-btn fl" @click="clickToSendUdp('operation_18')" @dblclick="clickToShowDialog('operation_18')">11</a>
                <a class="ir-btn fl" @click="clickToSendUdp('operation_19')" @dblclick="clickToShowDialog('operation_19')">12</a>
                <a class="ir-btn fr" @click="clickToSendUdp('operation_20')" @dblclick="clickToShowDialog('operation_20')">14</a>
                <a class="ir-btn fr" @click="clickToSendUdp('operation_21')" @dblclick="clickToShowDialog('operation_21')">13</a>
            </div>
        </div>
        <el-dialog title="Configure button" :visible.sync="showDialog">
          <el-form>
            <el-form-item >
              <el-input v-model="value" auto-complete="off"></el-input>
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
  border: 1px solid #d1dbe5;
  border-radius: 4px;
  overflow: hidden;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12), 0 0 6px 0 rgba(0, 0, 0, 0.04);
  background-color: #666666;
  color: #cccccc;
}
.ir .el-row {
  margin: 15px;
}
.ir .ir-btn {
  display: inline-block;
  margin: 1px 3px;
  width: 50px;
  height: 30px;
  line-height: 30px;
  border: 2px solid #cccccc;
  border-radius: 30px;
}
.home-item {
  height: 140px;
  /* position: relative; */
}
.ir a:hover {
  color: #333333;
  border-color: #333333;
}
.ir .triangle-up:hover {
  border-bottom: 60px solid #333333;
}
.ir .triangle-down:hover {
  border-top: 60px solid #333333;
}
.ir .triangle-left:hover {
  border-right: 60px solid #333333;
}
.ir .triangle-right:hover {
  border-left: 60px solid #333333;
}
.ir .home-btn {
  float: left;
  margin: 18px auto;
  width: 100px;
  height: 100px;
  line-height: 100px;
  font-size: 30px;
  border: 2px solid #cccccc;
  border-radius: 100px;
}
.ir .triangle-up {
  display: inline-block;
  width: 0;
  height: 0;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  border-bottom: 60px solid #cccccc;
}
.ir .triangle-down {
  display: inline-block;
  width: 0;
  height: 0;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  border-top: 60px solid #cccccc;
}
.ir .triangle-left {
  float: left;
  margin: 40px 18px 0 0;
  width: 1px;
  height: 1px;
  border-top: 30px solid transparent;
  border-bottom: 30px solid transparent;
  border-right: 60px solid #cccccc;
}
.ir .triangle-right {
  float: left;
  margin: 40px 0 0 18px;
  width: 1px;
  height: 1px;
  border-top: 30px solid transparent;
  border-bottom: 30px solid transparent;
  border-left: 60px solid #cccccc;
}
</style>
<script>
import irApi from "./ir/ir.js";
import http from "../../../assets/js/http";
export default {
  data() {
    return {
      key: "",
      value: "",
      showDialog: false
    };
  },
  // props: ['device'],
  methods: {
    clickToShowDialog(key) {
      this.value = this.device[key];
      this.key = key;
      this.showDialog = true;
    },
    clickToSendUdp(key) {
      var value = this.device[key];
      irApi.buttonClick(this.device, value);
    },
    submit() {
      if (this.value < 0 || this.value > 255) {
        _g.toastMsg("error", "The value must in 0 to 255");
        this.showDialog = false;
        return;
      }
      var data = {
        params: {
          id: this.device.id,
          key: this.key,
          value: this.value
        }
      };
      var vm = this;
      this.apiGet("device/index.php?action=setIr", data).then(res => {
        if (res[0]) {
          var devices = this.$store.state.devices;
          for (var i = 0; i < devices.length; i++) {
            if (devices[i].id == this.device.id) {
              devices[i][this.key] = this.value;
            }
          }
          vm.$store.dispatch("setDevices", devices);
          _g.toastMsg("success", res[1]);
        } else {
          _g.toastMsg("error", res[1]);
        }
        // for (var key in this.form) {
        //     this.form[key] = ""
        // }
        this.showDialog = false;
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
      return device;
    }
  },
  mixins: [http]
};
</script>