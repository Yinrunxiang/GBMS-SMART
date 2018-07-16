import api from "../api";
const lightApi = {
  socketio:{},
  get_switch_change(val, device) {
    if (val) {
      var operatorCodefst = "00",
        operatorCodesec = "31",
        additionalContentData = [device.channel, "64", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    } else {
      var operatorCodefst = "00",
        operatorCodesec = "31",
        additionalContentData = [device.channel, "00", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    }
  },
  get_slider_change(val, device) {
    var operatorCodefst = "00",
      operatorCodesec = "31",
      additionalContentData = [device.channel, _g.toHex(val), "00", "00"]
    return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
  },
  switch_change(val, device) {
    const data = this.get_switch_change(val, device)
    api.sendUdp(device, data)
  },
  slider_change(val, device) {
    const data = this.get_slider_change(val, device)
    api.sendUdp(device, data)
  },
  closeSocket(){
  },
  readStatus(device) {
    var operatorCodefst = "00",
      operatorCodesec = "33",
      additionalContentData = []
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  readOpen(device) {
    var operatorCodefst = "00",
      operatorCodesec = "33",
      additionalContentData = []
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  }
}

export default lightApi;
