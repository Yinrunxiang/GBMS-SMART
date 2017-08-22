const mutations = {
  showLeftMenu(state, status) {
    state.showLeftMenu = status
  },
  showLoading(state, status) {
    state.globalLoading = status
  },
  setMenus(state, menus) {
    state.menus = menus
  },
  setRules(state, rules) {
    state.rules = rules
  },
  setUsers(state, users) {
    state.users = users
  },
  setUserGroups(state, userGroups) {
    state.userGroups = userGroups
  },
  setOrganizes(state, organizes) {
    state.organizes = organizes
  },
  setDevice(state, device) {
    state.device = device
  },
  setDevices(state, devices) {
    state.devices = devices
  },
  setAcMode(state, ac_mode) {
    state.ac_mode = ac_mode
  },
  setLightMode(state, light_mode) {
    state.light_mode = light_mode
  },
  setLedMode(state, led_mode) {
    state.led_mode = led_mode
  }
}

export default mutations
