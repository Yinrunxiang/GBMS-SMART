<template>
  <div>
    <el-menu default-active="1" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" theme="dark" @select="routerChange">
      <el-menu-item index="global">
        <i class="el-icon-menu"></i>Global</el-menu-item>
      <el-menu-item index="contral">
        <i class="el-icon-menu"></i>Contral</el-menu-item>
      <!-- <el-submenu index="11">
        <template slot="title">
          <i class="el-icon-menu"></i>Contral</template>
        <el-submenu :index="country.name" v-for="country in countryArr">
          <template slot="title">
            <i class="el-icon-menu"></i>{{country.name}}</template>
          <el-submenu :index="devicetype.name" v-for="devicetype in country.typeList">
            <template slot="title">
              <i class="el-icon-menu"></i>{{devicetype.name}}</template>
            <el-menu-item :index="device.device" v-for="device in devicetype.deviceList"><i class="el-icon-menu"></i>{{device.device}}</el-menu-item>
          </el-submenu>
        </el-submenu>
      </el-submenu> -->
      <el-menu-item index="plan">
        <i class="el-icon-menu"></i>Plan</el-menu-item>
      <el-menu-item index="report">
        <i class="el-icon-menu"></i>Report</el-menu-item>
      <el-submenu index="111">
        <template slot="title">
          <i class="el-icon-setting"></i>Setting</template>
          <el-menu-item index="address">Address</el-menu-item>
        <el-submenu index="222">
          <template slot="title">Device Type</template>
          <el-menu-item index="settingTypeAc">AC</el-menu-item>
          <el-menu-item index="settingTypeLight">Light</el-menu-item>
          <el-menu-item index="settingTypeLed">LED</el-menu-item>
        </el-submenu>
      </el-submenu>
    </el-menu>
  </div>
</template>

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
    countryArr() {
      return this.$store.state.countryArr
    }
  }
}
</script>