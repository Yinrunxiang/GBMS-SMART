const actions = {
  showLeftMenu({ commit }, status) {
    commit("showLeftMenu", status);
  },
  showLoading({ commit }, status) {
    commit("showLoading", status);
  },
  setMenus({ commit }, menus) {
    commit("setMenus", menus);
  },
  setRules({ commit }, rules) {
    commit("setRules", rules);
  },
  setUsers({ commit }, users) {
    commit("setUsers", users);
  },
  setUserGroups({ commit }, userGroups) {
    commit("setUserGroups", userGroups);
  },
  setOrganizes({ commit }, organizes) {
    commit("setOrganizes", organizes);
  },
  setDevice({ commit }, device) {
    commit("setDevice", device);
  },
  setDevices({ commit }, devices) {
    commit("setDevices", devices);
  },
  setAcBreed({ commit }, ac_breed) {
    commit("setAcBreed", ac_breed);
  },
  setLightMode({ commit }, light_mode) {
    commit("setLightMode", light_mode);
  },
  setLedMode({ commit }, led_mode) {
    commit("setLedMode", led_mode);
  }
};

export default actions;
