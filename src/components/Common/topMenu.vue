<template>
  <div>
    <el-menu class="horizontal-menu el-menu-vertical-demo"  v-if="showHotel || showFloor || showRoom"  mode="horizontal" default-active="1" @open="handleOpen" @close="handleClose" theme="dark" @select="change" background-color="#1f2d3d" text-color="#eee" active-text-color = "#409EFF">
      <el-menu-item index="global">Global</el-menu-item>
        <el-menu-item index="Hotel">Building</el-menu-item>
         <el-menu-item v-show="showFloor || showRoom" index="Floor">Floor</el-menu-item>
         <el-menu-item v-show="showRoom" index="Room">Room</el-menu-item>
    </el-menu>
  </div>
</template>

<style>
.horizontal-menu  i{
  color:#eee;
}
</style>


<script>
export default {
  data() {
    return {
      // showBuild:false,
      // showFloor:false,
      // showRoom:false,
      // show:false
      active: "Global"
    };
  },
  methods: {
    handleOpen(key, keyPath) {
      // console.log(key, keyPath);
    },
    handleClose(key, keyPath) {
      // console.log(key, keyPath);
    },
    change(key, keyPath) {
      if (key == "global") {
        let url = "/home/global";
        router.push(url);
      } else {
        this.$store.dispatch("setShowHotel", false);
        this.$store.dispatch("setShowFloor", false);
        this.$store.dispatch("setShowRoom", false);
        // this.showHotel = false
        // this.showFloor = false
        // this.showRoom = false
        // this['show'+key] = true
        this.$store.dispatch("setShow" + key, true);
      }
    }
  },
  created() {
    console.log("topmeun");
  },
  computed: {
    showHotel() {
      this.active = "Hotel";
      return this.$store.state.showHotel;
    },
    showFloor() {
      this.active = "Floor";
      return this.$store.state.showFloor;
    },
    showRoom() {
      this.active = "Room";
      return this.$store.state.showRoom;
    }
  }
};
</script>