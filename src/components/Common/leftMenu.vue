<template>
  <div>
    <el-menu default-active="1" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" theme="dark" @select="routerChange">
      <el-menu-item index="global">
        <i class="el-icon-menu"></i>Global</el-menu-item>
      <el-menu-item index="contral">
        <el-badge :value="deviceWarn" class="badge-div">
          <i class="el-icon-menu"></i>Center
        </el-badge>
      </el-menu-item>
      <el-menu-item index="plan">
        <i class="el-icon-menu"></i>Plan</el-menu-item>
      <el-menu-item index="report">
        <i class="el-icon-menu"></i>Report</el-menu-item>
      <el-submenu index="111">
         <template slot="title">
          <i class="el-icon-setting"></i>Setting</template>
        <el-submenu index="AddressType">
        <template slot="title">Address Type</template>
        <el-menu-item index="address">Address</el-menu-item>
        <el-menu-item index="floor">Floor</el-menu-item>
        <el-menu-item index="room">Room</el-menu-item>
        </el-submenu>
        <el-submenu index="DeviceType">
          <template slot="title">Device Type</template>
          <el-menu-item index="settingTypeAc">AC</el-menu-item>
          <el-menu-item index="settingTypeLight">Light</el-menu-item>
          <el-menu-item index="settingTypeLed">LED</el-menu-item>
        </el-submenu>
      </el-submenu>
    </el-menu>
  </div>
</template>

<style>
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