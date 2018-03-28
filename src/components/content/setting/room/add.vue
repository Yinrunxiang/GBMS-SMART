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
            <el-form-item label="Address">
                <el-input :disabled="!this.add" v-model.trim="form.address" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Floor">
                <el-input :disabled="!this.add" v-model.trim="form.floor" class="h-40 w-200"></el-input>
            </el-form-item>
            <!-- <el-form-item  label="Address">
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
            </el-form-item> -->
        </el-form>
    </div>
    <div class="m-l-50 m-t-30 fl" style="position:relative;width:360px;">
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
        <el-button v-if="showImage" size="medium" type="primary" round style="margin-left:59px" @click="recoveryImage">Recovery</el-button>
        <el-button size="medium" type="primary" v-if="base64Image.substr(0,4) == 'data'"  @click="showFloorImage = true" style="position:absolute;top:206px;right:0;">Change position</el-button>
        <p  style="margin:0,color:#606266;">Comment</p>
        <el-input
          style="width:360px;"
          type="textarea"
          :rows="6"
          placeholder="Please enter the comment"
          v-model="form.comment">
        </el-input>
        <div class= "m-t-30 fr">
          <el-button type="primary" @click="addAddress()" :loading="isLoading">Save</el-button>
          <el-button @click="goback()">Cancel</el-button>
        </div>
    </div>
    <el-dialog title="" v-if="showFloorImage" :visible.sync="showFloorImage" :width="floorImageWidth+40+'px'">
      <vueCorpper
        :style="{height:floorImageHeight+'px',width:floorImageWidth+'px'}"
        v-if="showFloorImage"
        ref="cropper"
        :autoCrop="true"
          :img="base64Image"
          :canScale="false"
          :canMove = "false"
          :fixed = "false"
        ></vueCorpper>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogClose">Cannel</el-button>
          <el-button type="primary" @click="dialogSave">Save</el-button>
        </div>
    </el-dialog>
    
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
import image_api from "../../../../assets/js/image";
import vueCorpper from "../../../Common/vue-corpper";
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
      oldRoom: "",
      showFloorImage: false,
      floorImage: "",
      floorImageWidth: "",
      floorImageHeight: "",
      base64Image: ""
    };
  },
  methods: {
    goback() {
      this.$emit("goback", false);
    },
    getImage(image) {
      let img = new Image();
      img.crossOrigin = "*";
      img.src = image;
      img.onload = () => {
        this.floorImageWidth = img.width;
        this.floorImageHeight = img.height;
      };
    },
    dialogClose() {
      this.showFloorImage = false;
    },
    dialogSave() {
      this.form.width = this.$refs.cropper.cropW;
      this.form.height = this.$refs.cropper.cropH;
      this.form.lat = this.$refs.cropper.cropOffsertX;
      this.form.lng = this.$refs.cropper.cropOffsertY;
      this.showFloorImage = false;
      // console.log(this.form);
    },
    addAddress() {
      if( this.form.comment && this.form.comment.length  > 120){
        this.$message({
          message: "The length of the comment can not exceed 100 characters",
          type: 'error'
        });
        return
      }
      var vm = this;
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
              if (
                room[i].room == vm.form.room &&
                room[i].floor == vm.form.floor &&
                room[i].address == vm.form.address
              ) {
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
    },

    getBase64Image(img, width, height) {
      //width、height调用时传入具体像素值，控制大小 ,不传则默认图像大小
      var canvas = document.createElement("canvas");
      canvas.width = width ? width : img.width;
      canvas.height = height ? height : img.height;

      var ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
      var dataURL = canvas.toDataURL();
      return dataURL;
    },
    getFloorImage() {
      var imgSrc = this.floor.image;

      function getBase64(img) {
        //传入图片路径，返回base64

        var image = new Image();
        image.crossOrigin = "*";
        image.src = img;
        return new Promise((resolve, reject) => {
          if (img) {
            image.onload = () => {
              resolve(this.getBase64Image(image)); //将base64传给done上传处理
            };
            image.onerror = () => {
              reject("error");
            };
          }
        });
      }
      getBase64(imgSrc).then(
        function(base64) {
          // console.log(base64);
        },
        function(err) {
          // console.log(err);
        }
      );
    }
  },
  props: ["add", "room"],
  created() {
    var vm = this;
    this.form = Object.assign({}, this.room);
    this.currentImage = this.form.image;
    this.showImage = this.form.image_full;
    var floor = this.$store.state.floor.filter(function(item) {
      return item.address == vm.form.address && item.floor == vm.form.floor;
    });
    // console.log(floor[0])
    this.floorImage = floor[0].image;
    let data = {
      params: {
        image: this.floorImage
      }
    };
    this.apiGet("/upload/image.php", data).then(res => {
      this.base64Image = res;
      this.getImage(res);
      // console.log(res)
    });

    // this.getFloorImage()

    // let img = new Image();
    // img.src = this.floor.image;
    // img.setAttribute("crossOrigin",'Anonymous')
    // img.onload = () => {
    //   var canvas = document.createElement("canvas");
    //   canvas.width = img.width;
    //   canvas.height = img.height;

    //   var ctx = canvas.getContext("2d");
    //   ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    //   var dataURL = canvas.toDataURL();
    //   console.log(dataURL);
    // };
  },
  mounted() {
    console.log("room add");
    this.oldRoom = this.room.room;

    //  var imageX = this.$refs.image

    // imageX.onload = () => {
    //   // imageX.crossOrigin = "Anonymous";
    //   var canvas = document.createElement("canvas");
    //   canvas.width = imageX.width;
    //   canvas.height = imageX.height;

    //   var ctx = canvas.getContext("2d");
    //   ctx.drawImage(imageX, 0, 0, canvas.width, canvas.height);
    //   var dataURL = canvas.toDataURL();
    //   console.log(dataURL);
    // };
  },
  computed: {
    // address() {
    //   var address = [];
    //   for (var item of this.$store.state.address) {
    //     var addressObj = {};
    //     addressObj.label = item.address;
    //     addressObj.value = item.address;
    //     addressObj.address = item;
    //     address.push(addressObj);
    //   }
    //   return address;
    // },
    // floor() {
    //   var floor = [];
    //   if (this.form.address && this.form.address != "") {
    //     for (var item of this.$store.state.floor) {
    //       if (item.address == this.form.address) {
    //         var floorObj = {};
    //         floorObj.label = item.floor;
    //         floorObj.value = item.floor;
    //         floorObj.floor = item;
    //         floor.push(floorObj);
    //       }
    //     }
    //   }
    //   return floor;
    // }
  },
  components: {
    vueCorpper
  },
  mixins: [http, image_api]
};
</script>