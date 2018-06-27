<template>
<div class="w-100p">
    <div class="m-l-50 m-t-30 w-500 plan-update fl">
        <el-form ref="form" :model="form" label-width="110px">
            <el-form-item label="Device Name" prop="device" :rules="[
                                  { required: true, message: 'The Device Name must not be null'}
                                ]">
                <el-input type="device" v-model.trim="form.device" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item v-show="notHotel" label="Device Type">
                <el-select v-model="form.devicetype" filterable placeholder="Select Type" class="h-40 w-200">
                    <el-option v-for="item in deviceTypeOptions" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Breed Type">
                <el-select v-model="form.breed"  filterable placeholder="Select Breed" class="h-40 w-200">
                    <el-option v-for="(item,key) in breedData" :key="key" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Subnet ID" prop="subnetid" :rules="[
                                  { required: true, message: 'The Subnet ID must not be null'}
                                ]">
                <el-input  type="number" v-model="form.subnetid" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Device ID" prop="deviceid" :rules="[
                                  { required: true, message: 'The Device ID must not be null'}
                                ]">
                <el-input type="number" v-model="form.deviceid" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Channel" prop="channel">
                <el-input type="number" v-model="form.channel" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item type="number" v-show="form.devicetype == 'curtain'" label="Channel" prop="channel_spare">
                <el-input type="channel_spare" v-model="form.channel_spare" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item v-show="form.devicetype == 'curtain'" label="Channel" prop="operation_1">
                <el-input type="operation_1" v-model="form.operation_1" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item v-show="notHotel" label="Building">
              <template slot="prepend">Http://</template>
                <el-select  v-model="form.address" @change = "addressChange" filterable placeholder="Select Building" class="h-40 w-200">
                  
                    <el-option v-for="(item,key) in address" :key="key" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item v-show="notHotel" label="Floor">
                <el-select v-model="form.floor" filterable placeholder="Select Floor" @change = "floorChange" class="h-40 w-200">
                    <el-option v-for="(item,key)  in floor" :key="key" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item v-show="notHotel" label="Room">
                <el-select  v-model="form.room" filterable placeholder="Select Room" class="h-40 w-200">
                    <el-option v-for="(item,key) in room" :key="key" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Alexa Name" prop="alexa">
                <el-input type="alexa" v-model.trim="form.alexa" class="h-40 w-200"></el-input>
            </el-form-item>
            <!-- <el-form-item>
                <el-button type="primary" @click="commit('form')" :loading="isLoading">Save</el-button>
                <el-button @click="goback()">Cancel</el-button>
            </el-form-item> -->
        </el-form>
    </div>
    <div class="m-l-50 m-t-30 fl " style="width:360px;">
      
      <p  style="margin:0,color:#606266;">Comment</p>
        <el-input
          style="width:360px;"
          type="textarea"
          :rows="6"
          placeholder="Please enter the comment"
          v-model="form.comment">
        </el-input>
        
        <div class= "m-t-30">
          <div>
          <el-button  class= "fl" @click="search()">search</el-button>
        </div>
          <div class= "fr">
          <el-button type="primary" @click="commit('form')" :loading="isLoading">Save</el-button>
                <el-button @click="goback()">Cancel</el-button>
            </div>
        </div>
    </div>
    <el-dialog title="Device Type Table" :visible.sync="dialogTableVisible" width="600">
      <el-table :data="originalDevices" height="400" @row-dblclick="rowDblclick">
        <el-table-column prop="subnetid" label="Subnet ID" width="100" align = "center"></el-table-column>
        <el-table-column prop="deviceid" label="Device ID" width="100" align = "center"></el-table-column>
        <el-table-column prop="deviceType" label="Device Type" width="200" align = "center"></el-table-column>
        <el-table-column prop="remark" label="Remark"></el-table-column>
      </el-table>
    </el-dialog>

    </div>
</template>
<style>
.plan-update .el-form-item__error {
  padding: 0;
}
</style>


<script>
import http from "../../../assets/js/http";
import fomrMixin from "../../../assets/js/form_com";
export default {
  data() {
    return {
      isLoading: false,
      dialogTableVisible: false,
      // form: {
      //     device: '',
      //     devicetype: 'ac',
      //     subnetid: '',
      //     deviceid: '',
      //     channel: '',
      //     country: '',
      //     address: '',
      //     breed: '',
      //     ip: '',
      //     port: '',
      //     mac: '',
      //     status: 'enabled',
      //     on_off: false,
      //     starttime: '',
      //     endtime: '',

      // },
      form: {},
      deviceTypeOptions: [
        { label: "AC", value: "ac" },
        { label: "Light", value: "light" },
        { label: "LED", value: "led" },
        { label: "Curtain", value: "curtain" },
        { label: "Music", value: "music" },
        { label: "IR", value: "ir" },
        { label: "Security", value: "security" }
        // { label: "FloorHeat", value: "floorheat" }
      ],
      socketio: {}
    };
  },
  methods: {
    search() {
      this.dialogTableVisible = true;
      this.$store.dispatch("setOriginalDevices", []);
      var data = {
        operatorCodefst: "00",
        operatorCodesec: "0e",
        targetSubnetID: "ff",
        targetDeviceID: "ff",
        additionalContentData: [],
        macAddress: [],
        dest_address: "",
        dest_port: "",
        udp_type: "0"
      };
      this.apiPost("admin/udp/sendUdp", data).then(res => {
        // console.log(res)
      });
    },
    rowDblclick(row) {
      this.form.subnetid = row.subnetid;
      this.form.deviceid = row.deviceid;
      this.dialogTableVisible = false;
    },
    goback() {
      this.form.subnetid = _g.toHex(this.form.subnetid);
      this.form.deviceid = _g.toHex(this.form.deviceid);
      this.form.channel = _g.toHex(this.form.channel);
      this.form.channel_spare = _g.toHex(
        this.form.channel_spare ? this.form.channel_spare : 0
      );
      if (this.form.devicetype == "curtain") {
        this.form.operation_1 = _g.toHex(
          this.form.operation_1 ? this.form.operation_1 : 0
        );
      }
      this.$emit("changeUpdate", false);
    },
    addressChange(data) {
      this.form.floor = "";
      this.form.room = "";
      for (var address of this.$store.state.address) {
        if (this.form.address == address.id) {
          this.form.country = address.country;
        }
      }
    },
    floorChange() {
      this.form.room = "";
    },
    commit(form) {
      // this.form.subnetid = _g.toHex(this.form.subnetid)
      // this.form.deviceid = _g.toHex(this.form.deviceid)
      // this.form.channel = _g.toHex(this.form.channel)
      // this.form.channel_spare = _g.toHex(this.form.channel_spare ? this.form.channel_spare : 0)
      if (this.form.comment && this.form.comment.length > 120) {
        this.$message({
          message: "The length of the comment can not exceed 100 characters",
          type: "error"
        });
        return;
      }
      this.isLoading = !this.isLoading;
      var vm = this;
      const data = this.form;
      if (this.form.id) {
        this.apiPut("admin/device/", this.form.id, data).then(res => {
          this.isLoading = !this.isLoading;
          this.handelResponse(res, data => {
            _g.addDeviceProperty(data.device);
            vm.$store.dispatch("setDevices", data.device);
            _g.toastMsg("success", data.result);
            vm.goback();
          });
        });
      } else {
        this.apiPost("admin/device", data).then(res => {
          this.isLoading = !this.isLoading;
          this.handelResponse(res, data => {
            _g.addDeviceProperty(data.device);
            vm.$store.dispatch("setDevices", data.device);
            _g.toastMsg("success", data.result);
            vm.goback();
          });
        });
      }
    },
    getBreedList(breeds) {
      var breedArr = [];
      for (var item of breeds) {
        var breedObj = {};
        breedObj.label = item.breed;
        breedObj.value = item.breed;
        breedArr.push(breedObj);
      }
      return breedArr;
    }
  },
  props: ["device", "notHotel"],
  created() {
    console.log("plan update");
    this.form = Object.assign({}, this.device);
    this.form.subnetid = parseInt("0x" + this.form.subnetid);
    this.form.deviceid = parseInt("0x" + this.form.deviceid);
    this.form.channel = parseInt("0x" + this.form.channel);
    if (this.form.devicetype == "curtain") {
      this.form.channel_spare = parseInt("0x" + this.form.channel_spare);
    }
  },
  mounted() {},
  components: {},

  computed: {
    originalDevices() {
      return this.$store.state.originalDevices;
    },
    maxid() {
      var maxid = this.getBreedList(this.$store.state.maxid);
      return maxid;
    },
    breedData() {
      switch (this.form.devicetype) {
        case "ac":
          var breeds = this.ac_breed;
          return breeds;
          break;
        case "light":
          // return this.$store.state.light_breed
          var breeds = this.light_breed;
          return breeds;
          break;
        case "led":
          // return this.$store.state.led_breed
          var breeds = this.led_breed;
          return breeds;
          break;
      }
    },
    address() {
      var address = [];
      for (var item of this.$store.state.address) {
        var addressObj = {};
        addressObj.label = item.address;
        addressObj.value = item.id.toString();
        address.push(addressObj);
      }
      return address;
    },
    floor() {
      var floor = [];
      if (this.form.address && this.form.address != "") {
        for (var item of this.$store.state.floor) {
          if (item.address == this.form.address) {
            //   if (item.status == "enabled") {
            var floorObj = {};
            floorObj.label = item.floor;
            floorObj.value = item.id.toString();
            floor.push(floorObj);
          }
          // }
        }
      }
      console.log(floor);
      return floor;
    },
    room() {
      var room = [];
      if (this.form.floor && this.form.floor != "") {
        for (var item of this.$store.state.room) {
          if (
            item.address == this.form.address &&
            item.floor == this.form.floor
          ) {
            //   if (item.status == "enabled") {
            var roomObj = {};
            roomObj.label = item.room_name;
            roomObj.value = item.id.toString();
            room.push(roomObj);
          }
          // }
        }
      }

      return room;
    },
    ac_breed() {
      let arr = [];
      for (let item of this.$store.state.ac_breed) {
        let obj = {};
        obj.label = item.breed;
        obj.value = item.id.toString();
        arr.push(obj);
      }
      return arr;
    },
    light_breed() {
      let arr = [];
      for (let item of this.$store.state.light_breed) {
        let obj = {};
        obj.label = item.breed;
        obj.value = item.id.toString();
        arr.push(obj);
      }
      return arr;
    },
    led_breed() {
      let arr = [];
      for (let item of this.$store.state.led_breed) {
        let obj = {};
        obj.label = item.breed;
        obj.value = item.id.toString();
        arr.push(obj);
      }
      return arr;
    }
  },
  watch: {
    // device: {
    //     handler: function(val, oldVal) {
    //         console.log(this.device)
    //         var device = Object.assign({}, this.device)
    //         device.subnetid = parseInt('0x' + device.subnetid)
    //         device.deviceid = parseInt('0x' + device.deviceid)
    //         device.channel = parseInt('0x' + device.channel)
    //         device.channel_spare = parseInt('0x' + device.channel_spare)
    //         this.form = device
    //     },
    //     deep: true
    // },
  },
  mixins: [http, fomrMixin]
};
</script>