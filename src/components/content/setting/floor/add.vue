<template>
<div class="w-100p">
    <div class="m-l-50 m-t-30 w-500 fl">
        <el-form ref="form" :model="form" label-width="150px">
            <el-form-item label="Floor">
                <el-input  :disabled="!this.add" v-model.trim="form.floor" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Room Num">
                <el-input v-model.trim="form.room_num" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Address">
                <el-select  :disabled="!this.add" v-model="form.address" filterable placeholder="Select Address" class="h-40 w-200">
                    <el-option v-for="(item,key) in address" :key="key" :label="item.label" :value="item.value">
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
          <el-button type="primary" @click="addFloor('form')" :loading="isLoading">Save</el-button>
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
      form: {},
      addressOptions: [],
      oldFloor: "",
      oldRoomNum: ""
    };
  },
  methods: {
    goback() {
      this.$emit("goback", false);
    },
    addFloorRun() {
      var vm = this;
      this.isLoading = !this.isLoading;
      this.form.oldFloor = this.oldFloor;
      const data = this.form;
      if (this.add) {
        vm.apiPost("admin/floor", data).then(res => {
          vm.handelResponse(res, data => {
            vm.$store.dispatch("setFloor", data.floor);
            vm.$store.dispatch("setRoom", data.room);
            _g.toastMsg("success", data.result);
            setTimeout(() => {
              vm.goback();
            }, 500);
          });

          vm.isLoading = false;
        });
      } else {
        vm.apiPut("admin/floor/", data.id, data).then(res => {
          vm.handelResponse(res, data => {
            vm.$store.dispatch("setFloor", data.floor);
            vm.$store.dispatch("setRoom", data.room);
            vm.$store.dispatch("setDevices", data.device);
            _g.toastMsg("success", data.result);
            setTimeout(() => {
              vm.goback();
            }, 500);
          });

          vm.isLoading = false;
        });
      }
    },
    addFloor(form) {
      if (this.form.comment && this.form.comment.length > 120) {
        this.$message({
          message: "The length of the comment can not exceed 100 characters",
          type: "error"
        });
        return;
      }
      if (parseInt(this.form.room_num) < parseInt(this.floor.room_num)) {
        this.$confirm(
          "If you reduce the number of rooms,the devices in the deleted room will also be deleted.",
          "Tips",
          {
            confirmButtonText: "Yse",
            cancelButtonText: "No",
            type: "warning"
          }
        )
          .then(() => {
            this.addFloorRun();
          })
          .catch(() => {
            // catch error
          });
      } else {
        this.addFloorRun();
      }
    }
  },
  props: ["add", "floor"],
  created() {
    this.form = Object.assign({}, this.floor);
    this.currentImage = this.form.image;
    this.showImage = this.form.image_full;
  },
  mounted() {
    console.log("floor add");
    this.oldFloor = this.floor.floor;
    this.oldRoomNum = this.floor.room_num;
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
    }
  },
  components: {},
  mixins: [http, image]
};
</script>