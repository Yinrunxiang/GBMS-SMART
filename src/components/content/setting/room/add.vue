<template>
    <div class="m-l-50 m-t-30 w-500">
        <el-form ref="form" :model="form" label-width="150px">
            <el-form-item label="Room">
                <el-input  :disabled="!this.add" v-model.trim="form.room" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Room Name">
                <el-input v-model.trim="form.room_name" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item  label="Address">
                <el-select  :disabled="!this.add" v-model="form.address" filterable placeholder="Select Address" class="h-40 w-200">
                    <el-option v-for="item in address" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Floor">
                <el-select  :disabled="!this.add" v-model="form.floor" filterable placeholder="Select Floor" class="h-40 w-200">
                    <el-option v-for="item in floor" :key="item.value" :label="item.label" :value="item.value">
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
      oldRoom:"",
    };
  },
  methods: {
    goback() {
      this.$emit("goback", false);
    },
    addAddress(form) {
      console.log(this.form);
      this.isLoading = !this.isLoading;
      this.form.oldRoom = this.oldRoom;
      const data = {
        params: this.form
      };
      if (this.add) {
        this.apiGet("device/room.php?action=insert", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            var room = this.$store.state.room;
            room.push(this.form);
            this.$store.dispatch("setRoom", room);
            _g.toastMsg("success", res[1]);
            setTimeout(() => {
              this.goback();
            }, 500);
          } else {
            _g.toastMsg("error", res[1]);
          }
          this.isLoading = false;
        });
      } else {
        this.apiGet("device/room.php?action=update", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            var room = this.$store.state.room;
            for (var i = 0; i < room.length; i++) {
              if (room[i].room == this.form.room) {
                room[i] = this.form;
              }
            }
            this.$store.dispatch("setRoom", room);
            _g.toastMsg("success", res[1]);
            setTimeout(() => {
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
  props: ["add", "room"],
  mounted() {
    console.log("room add");
    this.oldRoom = this.room.room;
  },
  computed: {
    form() {
      return this.room;
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
    }
  },
  components: {},
  mixins: [http]
};
</script>