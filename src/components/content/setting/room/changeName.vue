<template>
  <el-dialog ref="dialog" :visible="showChange" :before-close="handleClose" custom-class="w-400 h-300" title="Change Name">
    <div>
      <el-form ref="form" :model="form" :rules="rules" label-width="120px">
        <el-form-item label="Room Name" prop="room_name" class="m-b-15">
          <el-input v-model.trim="form.room_name"></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="p-t-20">
      <el-button type="primary" class="fl m-l-20" :disabled="disable" @click="submit()">Save</el-button>
    </div>
  </el-dialog>
</template>
<style>

</style>
<script>
import http from "../../assets/js/http";

export default {
  data() {
    return {
      disable: false,
      myShowChange: this.showChange,
      form: {
        room_name: ""
      }
    };
  },
  methods: {
    // open() {
    //   console.log(this.$refs.dialog)
    //   this.$refs.dialog.open()
    // },
    // close() {
    //   this.$refs.dialog.close()
    // },
    handleClose(done) {
      this.myShowChange = false;
    },
    submit() {
      this.disable = !this.disable;
      const data = {
        params: this.form
      };
      this.apiGet("device/room.php?action=change", data).then(res => {
        if (res[0]) {
          _g.toastMsg("success", "Change success");
         this.myShowChange = false;
        } else {
          _g.toastMsg("error", "Change failed");
          this.disable = !this.disable;
        }
      })
    }
  },
  created() {
    console.log("changeName");
    // this.form.auth_key = Lockr.get('authKey')
  },
  props: ["room","showChange"],
  watch: {
    showChange(val) {
      this.myShowChange = val;
    },
    myShowChange(val) {
      this.$emit("change", val);
    }
  },

  mixins: [http]
};
</script>