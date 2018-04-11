import api from "../api";
const lightApi = {
  switch_change(val, device) {
    if (val) {
      if (device.operation_6) {
        var operatorCodefst = "00",
          operatorCodesec = "31",
          additionalContentData = [_g.toHex(device.operation_6), "64"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
      }
    } else {
      if (device.operation_6) {
        var operatorCodefst = "00",
          operatorCodesec = "31",
          additionalContentData = [_g.toHex(device.operation_7), "64"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
      }
    }
  },
  buttonClick(device, value) {
    var operatorCodefst = "00",
      operatorCodesec = "31",
      additionalContentData = [_g.toHex(value), "64"]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  }
}

export default lightApi;
