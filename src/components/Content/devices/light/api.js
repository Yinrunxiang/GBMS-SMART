import api from "../../../../assets/js/api";
const lightApi = {
  get_switch_change(val, device, deviceProperty) {
    if (val) {
      deviceProperty.brightness = 100;
      var operatorCodefst = "00",
        operatorCodesec = "31",
        additionalContentData = [device.channel, "64", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    } else {
      deviceProperty.brightness == 0;
      var operatorCodefst = "00",
        operatorCodesec = "31",
        additionalContentData = [device.channel, "00", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    }
  },
  get_slider_change(val, device, deviceProperty) {
    var operatorCodefst = "00",
      operatorCodesec = "31",
      additionalContentData = [device.channel, _g.toHex(val), "00", "00"]
    return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
  },
  switch_change(val, device, deviceProperty) {
    const data = this.get_switch_change(val, device, deviceProperty)
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
  },
  slider_change(val, device, deviceProperty) {
    const data = this.get_slider_change(val, device, deviceProperty)
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
  },
  readStatus(device, deviceProperty) {
    var operatorCodefst = "00",
      operatorCodesec = "33",
      additionalContentData = []
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    // console.log(device);
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
    // var socket = window.socket("http://" + document.domain + ":2120");
    // window.socketio.removeAllListeners("new_msg");
    window.socketio.on("new_msg", function (msg) {
      var subnetid = msg.substr(34, 2);
      var deviceid = msg.substr(36, 2);
      var channel = msg.substr(52, 2);
      if (
        subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
        deviceid.toLowerCase() == device.deviceid.toLowerCase()
      ) {
        var msg1 = msg.substr(42, 4);
        switch (msg1) {
          case "0032":
            var channel = msg.substr(50, 2);
            if (channel.toLowerCase() == device.channel.toLowerCase()) {
              var msg2 = msg.substr(54, 2);
              if (msg2 != "00") {
                deviceProperty.on_off = true;
              } else {
                deviceProperty.on_off = false;
                deviceProperty.brightness = 0;
              }
            }
            break
          case "0034":
            var channel = device.channel
            switch (channel) {
              case "31":
                channel = "01"
                break
              case "32":
                channel = "02"
                break
              case "33":
                channel = "03"
                break
              case "34":
                channel = "04"
                break
            }
            var len = 50 + parseInt("0x" + channel) * 2;
            var msg2 = msg.substr(len, 2);
            var msg3 = parseInt("0x" + msg2);
            if (msg2 != "00") {
              deviceProperty.on_off = true;
              deviceProperty.brightness = msg3;
            } else {
              deviceProperty.on_off = false;
              deviceProperty.brightness = msg3;
            }
            break
          case "f081":
            var red = msg.substr(52, 2);
            var green = msg.substr(54, 2);
            var blue = msg.substr(56, 2);
            var mix = msg.substr(58, 2);
            var brightness = "00"
            switch (device.channel) {
              case "31":
                brightness = parseInt("0x" + red)
                break
              case "32":
                brightness = parseInt("0x" + green)
                break
              case "33":
                brightness = parseInt("0x" + blue)
                break
              case "34":
                brightness = parseInt("0x" + mix)
                break
            }
            if (brightness != "00") {
              deviceProperty.on_off = true;
              deviceProperty.brightness = brightness;
            } else {
              deviceProperty.on_off = false;
              deviceProperty.brightness = brightness;
            }
            break
        }
      }
    })
  },
  readOpen(device) {
    var operatorCodefst = "00",
      operatorCodesec = "33",
      additionalContentData = []
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    // console.log(device);
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
    // var socket = window.socket("http://" + document.domain + ":2120");
    // window.socketio.removeAllListeners("new_msg");
    window.socketio.on("new_msg", function (msg) {
      var subnetid = msg.substr(34, 2);
      var deviceid = msg.substr(36, 2);
      var channel = msg.substr(52, 2);
      if (
        subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
        deviceid.toLowerCase() == device.deviceid.toLowerCase()
      ) {
        var msg1 = msg.substr(42, 4);
        switch (msg1) {
          case "0032":
            var channel = msg.substr(50, 2);
            if (channel.toLowerCase() == device.channel.toLowerCase()) {
              var msg2 = msg.substr(54, 2);
              if (msg2 != "00") {
                device.on_off = true;
              } else {
                device.on_off = false;
              }
            }
            break
          case "0034":
            var channel = device.channel
            switch (channel) {
              case "31":
                channel = "01"
                break
              case "32":
                channel = "02"
                break
              case "33":
                channel = "03"
                break
              case "34":
                channel = "04"
                break
            }
            var len = 50 + parseInt("0x" + channel) * 2;
            var msg2 = msg.substr(len, 2);
            if (msg2 != "00") {
              device.on_off = true;
            } else {
              device.on_off = false;
            }
            break
          case "f081":
            var red = msg.substr(52, 2);
            var green = msg.substr(54, 2);
            var blue = msg.substr(56, 2);
            var mix = msg.substr(58, 2);
            var brightness = "00"
            switch (device.channel) {
              case "31":
                brightness = parseInt("0x" + red)
                break
              case "32":
                brightness = parseInt("0x" + green)
                break
              case "33":
                brightness = parseInt("0x" + blue)
                break
              case "34":
                brightness = parseInt("0x" + mix)
                break
            }
            if (brightness != "00") {
              device.on_off = true;
            } else {
              device.on_off = false;
            }
            break
        }
      }
    })
  }
}

export default lightApi;
