<template>
  <div @click.stop="phoneNavStop()">
    <el-menu default-active="1" class="vertical-menu el-menu-vertical-demo" @open="handleOpen" @close="handleClose" background-color="#324057" text-color="#eee"  @select="routerChange">
      <el-menu-item index="global" @click="phoneNavHidden">
        <i class="el-icon-menu"></i>Global</el-menu-item>
      <el-menu-item index="contral" @click="phoneNavHidden">
        <el-badge :value="deviceWarn" class="badge-div">
          <i class="el-icon-menu"></i>Center
        </el-badge>
      </el-menu-item>
      <el-menu-item index="plan" @click="phoneNavHidden">
        <i class="el-icon-menu"></i>Plan</el-menu-item>
        <el-menu-item index="runing" @click="phoneNavHidden">
        <i class="el-icon-menu"></i>Runing</el-menu-item>
        <el-menu-item index="macro" @click="phoneNavHidden">
        <i class="el-icon-menu"></i>Macro</el-menu-item>
        <el-menu-item index="schedule" @click="phoneNavHidden">
        <i class="el-icon-menu"></i>Schedule</el-menu-item>
      <el-menu-item index="report" @click="phoneNavHidden">
        <i class="el-icon-menu"></i>Report</el-menu-item>
      <el-submenu index="111" >
         <template slot="title">
          <i class="el-icon-setting"></i>Setting</template>
        <el-submenu index="AddressType">
        <template slot="title">Address Type</template>
        <el-menu-item index="address" @click="phoneNavHidden">Address</el-menu-item>
        <el-menu-item index="floor" @click="phoneNavHidden">Floor</el-menu-item>
        <el-menu-item index="room" @click="phoneNavHidden">Room</el-menu-item>
        </el-submenu>
        <el-submenu index="DeviceType">
          <template slot="title">Device Type</template>
          <el-menu-item index="settingTypeAc" @click="phoneNavHidden">AC</el-menu-item>
          <el-menu-item index="settingTypeLight" @click="phoneNavHidden">Light</el-menu-item>
          <el-menu-item index="settingTypeLed" @click="phoneNavHidden">LED</el-menu-item>
        </el-submenu>
      </el-submenu>
    </el-menu>
  </div>
</template>

<style>
.vertical-menu{
  width: 100%;
}
.vertical-menu i{
  color:#eee;
  z-index: 999
}
.badge-div {
  position: relative;
  width: 100%;
  height: 100%;
}

.badge-div sup {
  position: absolute !important;
  top: 15px !important;
  right: 10px !important;
}
</style>


<script>
export default {
  data() {
    return {
      menuDatas: [
        {
          title: 'Global',
          url: '/home/global',
          name: 'global'
        },
        {
          title: 'Contral',
          url: '/home/contral',
          name: 'contral'
        },
        {
          title: 'plan',
          url: '/home/plan',
          name: 'plan'
        },
        {
          title: 'runing',
          url: '/home/runing',
          name: 'runing'
        },
        {
          title: 'macro',
          url: '/home/macro',
          name: 'macro'
        },
        {
          title: 'schedule',
          url: '/home/schedule',
          name: 'schedule'
        },
        {
          title: 'report',
          url: '/home/report',
          name: 'report'
        },
        {
          title: 'ac',
          url: '/home/setting/type/ac',
          name: 'settingTypeAc'
        },
        {
          title: 'light',
          url: '/home/setting/type/light',
          name: 'settingTypeLight'
        },
        {
          title: 'led',
          url: '/home/setting/type/led',
          name: 'settingTypeLed'
        },
        {
          title: 'address',
          url: '/home/setting/address',
          name: 'address'
        },
        {
          title: 'floor',
          url: '/home/setting/floor',
          name: 'floor'
        },
        {
          title: 'room',
          url: '/home/setting/room',
          name: 'room'
        }

      ],
    }
  },
  props: ['menuData'],
  methods: {
    handleOpen(key, keyPath) {
      // console.log(key, keyPath);
    },
    phoneNavStop(){
      this.$store.dispatch("setShowRightPage", false);
    },
    phoneNavHidden() {
      this.$store.dispatch("setPhoneNav", false);
    },
    handleClose(key, keyPath) {
      // console.log(key, keyPath);
    },
    routerChange(key, keyPath) {
      for (var item of this.menuDatas) {
        if (key == item.name) {
          if (item.url != this.$route.path) {
            router.push(item.url)
          } else {
            _g.shallowRefresh(this.$route.name)
          }
        }
      }

    }
  },
  created() {
    console.log('leftmeun')

  },
  computed: {
    //获取异常设备
    deviceWarn() {
      // var warn = 0
      // for (var device of this.$store.state.devices) {
      //   if (device.on_off == 'on') {
      //     for (var breed of this.$store.state[device.devicetype + "_breed"]) {
      //       var run_time = parseInt(breed.run_time) * 36000
      //       if (device.breed == breed.breed && device.run_time >= run_time) {
      //         warn += 1
      //       }
      //     }
      //   }
      // }
      var warn = this.$store.state.warn
      return warn
    },
  },
}
</script>