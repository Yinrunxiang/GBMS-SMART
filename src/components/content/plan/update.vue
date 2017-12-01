<template>
    <div class="m-l-50 m-t-30 w-500 plan-update">
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
                <el-select v-model="form.breed" filterable placeholder="Select Breed" class="h-40 w-200">
                    <el-option v-for="item in breedData" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Subnet ID" prop="subnetid" :rules="[
                                  { required: true, message: 'The Subnet ID must not be null'}
                                ]">
                <el-input type="subnetid" v-model="form.subnetid" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Device ID" prop="deviceid" :rules="[
                                  { required: true, message: 'The Device ID must not be null'}
                                ]">
                <el-input type="deviceid" v-model="form.deviceid" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Channel" prop="channel" :rules="[
                                  { required: true, message: 'The Channel must not be null'}
                                ]">
                <el-input type="channel" v-model="form.channel" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item v-show="form.devicetype == 'curtain'" label="Channel Spare" prop="channel_spare" :rules="[
                                  { required: true, message: 'The Channel Spare must not be null'}
                                ]">
                <el-input type="channel_spare" v-model="form.channel_spare" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item v-show="notHotel" label="Building">
                <el-select v-model="form.address" filterable placeholder="Select Building" class="h-40 w-200">
                    <el-option v-for="item in address" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item v-show="notHotel" label="Floor">
                <el-select v-model="form.floor" filterable placeholder="Select Floor" class="h-40 w-200">
                    <el-option v-for="item in floor" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item v-show="notHotel" label="Room">
                <el-select v-model="form.room" filterable placeholder="Select Room" class="h-40 w-200">
                    <el-option v-for="item in room" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="commit('form')" :loading="isLoading">Save</el-button>
                <el-button @click="goback()">Cancel</el-button>
            </el-form-item>
        </el-form>
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
      // form: {},
      deviceTypeOptions: [
        { label: "AC", value: "ac" },
        { label: "Light", value: "light" },
        { label: "LED", value: "led" },
        { label: "Curtain", value: "curtain" },
        { label: "Music", value: "music" },
        { label: "IR", value: "ir" }
      ]
    };
  },
  methods: {
    goback() {
      this.form.subnetid = _g.toHex(this.form.subnetid);
      this.form.deviceid = _g.toHex(this.form.deviceid);
      this.form.channel = _g.toHex(this.form.channel);
      this.form.channel_spare = _g.toHex(
        this.form.channel_spare ? this.form.channel_spare : 0
      );
      this.$emit("changeUpdate", false);
    },
    commit(form) {
      // this.form.subnetid = _g.toHex(this.form.subnetid)
      // this.form.deviceid = _g.toHex(this.form.deviceid)
      // this.form.channel = _g.toHex(this.form.channel)
      // this.form.channel_spare = _g.toHex(this.form.channel_spare ? this.form.channel_spare : 0)
      this.isLoading = !this.isLoading;
      var vm = this;
      const data = {
        params: this.form
      };
      if (this.form.id) {
        this.apiGet("device/index.php?action=update", data).then(res => {
          // _g.clearVuex('setRules')
          console.log(res);

          if (res[0]) {
            var devices = this.$store.state.devices;
            for (var i = 0; i < devices.length; i++) {
              if (devices[i].id == this.form.id) {
                devices[i] = this.form;
              }
            }
            vm.$store.dispatch("setDevices", devices);
            _g.toastMsg("success", res[1]);
            vm.goback();
          } else {
            _g.toastMsg("error", res[1]);
          }
          // for (var key in this.form) {
          //     this.form[key] = ""
          // }
          this.isLoading = !this.isLoading;
        });
      } else {
        this.apiGet("device/index.php?action=insert", data).then(res => {
          // _g.clearVuex('setRules')
          console.log(res);

          if (res[0]) {
            var devices = vm.$store.state.devices;
            var addressList = vm.$store.state.address;
            for (var address of addressList) {
              if (vm.form.address == address.address) {
                vm.form.country = address.country
                vm.form.ip = address.ip
                vm.form.port = address.port
                vm.form.mac = address.mac
              }
            }
            console.log("maxid:" + vm.$store.state.maxid);
            vm.form.id = parseInt(vm.$store.state.maxid) + 1;
            devices.push(vm.form);
            this.$emit("newDevice", vm.form);
            vm.$store.dispatch("setDevices", devices);
            _g.toastMsg("success", res[1]);
            vm.goback();
          } else {
            _g.toastMsg("error", res[1]);
          }
          // for (var key in this.form) {
          //     this.form[key] = ""
          // }
          vm.isLoading = !vm.isLoading;
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
    // this.form.subnetid = parseInt('0x' + this.form.subnetid)
    // this.form.deviceid = parseInt('0x' + this.form.deviceid)
    // this.form.channel = parseInt('0x' + this.form.channel)
    // this.form.channel_spare = parseInt('0x' + this.form.channel_spare)
  },
  mounted() {},
  components: {},

  computed: {
    form() {
      // var device = this.$store.state.device
      console.log(this.device);
      var device = this.device;
      // var device = Object.assign({}, this.device)
      // device.subnetid = parseInt('0x' + device.subnetid)
      // device.deviceid = parseInt('0x' + device.deviceid)
      // device.channel = parseInt('0x' + device.channel)
      // device.channel_spare = parseInt('0x' + device.channel_spare)
      return device;

      // var device = Object.assign({}, this.device)

      // device.subnetid = parseInt('0x' + device.subnetid)
      // device.deviceid = parseInt('0x' + device.deviceid)
      // device.channel = parseInt('0x' + device.channel)
      // device.channel_spare = parseInt('0x' + device.channel_spare)
      // return device
    },
    maxid() {
      var maxid = this.getBreedList(this.$store.state.maxid);
      return maxid;
    },
    breedData() {
      switch (this.form.devicetype) {
        case "ac":
          var breeds = this.getBreedList(this.$store.state.ac_breed);
          return breeds;
          break;
        case "light":
          // return this.$store.state.light_breed
          var breeds = this.getBreedList(this.$store.state.light_breed);
          return breeds;
          break;
        case "led":
          // return this.$store.state.led_breed
          var breeds = this.getBreedList(this.$store.state.led_breed);
          return breeds;
          break;
      }
    },
    address() {
      var address = [];
      for (var item of this.$store.state.address) {
        var addressObj = {};
        addressObj.label = item.address;
        addressObj.value = item.address;
        addressObj.address = item;
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
            floorObj.value = item.floor;
            floorObj.floor = item;
            floor.push(floorObj);
          }
          // }
        }
      }
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
            roomObj.value = item.room;
            roomObj.room = item;
            room.push(roomObj);
          }
          // }
        }
      }

      return room;
    }
  },
  // watch: {
  //     device: {
  //         handler: function(val, oldVal) {
  //             console.log(this.device)
  //             var device = Object.assign({}, this.device)
  //             device.subnetid = parseInt('0x' + device.subnetid)
  //             device.deviceid = parseInt('0x' + device.deviceid)
  //             device.channel = parseInt('0x' + device.channel)
  //             device.channel_spare = parseInt('0x' + device.channel_spare)
  //             this.form = device
  //         },
  //         deep: true
  //     }
  // },
  mixins: [http, fomrMixin]
};
</script>