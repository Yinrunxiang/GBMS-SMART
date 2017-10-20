<template>
    <div class="time-setting-day" >
    <div  class="time-setting-box"  :class="{ 'active': active == day.toLowerCase() }"  @click="dayClick(day)">{{day}}
    </div>
    <div class="time-setting-select" :class="{ 'active': myopen }" @click="daySelect">
        <i class="fa fa-check-circle-o"></i>
    </div>
    </div>
</template>
<style>
.time-setting-day {
  display: inline-block;
    margin:0 2px;
  width: 61px;
  height: 50px;
  text-align: center;
}
.time-setting-day .time-setting-box {
  padding: 0 5px;
  width: 50px;
  height: 35px;
  line-height: 30px;
  text-align: center;
  border-bottom: 3px solid #eef1f6;
}

.time-setting-day .time-setting-box.active {
  color: #20a0ff;
  border-bottom: 3px solid #20a0ff;
}
.time-setting-day .time-setting-box:not(.active):hover {
  color: #20a0ff;
  border-bottom: 3px solid #20a0ff;
}

.time-setting-day .time-setting-select {
  margin-top: 2px;
  padding-top: 3px;
  width: 61px;
  height: 15px;
  line-height: 15px;
  text-align: center;
  border-radius: 3px;
  /* color:#fff;
  background-color: #666 */
}
.time-setting-day .time-setting-select.active {
  color:#fff;
  background-color: #20a0ff
}
.time-setting-day .time-setting-select:hover {
  color:#fff;
  background-color: #20a0ff
}
/* .time-setting-day .time-setting-select:not(.active):hover {
  color:#48576a;
  background-color: #eef1f6;
} */
</style>
<script>
export default {
  data() {
    return {
      myopen: this.open,
      myactive: this.active
    };
  },
  methods: {
    daySelect() {
      // if(this.myopen == 1){
      //     this.myopen  = 0
      // }else{
      //     this.myopen  = 1
      // }

      this.myopen = !this.myopen;
    },
    dayClick() {
      this.myactive = this.day.toLowerCase();
    }
  },
  created() {},
  watch: {
    open(val) {
      this.myopen = val;
    },
    myopen(val) {
      var day = this.day.toLowerCase();
      this.$emit("select", day, val);
    },
    active(val) {
      this.myactive = val;
    },
    myactive(val) {
      this.$emit("change", val);
    }
  },
  props: ["day", "open", "active"]
};
</script>