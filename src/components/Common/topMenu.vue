<template>
  <div>
    <el-menu mode="horizontal" default-active="1" @open="handleOpen" @close="handleClose" theme="dark" @select="change" style="background-color:#1f2d3d;">
      <el-menu-item index="global">
        <i class="el-icon-menu"></i>Global</el-menu-item>
        <el-menu-item index="Hotel">
        <i class="el-icon-menu"></i>Build</el-menu-item>
         <el-menu-item index="Floor">
        <i class="el-icon-menu"></i>Floor</el-menu-item>
         <el-menu-item index="Room">
        <i class="el-icon-menu"></i>Room</el-menu-item>
    </el-menu>
  </div>
</template>

<style>

</style>


<script>
export default {
  data() {
    return {
        // showBuild:false,
        // showFloor:false,
        // showRoom:false,
    }
  },
  methods: {
    handleOpen(key, keyPath) {
      // console.log(key, keyPath);
    },
    handleClose(key, keyPath) {
      // console.log(key, keyPath);
    },
    change(key, keyPath) {
      this.showHotel = false
      this.showFloor = false
      this.showRoom = false
      this['show'+key] = true
    }
  },
  created() {
    console.log('topmeun')

  },
  computed: {
   showHotel() {
      return this.$route.query.showHotel;
    },
    showFloor() {
      return this.$route.query.showFloor;
    },
    showRoom() {
      return this.$route.query.showRoom;
    },
  },
}
</script>