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
  setAcBreed(state, ac_breed) {
    state.ac_breed = ac_breed
  },
  setLightBreed(state, light_breed) {
    state.light_breed = light_breed
  },
  setLedBreed(state, led_breed) {
    state.led_breed = led_breed
  },
  setRecord(state, record) {
    state.record = record
  },
  setCountryArr(state, countryArr) {
    state.countryArr = countryArr
  },
  setAddress(state, address) {
    state.address = address
  }
}

export default mutations
