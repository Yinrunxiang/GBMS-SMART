<template>
    <div class="m-l-50 m-t-30 w-500">
        <el-form ref="form" :model="form" label-width="150px">
            <el-form-item label="Floor">
                <el-input  :disabled="!this.add" v-model.trim="form.floor" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Room Num">
                <el-input v-model.trim="form.room_num" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Address">
                <el-select  :disabled="!this.add" v-model="form.address" filterable placeholder="Select Address" class="h-40 w-200">
                    <el-option v-for="item in address" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="addAddress('form')" :loading="isLoading">Save</el-button>
                <el-button @click="goback()">Cancel</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import http from "../../../../assets/js/http";
// import fomrMixin from '../../../../assets/js/form_com'

export default {
  data() {
    return {
      isLoading: false,
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
      addressOptions: [],
      oldFloor:"",
    };
  },
  methods: {
    goback() {
      this.$emit("goback", false);
    },
    getRoom() {
      const data = {
        params: {
          action: "search"
        }
      };
      this.apiGet("device/room.php", data).then(res => {
        this.$store.dispatch("setRoom", res);
      });
    },
    addAddress(form) {
      console.log(this.form);
      this.isLoading = !this.isLoading;
      this.form.oldFloor = this.oldFloor;
      const data = {
        params: this.form
      };
      if (this.add) {
        this.apiGet("device/floor.php?action=insert", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            var floor = this.$store.state.floor;
            floor.push(this.form);
            this.$store.dispatch("setFloor", floor);
            _g.toastMsg("success", res[1]);
            setTimeout(() => {
              this.getRoom()
              this.goback();
            }, 500);
          } else {
            _g.toastMsg("error", res[1]);
          }
          this.isLoading = false;
        });
      } else {
        this.apiGet("device/floor.php?action=update", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            var floor = this.$store.state.floor;
            for (var i = 0; i < floor.length; i++) {
              if (floor[i].floor == this.form.floor) {
                floor[i] = this.form;
              }
            }
            this.$store.dispatch("setFloor", floor);
            _g.toastMsg("success", res[1]);
            setTimeout(() => {
              this.getRoom()
              this.goback();
            }, 500);
          } else {
            _g.toastMsg("error", res[1]);
          }
          this.isLoading = false;
        });
      }
    }
  },
  props: ["add", "floor"],
  mounted() {
    console.log("floor add");
    this.oldFloor = this.floor.floor;
  },
  computed: {
    form() {
      return this.floor;
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
    }
  },
  components: {},
  mixins: [http]
};
</script>