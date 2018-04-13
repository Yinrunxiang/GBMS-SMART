//异步懒加载
const refresh = require("./components/refresh.vue");
const Login = require("./components/Account/Login.vue");
const Home = require("./components/Home.vue");
const global = require("./components/Content/global");
const hotel = require("./components/Content/global/hotel.vue");
const plan = require("./components/Content/plan");
const runing = require("./components/Content/runing");
const macro = require("./components/Content/macro");
const schedule = require("./components/Content/schedule");
const report = require("./components/Content/report");
const contral = require("./components/Content/contral");
const planUpdate = require("./components/Content/plan/update.vue");
const macroAdd = require("./components/Content/macro/add.vue");
const scheduleAdd = require("./components/Content/schedule/add.vue");
const settingTypeAc = require("./components/Content/setting/type/ac");
const settingTypeAcAdd = require("./components/Content/setting/type/ac/add.vue");
const settingTypeAcUpdate = require("./components/Content/setting/type/ac/update.vue");
const settingTypeLight = require("./components/Content/setting/type/light");
const settingTypeLightAdd = require("./components/Content/setting/type/light/add.vue");
const settingTypeLightUpdate = require("./components/Content/setting/type/light/update.vue");
const settingTypeLed = require("./components/Content/setting/type/led");
const settingTypeLedAdd = require("./components/Content/setting/type/led/add.vue");
const settingTypeLedUpdate = require("./components/Content/setting/type/led/update.vue");
const address = require("./components/Content/setting/address");
const addressAdd = require("./components/Content/setting/address/add.vue");
const addressUpdate = require("./components/Content/setting/address/update.vue");
const floorSetting = require("./components/Content/setting/floor");
const floorAdd = require("./components/Content/setting/floor/add.vue");
const roomSetting = require("./components/Content/setting/room");
const roomAdd = require("./components/Content/setting/room/add.vue");
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
      { path: "global/hotel", component: hotel, name: "hotel" },]

  },
  {
    path: "/home",
    component: Home,
    children: [
      {
        path: "contral",
        component: contral,
        name: "contral",
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
      { path: "runing", component: runing, name: "runing" },
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
