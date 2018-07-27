import api from "../api";
const cutainApi = {
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
        additionalContentData = [device.channel_spare, "64", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    }
  },
  get_stop(val, device) {
    if (device.operation_1 && device.operation_1 != '') {
      var operatorCodefst = "00",
        operatorCodesec = "31",
        additionalContentData = [device.operation_1, "64", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    }
    if (val) {
      var operatorCodefst = "00",
        operatorCodesec = "31",
        additionalContentData = [device.channel, "00", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    } else {
      var operatorCodefst = "00",
        operatorCodesec = "31",
        additionalContentData = [device.channel_spare, "00", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    }
  },
  get_slider_change(val, device) {
    var operatorCodefst = "00",
      operatorCodesec = "31",
      additionalContentData = [device.channel, _g.toHex(val), "00", "00"]
    return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
  },
  //开关
  switch_change(val, device) {
    const data = this.get_switch_change(val, device)
    api.sendUdp(device, data)
  },
  //停止
  stop(val, device) {
    const data = this.get_stop(val, device)
    api.sendUdp(device, data)
  },
  slider_change(val, device) {
    const data = this.get_slider_change(val, device)
    api.sendUdp(device, data)
  },
  closeSocket(){
    // this.socketio.removeAllListeners()
  },
  readStatus(device) {
    var operatorCodefst = "00",
      operatorCodesec = "33",
      additionalContentData = []
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
    // let userInfo = Lockr.get("userInfo");
    // let port = userInfo.port;
    // this.socketio = socket("http://" + document.domain + ":" + port);
    // this.socketio.on("new_msg", function (msg) {
    //   var subnetid = msg.substr(34, 2);
    //   var deviceid = msg.substr(36, 2);
    //   var channel = msg.substr(52, 2);
    //   if (
    //     subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
    //     deviceid.toLowerCase() == device.deviceid.toLowerCase()
    //   ) {
    //     var msg1 = msg.substr(42, 4);
    //     if (msg1 == "0032") {
    //       var channel = msg.substr(50, 2);
    //       if (channel.toLowerCase() == device.channel.toLowerCase()) {
    //         var msg2 = msg.substr(54, 2);
    //         if (msg2 != "00") {
    //           deviceProperty.on_off = true;
    //         } else {
    //           deviceProperty.on_off = false;
    //           deviceProperty.brightness = 0;
    //         }
    //       }
    //     } else if (msg1 == "0034") {
    //       var len = 50 + parseInt("0x" + device.channel) * 2;
    //       var msg2 = msg.substr(len, 2);
    //       var msg3 = parseInt("0x" + msg2);
    //       if (msg2 != "00") {
    //         deviceProperty.on_off = true;
    //         deviceProperty.brightness = msg3;
    //       } else {
    //         deviceProperty.on_off = false;
    //         deviceProperty.brightness = msg3;
    //       }
    //     }
    //   }
    // })
  },
  readOpen(device) {
    var operatorCodefst = "00",
      operatorCodesec = "33",
      additionalContentData = []
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
    // var socket = window.socket("http://" + document.domain + ":2120");
    // window.socketio.removeAllListeners("new_msg");
    // window.socketio.on("new_msg", function (msg) {
    //   var subnetid = msg.substr(34, 2);
    //   var deviceid = msg.substr(36, 2);
    //   var channel = msg.substr(52, 2);
    //   if (
    //     subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
    //     deviceid.toLowerCase() == device.deviceid.toLowerCase()
    //   ) {
    //     var msg1 = msg.substr(42, 4);
    //     if (msg1 == "0032") {
    //       var channel = msg.substr(50, 2);
    //       if (channel.toLowerCase() == device.channel.toLowerCase()) {
    //         var msg2 = msg.substr(54, 2);
    //         if (msg2 != "00") {
    //           device.on_off = true;
    //         } else {
    //           device.on_off = false;
    //         }
    //       }
    //     } else if (msg1 == "0034") {
    //       var len = 50 + parseInt("0x" + device.channel) * 2;
    //       var msg2 = msg.substr(len, 2);
    //       if (msg2 != "00") {
    //         device.on_off = true;
    //       } else {
    //         device.on_off = false;
    //       }
    //     }
    //   }
    // })
  }
}

export default cutainApi;
