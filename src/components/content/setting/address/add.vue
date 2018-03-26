<template>
  <div class="w-100p">
      <div class="m-l-50 m-t-30 w-500 fl">
          <el-form ref="form" :model="form" label-width="150px">
              <el-form-item label="Country">
                  <el-select v-model="form.country" filterable placeholder="Select Address" class="h-40 w-200">
                      <el-option v-for="item in addressOptions" :key="item.value" :label="item.label" :value="item.value">
                      </el-option>
                  </el-select>
              </el-form-item>
              <el-form-item label="Building">
                  <el-input v-model.trim="form.address" class="h-40 w-200"></el-input>
              </el-form-item>
              <el-form-item label="Floor Num">
                  <el-input v-model.trim="form.floor_num" class="h-40 w-200"></el-input>
              </el-form-item>
              <el-form-item label="Latitude">
                  <el-input v-model.trim="form.lat" class="h-40 w-200"></el-input>
              </el-form-item>
              <el-form-item label="Longitude">
                  <el-input v-model.trim="form.lng" class="h-40 w-200"></el-input>
              </el-form-item>
              <el-form-item label="KW/USD">
                  <el-input v-model.trim="form.kw_usd" class="h-40 w-200"></el-input>
              </el-form-item>
              <el-form-item label="Operation Type">
                <el-radio-group v-model="form.operation" @change="operationChange">
                  <el-radio label="0">Local</el-radio>
                  <el-radio label="1">Remote</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item v-show="form.operation == '1'" label="IP">
                  <el-input v-model.trim="form.ip" class="h-40 w-200"></el-input>
              </el-form-item>
              <el-form-item v-show="form.operation == '1'" label="MAC">
                  <el-input v-model.trim="form.mac" class="h-40 w-200"></el-input>
              </el-form-item>
              
          </el-form>
      </div>
      <div class="m-l-50 m-t-30 fl" style="width:360px;">
          <el-upload
          class="avatar-uploader"
          :action="action"
          :show-file-list="false"
          :auto-upload="true"
          :on-success="handleAvatarSuccess"
          :before-upload="beforeAvatarUpload">
          <img v-if="showImage" :src="showImage" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
             
        </el-upload>
        <el-button v-if="showImage" size="small" type="primary" round style="margin-left:59px" @click="recoveryImage">Recovery</el-button>
        <p  style="margin:0,color:#606266;">Comment</p>
        <el-input
          style="width:360px;"
          type="textarea"
          :rows="6"
          placeholder="Please enter the comment"
          v-model="form.comment">
        </el-input>
        <div class= "m-t-30 fr">
          <el-button type="primary" @click="addAddress('form')" :loading="isLoading">Save</el-button>
          <el-button @click="goback()">Cancel</el-button>
        </div>
      </div>
    </div>
</template>

<style>
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 200px;
  height: 200px;
  line-height: 200px;
  text-align: center;
}
.avatar {
  width: 200px;
  height: 200px;
  display: block;
}
</style>

<script>
import http from "../../../../assets/js/http";
import image from "../../../../assets/js/image";
// import fomrMixin from '../../../../assets/js/form_com'

export default {
  data() {
    return {
      isLoading: false,
      textarea: "",
      // form: {
      //     country: '',
      //     address: '',
      //     ip: '',
      //     port: '',
      //     mac: '',
      //     lat: '',
      //     lng: '',
      //     status: 'enabled',
      // },
      form: {},
      oldAddress: "",
      oldFloorNum: "",
      addressOptions: [],
      
    };
  },
  methods: {
   
    goback() {
      this.$emit("goback", false);
    },
    operationChange(val) {
      this.operation = val;
    },
    getFloor() {
      const data = {
        params: {
          action: "search"
        }
      };
      this.apiGet("device/floor.php", data).then(res => {
        this.$store.dispatch("setFloor", res);
      });
    },
    addAddressRun() {
      var vm = this;
      if(this.form.operation == '1'){
        if(this.form.ip == "" || this.form.mac == ""){
          this.$message.error('In remote mode, you must enter IP and MAC');
          return
        }
      }
      this.isLoading = !this.isLoading;
      this.form.oldAddress = this.oldAddress;
      const data = {
        params: this.form
      };

      if (this.add) {
        this.apiGet("device/address.php?action=insert", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            var address = [];
            address = address.concat(vm.$store.state.address);
            address.push(vm.form);
            vm.$store.dispatch("setAddress", address);
            var floors = [];
            floors = floors.concat(vm.$store.state.floor);
            for (var i = 1; i <= parseInt(vm.form.floor_num); i++) {
              var floorObj = {
                floor: i,
                room_num: 0,
                address: vm.form.address,
                status: "enabled"
              };
              floors.push(floorObj);
            }
            vm.$store.dispatch("setFloor", floors);
            _g.toastMsg("success", res[1]);
            vm.updateDeleteImage()
            vm.success = true;
            setTimeout(() => {
              vm.getFloor();
              vm.goback();
            }, 500);
          } else {
            _g.toastMsg("error", res[1]);
          }
          vm.isLoading = false;
        });
      } else {
        vm.apiGet("device/address.php?action=update", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            var address = [];
            address = address.concat(vm.$store.state.address);
            for (var index in address) {
              if (address[index].address == vm.oldAddress) {
                address[index] = vm.form;
              }
            }
            vm.$store.dispatch("setAddress", address);
            var devices = [];
            devices = devices.concat(vm.$store.state.devices);
            for (var index in devices) {
              if (devices[index].address == vm.oldAddress) {
                if (
                  parseInt(devices[index].floor) > parseInt(vm.form.floor_num)
                ) {
                  devices.splice(index, 1);
                } else {
                  devices[index].address = vm.form.address;
                  devices[index].ip = vm.form.ip;
                  devices[index].port = vm.form.port;
                  devices[index].mac = vm.form.mac;
                  devices[index].country = vm.form.country;
                }
              }
            }
            vm.$store.dispatch("setDevices", devices);
            var floors = [];
            floors = floors.concat(vm.$store.state.floor);
            if (parseInt(vm.oldFloorNum) < parseInt(vm.form.floor_num)) {
              for (
                var i = parseInt(vm.oldFloorNum);
                i <= parseInt(vm.form.floor_num);
                i++
              ) {
                var floorObj = {
                  floor: i,
                  room_num: 0,
                  address: vm.form.address,
                  status: "enabled"
                };
                floors.push(floorObj);
              }
            } else {
              floors = floors.filter(
                item =>
                  item.address != vm.oldAddress ||
                  parseInt(item.floor) <= parseInt(vm.form.floor_num)
              );
              for (var index in floors) {
                if (floors[index].address == vm.oldAddress) {
                  floors[index].address = vm.form.address;
                }
              }
            }
            vm.$store.dispatch("setFloor", floors);
            var rooms = [];
            rooms = rooms.concat(vm.$store.state.room);
            for (var index in rooms) {
              if (rooms[index].address == vm.oldAddress) {
                if (
                  parseInt(rooms[index].floor) > parseInt(vm.form.floor_num)
                ) {
                  rooms.splice(index, 1);
                } else {
                  rooms[index].address = vm.form.address;
                }
              }
            }
            vm.$store.dispatch("setRoom", rooms);
            _g.toastMsg("success", res[1]);
            vm.updateDeleteImage()
            vm.success = true;
            setTimeout(() => {
              vm.getFloor();
              vm.goback();
            }, 500);
          } else {
            _g.toastMsg("error", res[1]);
          }
          vm.isLoading = false;
        });
      }
    },
    addAddress(form) {
      if( this.form.comment.length  > 120){
        this.$message({
          message: "The length of the comment can not exceed 100 characters",
          type: 'error'
        });
        return
      }
      if (parseInt(this.form.floor_num) < parseInt(this.address.floor_num)) {
        this.$confirm(
          "If you reduce the number of floors, the rooms and devices that are deleted will also be removed.",
          "Tips",
          {
            confirmButtonText: "Yse",
            cancelButtonText: "No",
            type: "warning"
          }
        )
          .then(() => {
            this.addAddressRun();
          })
          .catch(() => {
            // catch error
          });
      } else {
        this.addAddressRun();
      }
    },
    getAddressOptions() {
      var option = [
        ["AO", "Angola"],
        ["AF", "Afghanistan"],
        ["AL", "Albania"],
        ["DZ", "Algeria"],
        ["AD", "Andorra"],
        ["AI", "Anguilla"],
        ["AG", "Barbuda Antigua"],
        ["AR", "Argentina"],
        ["AM", "Armenia"],
        ["AU", "Australia"],
        ["AT", "Austria"],
        ["AZ", "Azerbaijan"],
        ["BS", "Bahamas"],
        ["BH", "Bahrain"],
        ["BD", "Bangladesh"],
        ["BB", "Barbados"],
        ["BY", "Belarus"],
        ["BE", "Belgium"],
        ["BZ", "Belize"],
        ["BJ", "Benin"],
        ["BM", "Bermuda Is."],
        ["BO", "Bolivia"],
        ["BW", "Botswana"],
        ["BR", "Brazil"],
        ["BN", "Brunei"],
        ["BG", "Bulgaria"],
        ["BF", "Burkina-faso"],
        ["MM", "Burma"],
        ["BI", "Burundi"],
        ["CM", "Cameroon"],
        ["CA", "Canada"],
        ["CF", "Central African Republic"],
        ["TD", "Chad"],
        ["CL", "Chile"],
        ["CN", "China"],
        ["CO", "Colombia"],
        ["CG", "Congo"],
        ["CK", "Cook Is."],
        ["CR", "Costa Rica"],
        ["CU", "Cuba"],
        ["CY", "Cyprus"],
        ["CZ", "Czech Republic"],
        ["DK", "Denmark"],
        ["DJ", "Djibouti"],
        ["DO", "Dominica Rep."],
        ["EC", "Ecuador"],
        ["EG", "Egypt"],
        ["SV", "EI Salvador"],
        ["EE", "Estonia"],
        ["ET", "Ethiopia"],
        ["FJ", "Fiji"],
        ["FI", "Finland"],
        ["FR", "France"],
        ["GF", "French Guiana"],
        ["GA", "Gabon"],
        ["GM", "Gambia"],
        ["GE", "Georgia"],
        ["DE", "Germany"],
        ["GH", "Ghana"],
        ["GI", "Gibraltar"],
        ["GR", "Greece"],
        ["GD", "Grenada"],
        ["GU", "Guam"],
        ["GT", "Guatemala"],
        ["GN", "Guinea"],
        ["GY", "Guyana"],
        ["HT", "Haiti"],
        ["HN", "Honduras"],
        ["HK", "Hongkong"],
        ["HU", "Hungary"],
        ["IS", "Iceland"],
        ["IN", "India"],
        ["ID", "Indonesia"],
        ["IR", "Iran"],
        ["IQ", "Iraq"],
        ["IE", "Ireland"],
        ["IL", "Israel"],
        ["IT", "Italy"],
        ["JM", "Jamaica"],
        ["JP", "Japan"],
        ["JO", "Jordan"],
        ["KH", "Kampuchea (Cambodia )"],
        ["KZ", "Kazakstan"],
        ["KE", "Kenya"],
        ["KR", "Korea"],
        ["KW", "Kuwait"],
        ["KG", "Kyrgyzstan"],
        ["LA", "Laos"],
        ["LV", "Latvia"],
        ["LB", "Lebanon"],
        ["LS", "Lesotho"],
        ["LR", "Liberia"],
        ["LY", "Libya"],
        ["LI", "Liechtenstein"],
        ["LT", "Lithuania"],
        ["LU", "Luxembourg"],
        ["MO", "Macao"],
        ["MG", "Madagascar"],
        ["MW", "Malawi"],
        ["MY", "Malaysia"],
        ["MV", "Maldives"],
        ["ML", "Mali"],
        ["MT", "Malta"],
        ["MU", "Mauritius"],
        ["MX", "Mexico"],
        ["MD", "Moldova"],
        ["MC", "Monaco"],
        ["MN", "Mongolia"],
        ["MS", "Montserrat Is."],
        ["MA", "Morocco"],
        ["MZ", "Mozambique"],
        ["NA", "Namibia"],
        ["NR", "Nauru"],
        ["NP", "Nepal"],
        ["NL", "Netherlands"],
        ["NZ", "New Zealand"],
        ["NI", "Nicaragua"],
        ["NE", "Niger"],
        ["NG", "Nigeria"],
        ["KP", "North Korea"],
        ["NO", "Norway"],
        ["OM", "Oman"],
        ["PK", "Pakistan"],
        ["PA", "Panama"],
        ["PG", "Papua New Cuinea"],
        ["PY", "Paraguay"],
        ["PE", "Peru"],
        ["PH", "Philippines"],
        ["PL", "Poland"],
        ["PF", "French Polynesia"],
        ["PT", "Portugal"],
        ["PR", "Puerto Rico"],
        ["QA", "Qatar"],
        ["RO", "Romania"],
        ["RU", "Russia"],
        ["LC", "Saint Lueia"],
        ["VC", "Saint Vincent"],
        ["SM", "San Marino"],
        ["ST", "Sao Tome and Principe"],
        ["SA", "Saudi Arabia"],
        ["SN", "Senegal"],
        ["SC", "Seychelles"],
        ["SL", "Sierra Leone"],
        ["SG", "Singapore"],
        ["SK", "Slovakia"],
        ["SI", "Slovenia"],
        ["SB", "Solomon Is."],
        ["SO", "Somali"],
        ["ZA", "South Africa"],
        ["ES", "Spain"],
        ["LK", "Sri Lanka"],
        ["SD", "Sudan"],
        ["SR", "Suriname"],
        ["SZ", "Swaziland"],
        ["SE", "Sweden"],
        ["CH", "Switzerland"],
        ["SY", "Syria"],
        ["TW", "Taiwan"],
        ["TJ", "Tajikstan"],
        ["TZ", "Tanzania"],
        ["TH", "Thailand"],
        ["TG", "Togo"],
        ["TO", "Tonga"],
        ["TT", "Trinidad and Tobago"],
        ["TN", "Tunisia"],
        ["TR", "Turkey"],
        ["TM", "Turkmenistan"],
        ["UG", "Uganda"],
        ["UA", "Ukraine"],
        ["AE", "United Arab Emirates"],
        ["GB", "United Kiongdom"],
        ["US", "United States of America"],
        ["UY", "Uruguay"],
        ["UZ", "Uzbekistan"],
        ["VE", "Venezuela"],
        ["VN", "Vietnam"],
        ["YE", "Yemen"],
        ["YU", "Yugoslavia"],
        ["ZW", "Zimbabwe"],
        ["ZR", "Zaire"],
        ["ZM", "Zambia"]
      ];
      var address = [];
      for (var item of option) {
        var addressObj = {};
        addressObj.label = item[1];
        addressObj.value = item[1];
        address.push(addressObj);
      }
      return address;
    }
  },
  props: ["add", "address"],
  created() {
    //判断远程还是本地
    this.form = Object.assign({}, this.address);
    this.currentImage = this.form.image;
    this.showImage = this.form.image_full;
  },
  mounted() {
    console.log("address add");
    this.oldAddress = this.address.address;
    this.oldFloorNum = this.address.floor_num;
    this.addressOptions = this.getAddressOptions();
  },
  destroyed() {
    this.destroyedDeleteImage()
  },
  computed: {},
  components: {},
  mixins: [http,image]
};
</script>