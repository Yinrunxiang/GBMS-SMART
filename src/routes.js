//异步懒加载
const refresh = () =>  import("./components/refresh.vue");
const Login = () =>  import("./components/Account/Login.vue");
const Home = () =>  import("./components/Home.vue");
const global = () =>  import("./components/content/global.vue");
const hotel = () =>  import("./components/content/global/hotel.vue");
const floor = () =>  import("./components/content/global/floor.vue");
const room = () =>  import("./components/content/global/room.vue");
const plan = () =>  import("./components/content/plan.vue");
const macro = () =>  import("./components/content/macro.vue");
const schedule = () =>  import("./components/content/schedule.vue");
const report = () =>  import("./components/content/report.vue");
const contral = () =>  import("./components/content/contral.vue");
const light = () =>  import("./components/content/devices/light.vue");
const ac = () =>  import("./components/content/devices/ac.vue");
const music = () =>  import("./components/content/devices/music.vue");
const led = () =>  import("./components/content/devices/led.vue");
const curtain = () =>  import("./components/content/devices/curtain.vue");
const ir = () =>  import("./components/content/devices/ir.vue");
const planUpdate = () =>  import("./components/content/plan/update.vue");
const macroAdd = () =>  import("./components/content/macro/add.vue");
const scheduleAdd = () =>  import("./components/content/schedule/add.vue");
const settingTypeAc = () =>  import("./components/content/setting/type/ac/ac.vue");
const settingTypeAcAdd = () =>  import("./components/content/setting/type/ac/add.vue");
const settingTypeAcUpdate = () =>  import("./components/content/setting/type/ac/update.vue");
const settingTypeLight = () =>  import("./components/content/setting/type/light/light.vue");
const settingTypeLightAdd = () =>  import("./components/content/setting/type/light/add.vue");
const settingTypeLightUpdate = () =>  import("./components/content/setting/type/light/update.vue");
const settingTypeLed = () =>  import("./components/content/setting/type/led/led.vue");
const settingTypeLedAdd = () =>  import("./components/content/setting/type/led/add.vue");
const settingTypeLedUpdate = () =>  import("./components/content/setting/type/led/update.vue");
const address = () =>  import("./components/content/setting/address/address.vue");
const addressAdd = () =>  import("./components/content/setting/address/add.vue");
const addressUpdate = () =>  import("./components/content/setting/address/update.vue");
const floorSetting = () =>  import("./components/content/setting/floor/floor.vue");
const floorAdd = () =>  import("./components/content/setting/floor/add.vue");
const roomSetting = () =>  import("./components/content/setting/room/room.vue");
const roomAdd = () =>  import("./components/content/setting/room/add.vue");
// import refresh from "./components/refresh.vue";
// import Login from './components/Account/Login.vue'
// import Home from "./components/Home.vue";
// import global from "./components/content/global.vue";
// import hotel from "./components/content/global/hotel.vue";
// import floor from "./components/content/global/floor.vue";
// import room from "./components/content/global/room.vue";
// import plan from "./components/content/plan.vue";
// import macro from "./components/content/macro.vue";
// import schedule from "./components/content/schedule.vue";
// import report from "./components/content/report.vue";
// import contral from "./components/content/contral.vue";
// import light from "./components/content/devices/light.vue";
// import ac from "./components/content/devices/ac.vue";
// import music from "./components/content/devices/music.vue";
// import led from "./components/content/devices/led.vue";
// import curtain from "./components/content/devices/curtain.vue";
// import ir from "./components/content/devices/ir.vue";
// import planUpdate from "./components/content/plan/update.vue";
// import macroAdd from "./components/content/macro/add.vue";
// import scheduleAdd from "./components/content/schedule/add.vue";
// import settingTypeAc from "./components/content/setting/type/ac/ac.vue";
// import settingTypeAcAdd from "./components/content/setting/type/ac/add.vue";
// import settingTypeAcUpdate from "./components/content/setting/type/ac/update.vue";
// import settingTypeLight from "./components/content/setting/type/light/light.vue";
// import settingTypeLightAdd from "./components/content/setting/type/light/add.vue";
// import settingTypeLightUpdate from "./components/content/setting/type/light/update.vue";
// import settingTypeLed from "./components/content/setting/type/led/led.vue";
// import settingTypeLedAdd from "./components/content/setting/type/led/add.vue";
// import settingTypeLedUpdate from "./components/content/setting/type/led/update.vue";
// import address from "./components/content/setting/address/address.vue";
// import addressAdd from "./components/content/setting/address/add.vue";
// import addressUpdate from "./components/content/setting/address/update.vue";
// import floorSetting from "./components/content/setting/floor/floor.vue";
// import floorAdd from "./components/content/setting/floor/add.vue";
// import roomSetting from "./components/content/setting/room/room.vue";
// import roomAdd from "./components/content/setting/room/add.vue";
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
      { path: "macro", component: macro, name: "macro" },
      // { path: "plan/add", component: planAdd, name: "planAdd" },
      { path: "macro/add", component: macroAdd, name: "macroAdd" },
    ]
  },
  {
    path: "/home",
    component: Home,
    children: [
      { path: "schedule", component: schedule, name: "schedule" },
      // { path: "plan/add", component: planAdd, name: "planAdd" },
      { path: "schedule/add", component: scheduleAdd, name: "scheduleAdd" },
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
