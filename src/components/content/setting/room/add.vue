<template>
  <div class="w-100p">
    <div class="m-l-50 m-t-30 w-500 fl">
        <el-form ref="form" :model="form" label-width="150px">
            <el-form-item label="Room">
                <el-input  :disabled="!this.add" v-model.trim="form.room" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Room Name">
                <el-input v-model.trim="form.room_name" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item  label="Address">
                <el-select  :disabled="!this.add" v-model="form.address" filterable placeholder="Select Address" class="h-40 w-200">
                    <el-option v-for="(item,key) in address" :key="key" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Floor">
                <el-select  :disabled="!this.add" v-model="form.floor" filterable placeholder="Select Floor" class="h-40 w-200">
                    <el-option v-for="(item,key) in floor" :key="key" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
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
          <img v-if="form.image" :src="form.image" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
             
        </el-upload>
        <el-button v-if="form.image" size="small" type="primary" round style="margin-left:59px" @click="recoveryImage">Recovery</el-button>
        <p  style="margin:0,color:#606266;">Remarks</p>
        <el-input
          style="width:360px;"
          type="textarea"
          :rows="6"
          placeholder="请输入内容"
          v-model="form.comment">
        </el-input>
        <div class= "m-t-30 fr">
          <el-button type="primary" @click="addAddress()" :loading="isLoading">Save</el-button>
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
      form:{},
      addressOptions: [],
      oldRoom:"",
    };
  },
  methods: {
    goback() {
      this.$emit("goback", false);
    },
    addAddress() {
      var vm = this
      this.isLoading = !this.isLoading;
      this.form.oldRoom = this.oldRoom;
      const data = {
        params: this.form
      };
      if (this.add) {
        this.apiGet("device/room.php?action=insert", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            var room = vm.$store.state.room;
            room.push(vm.form);
            vm.$store.dispatch("setRoom", room);
            _g.toastMsg("success", res[1]);
            setTimeout(() => {
              vm.goback();
            }, 500);
          } else {
            _g.toastMsg("error", res[1]);
          }
          vm.isLoading = false;
        });
      } else {
        this.apiGet("device/room.php?action=update", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            var room = [];
            room = room.concat(vm.$store.state.room);
            for (var i = 0; i < room.length; i++) {
              if (room[i].room == vm.form.room && room[i].floor == vm.form.floor && room[i].address == vm.form.address) {
                room[i] = vm.form;
              }
            }
            vm.$store.dispatch("setRoom", room);
            _g.toastMsg("success", res[1]);
            setTimeout(() => {
              vm.goback();
            }, 500);
          } else {
            _g.toastMsg("error", res[1]);
          }
          vm.isLoading = false;
        });
      }
    }
  },
  props: ["add", "room"],
  created(){
    this.form = Object.assign({},this.room)
    this.currentImage = this.form.image;
  },
  mounted() {
    console.log("room add");
    this.oldRoom = this.room.room;
  },
  computed: {
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
  mixins: [http,image]
};
</script>