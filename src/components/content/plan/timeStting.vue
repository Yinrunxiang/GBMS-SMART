<template>
    <el-dialog v-if="ready" class="time-setting" ref="dialog" :visible="open" :before-close="handleClose" custom-class="w-500" title="Time Contral Center">
        <div>
            <div class="day-item" w-100p>
                <day v-for="day in week" :day="day" :active="showing" :open="runtime[day.toLowerCase()].status" @change="dayChange" @select="daySelect" @click="dayClick(day.toLowerCase())"></day>
            </div>
            <div class="m-t-20">
                <el-time-select class="m-l-20" placeholder="Start Time" v-model="runtime[showing].up"  :picker-options="{
                                                      start: '00:00',
                                                      step: '00:10',
                                                      end: '24:00'
                                                    }">
                </el-time-select>
                <el-time-select class="m-l-20" placeholder="End Time" v-model="runtime[showing].down"  :picker-options="{
                                              start: '00:00',
                                              step: '00:10',
                                              end: '24:00',
                              minTime: runtime[showing].up   
                                            }">
                </el-time-select>
                 </div>
        </div>
        <div class="p-t-20 p-b-20">
            <el-button type="primary" class="fl " :disabled="disable" @click="submit()">Save</el-button>
        </div>
    </el-dialog>
</template>
<style>
.time-setting .day-item {
  height: 60px;
  background-color: #eef1f6;
}

.time-setting .day {
  display: inline-block;
  padding: 0 6px;
  width: 50px;
  height: 35px;
  line-height: 30px;
  text-align: center;
}

.time-setting .day.active {
  color: #20a0ff;
  border-bottom: 3px solid #20a0ff;
}
</style>
<script>
import day from "./day";
import http from "../../../assets/js/http";

export default {
  data() {
    return {
      id:'',
      disable: false,
      myopen: this.open,
      runtime: {},
      week: ["Sun", "Mon", "Tues", "Wed", "Thur", "Fri", "Sat"],
      showing: "sun",
      ready: false
    };
  },
  methods: {
    //启用时间事件
    daySelect(day, val) {
      //   this.runtime[day].status = val;
      this.runtime[day].status = val;
    },
    //选中星期几事件
    dayChange(val) {
      this.showing = val;
    },
    handleClose() {
      this.myopen = false;
    },
    //保存运行时间
    submit() {
      var params = {
        action:'setRunTime',
          device_id : this.device.id,
      }
      if(this.id){
        params.type='update'
      }
      else{
        params.type='insert'
      }
      for(var key in this.runtime){
          params[key+'_up'] =this.runtime[key].up
        params[key+'_down'] =this.runtime[key].down
        params[key+'_status'] =this.runtime[key].status?1:0
      }
      const data = {
        params: params
      };
       this.apiGet("device/index.php", data).then(res => {
           if(res[0]){
             _g.toastMsg('success', res[1])
           }else{
             _g.toastMsg('error', res[1])
           }
       })
    },
    //获取运行时间
    getRunTime() {
      const data = {
        params: {
          action: "getRunTime",
          device_id: this.device.id
        }
      };
      _g.openGlobalLoading()
      this.apiGet("device/index.php", data).then(res => {
        if (res[0]) {
          var data = res[0];
          this.id = res[0].id
          var runtime = {
            sun: {
              up: data.sun_up,
              down: data.sun_down,
              status: data.sun_status == 1 ? true : false
            },
            mon: {
              up: data.mon_up,
              down: data.mon_down,
              status: data.mon_status == 1 ? true : false
            },
            tues: {
              up: data.tues_up,
              down: data.tues_down,
              status: data.tues_status == 1 ? true : false
            },
            wed: {
              up: data.wed_up,
              down: data.wed_down,
              status: data.wed_status == 1 ? true : false
            },
            thur: {
              up: data.thur_up,
              down: data.thur_down,
              status: data.thur_status == 1 ? true : false
            },
            fri: {
              up: data.fri_up,
              down: data.fri_down,
              status: data.fri_status == 1 ? true : false
            },
            sat: {
              up: data.sat_up,
              down: data.sat_down,
              status: data.sat_status == 1 ? true : false
            }
          };
          console.log(runtime);
          this.runtime = runtime;
        } else {
          var runtime = {
            sun: {
              up: "00:00",
              down: "00:00",
              status: false
            },
            mon: {
              up: "00:00",
              down: "00:00",
              status: false
            },
            tues: {
              up: "00:00",
              down: "00:00",
              status: false
            },
            wed: {
              up: "00:00",
              down: "00:00",
              status: false
            },
            thur: {
              up: "00:00",
              down: "00:00",
              status: false
            },
            fri: {
              up: "00:00",
              down: "00:00",
              status: false
            },
            sat: {
              up: "00:00",
              down: "00:00",
              status: false
            }
          };
          console.log(runtime);
          this.runtime = runtime;
        }
        this.ready = true;
        _g.closeGlobalLoading()
      });
    }
  },
  created() {
    console.log("changePwd");
    this.getRunTime();

    // this.form.auth_key = Lockr.get('authKey')
  },
  props: ["open", "device"],
  watch: {
    open(val) {
      this.myopen = val;
    },
    myopen(val) {
      this.$emit("change", val);
    }
  },
  components: {
    day
  },
  mixins: [http]
};
</script>