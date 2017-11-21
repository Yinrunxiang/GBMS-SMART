import refresh from "./components/refresh.vue";
import Login from './components/Account/Login.vue'
import Home from "./components/Home.vue";
import global from "./components/content/global.vue";
import hotel from "./components/content/global/hotel.vue";
import floor from "./components/content/global/floor.vue";
import room from "./components/content/global/room.vue";
import plan from "./components/content/plan.vue";
import report from "./components/content/report.vue";
import contral from "./components/content/contral.vue";
import light from "./components/content/devices/light.vue";
import ac from "./components/content/devices/ac.vue";
import music from "./components/content/devices/music.vue";
import led from "./components/content/devices/led.vue";
import curtain from "./components/content/devices/curtain.vue";
import ir from "./components/content/devices/ir.vue";
// import planAdd from "./components/content/plan/add.vue";
import planUpdate from "./components/content/plan/update.vue";
import settingTypeAc from "./components/content/setting/type/ac/ac.vue";
import settingTypeAcAdd from "./components/content/setting/type/ac/add.vue";
import settingTypeAcUpdate from "./components/content/setting/type/ac/update.vue";
import settingTypeLight from "./components/content/setting/type/light/light.vue";
import settingTypeLightAdd from "./components/content/setting/type/light/add.vue";
import settingTypeLightUpdate from "./components/content/setting/type/light/update.vue";
import settingTypeLed from "./components/content/setting/type/led/led.vue";
import settingTypeLedAdd from "./components/content/setting/type/led/add.vue";
import settingTypeLedUpdate from "./components/content/setting/type/led/update.vue";
import address from "./components/content/setting/address/address.vue";
import addressAdd from "./components/content/setting/address/add.vue";
import addressUpdate from "./components/content/setting/address/update.vue";
import floorSetting from "./components/content/setting/floor/floor.vue";
import floorAdd from "./components/content/setting/floor/add.vue";
import roomSetting from "./components/content/setting/room/room.vue";
import roomAdd from "./components/content/setting/room/add.vue";
/**
 * meta参数解析
 * hideLeft: 是否隐藏左侧菜单，单页菜单为true
 * module: 菜单所属模块
 * menu: 所属菜单，用于判断三级菜单是否显示高亮，如菜单列表、添加菜单、编辑菜单都是'menu'，用户列表、添加用户、编辑用户都是'user'，如此类推
 */

const routes = [
  { path: '/', component: Login, name: 'Login' },
  {
    path: "/home",
    component: Home,
    children: [
      { path: "refresh", component: refresh, name: "refresh" },
      { path: "global", component: global, name: "global" },
      { path: "global/hotel", component: hotel, name: "hotel" },
      { path: "global/floor", component: floor, name: "floor" },
      { path: "global/room", component: room, name: "room" },]

  },
  {
    path: "/home",
    component: Home,
    children: [
      {
        path: "contral",
        component: contral,
        name: "contral",
        children: [
          {path: "light", component: light, name: "light" },
          {path: "ac", component: ac, name: "ac" },
          {path: "music", component: music, name: "music" },
          {path: "led", component: led, name: "led" },
          {path: "curtain", component: curtain, name: "curtain" },
          {path: "ir", component: ir, name: "ir" },
        ]
      }
    ]
  },
  {
    path: "/home",
    component: Home,
    children: [
      { path: "plan", component: plan, name: "plan" },
      // { path: "plan/add", component: planAdd, name: "planAdd" },
      { path: "plan/update", component: planUpdate, name: "planUpdate" },
    ]
  },
  {
    path: "/home",
    component: Home,
    children: [
      { path: "report", component: report, name: "report" }
    ]
  },
  {
    path: "/home",
    component: Home,
    children: [
      { path: "setting/type/ac", component: settingTypeAc, name: "settingTypeAc" },
      { path: "setting/type/ac/add", component: settingTypeAcAdd, name: "settingTypeAcAdd" },
      { path: "setting/type/ac/update", component: settingTypeAcUpdate, name: "settingTypeAcUpdate" },
      { path: "setting/type/light", component: settingTypeLight, name: "settingTypeLight" },
      { path: "setting/type/light/add", component: settingTypeLightAdd, name: "settingTypeLightAdd" },
      { path: "setting/type/light/update", component: settingTypeLightUpdate, name: "settingTypeLightUpdate" },
      { path: "setting/type/led", component: settingTypeLed, name: "settingTypeLed" },
      { path: "setting/type/led/add", component: settingTypeLedAdd, name: "settingTypeLedAdd" },
      { path: "setting/type/led/update", component: settingTypeLedUpdate, name: "settingTypeLedUpdate" },
      { path: "setting/address", component: address, name: "address" },
      { path: "setting/address/add", component: addressAdd, name: "addressAdd" },
      { path: "setting/address/update", component: addressUpdate, name: "addressUpdate" },
      { path: "setting/floor", component: floorSetting, name: "floorSetting" },
      { path: "setting/floor/add", component: floorAdd, name: "floorAdd" },
      { path: "setting/room", component: roomSetting, name: "roomSetting" },
      { path: "setting/room/add", component: roomAdd, name: "roomAdd" },
    ]
  },
  { path: "/", redirect: { name: "global" } }
];
export default routes;
