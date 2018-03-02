import api from "../../../../assets/js/api";
import $ from "jquery";
const ledApi = {
  //开关滑块
  //驱动颜色变化的数据为 255色的16位进制
  //UDP发送的颜色数据为  100色的16位进制
  get_switch_change(val, device, deviceProperty) {
    if (val) {
      //255色转100色
      var color = deviceProperty.color
      var red = _g.toHex(Math.round(parseInt("0x" + color.substr(1, 2)) / 255 * 100));
      var green = _g.toHex(
        Math.round(parseInt("0x" + color.substr(3, 2)) / 255 * 100)
      );
      var blue = _g.toHex(Math.round(parseInt("0x" + color.substr(5, 2)) / 255 * 100));
      const data = {
        params: {
          operatorCodefst: "F0",
          operatorCodesec: "80",
          targetSubnetID: device.subnetid,
          targetDeviceID: device.deviceid,
          additionalContentData: (red +
            "," +
            green +
            "," +
            blue +
            ",00,00,00").split(","),
          macAddress: device.mac ? device.mac.split(".") : "",
          dest_address: device.ip ? device.ip : "",
          dest_port: device.port ? device.port : ""
        }
      };
      return data
    } else {
      const data = {
        params: {
          operatorCodefst: "F0",
          operatorCodesec: "80",
          targetSubnetID: device.subnetid,
          targetDeviceID: device.deviceid,
          additionalContentData: "00,00,00,00,00,00".split(","),
          macAddress: device.mac ? device.mac.split(".") : "",
          dest_address: device.ip ? device.ip : "",
          dest_port: device.port ? device.port : ""
        }
      };
      return data
    }
  },
  //当颜色值发生改变时
  get_headleChangeColor(val, device, deviceProperty) {
    // var color = val.hex
    // color = color.substring(1)
    // color = _g.strToarr(color)
    var color = deviceProperty.color;
    $(".led-light").css("color", color);
    var red = _g.toHex(Math.round(parseInt("0x" + color.substr(1, 2)) / 255 * 100));
    var green = _g.toHex(
      Math.round(parseInt("0x" + color.substr(3, 2)) / 255 * 100)
    );
    var blue = _g.toHex(Math.round(parseInt("0x" + color.substr(5, 2)) / 255 * 100));
    if (deviceProperty.on_off) {
      const data = {
        params: {
          operatorCodefst: "F0",
          operatorCodesec: "80",
          targetSubnetID: device.subnetid,
          targetDeviceID: device.deviceid,
          additionalContentData: (red +
            "," +
            green +
            "," +
            blue +
            ",00,00,00").split(","),
          // additionalContentData: ("64,64,64,00,00,00").split(","),
          macAddress: device.mac ? device.mac.split(".") : "",
          dest_address: device.ip ? device.ip : "",
          dest_port: device.port ? device.port : ""
        }
      };
      return data
    }
  },
  switch_change(val, device, deviceProperty) {
    const data = this.get_switch_change(val, device, deviceProperty)
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()

    })
  },
  //当颜色值发生改变时
  headleChangeColor(val, device, deviceProperty) {
    const data = this.get_headleChangeColor(val, device, deviceProperty)
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
  },
  readStatus(device, deviceProperty) {
    let data = {
      params: {
        operatorCodefst: "00",
        operatorCodesec: "33",
        targetSubnetID: device.subnetid,
        targetDeviceID: device.deviceid,
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
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
        var tocolor = function (str) {
          var col = _g.toHex(Math.round(parseInt("0x" + str) / 100 * 255));
          return col;
        };
        var msg1 = msg.substr(42, 4);
        if (msg1 == "f081") {
          var red = tocolor(msg.substr(52, 2));
          var green = tocolor(msg.substr(54, 2));
          var blue = tocolor(msg.substr(56, 2));
          var color = "#" + red + green + blue;

          if (color != "#000000") {
            deviceProperty.on_off = true;
            // deviceProperty.red = red;
            // deviceProperty.green = green;
            // deviceProperty.blue = blue;
            deviceProperty.color = color;
            $(".led-light").css("color", color);
          } else {
            deviceProperty.on_off = false;
          }
        } else if (msg1 == "0034") {
          var red = tocolor(msg.substr(52, 2));
          var green = tocolor(msg.substr(54, 2));
          var blue = tocolor(msg.substr(56, 2));
          var color = "#" + red + green + blue;

          if (color != "#000000") {
            deviceProperty.on_off = true;
            // deviceProperty.red = red;
            // deviceProperty.green = green;
            // deviceProperty.blue = blue;
            deviceProperty.color = color;
            $(".led-light").css("color", color);
          } else {
            deviceProperty.on_off = false;
          }
        }
      }
    });
  },
  readOpen(device) {
    let data = {
      params: {
        operatorCodefst: "00",
        operatorCodesec: "33",
        targetSubnetID: device.subnetid,
        targetDeviceID: device.deviceid,
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
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
        var tocolor = function (str) {
          var col = _g.toHex(Math.round(parseInt("0x" + str) / 100 * 255));
          return col;
        };
        var msg1 = msg.substr(42, 4);
        if (msg1 == "f081") {
          var red = tocolor(msg.substr(52, 2));
          var green = tocolor(msg.substr(54, 2));
          var blue = tocolor(msg.substr(56, 2));
          var color = "#" + red + green + blue;

          if (color != "#000000") {
            device.on_off = true;
          } else {
            device.on_off = false;
          }
        } else if (msg1 == "0034") {
          var red = tocolor(msg.substr(52, 2));
          var green = tocolor(msg.substr(54, 2));
          var blue = tocolor(msg.substr(56, 2));
          var color = "#" + red + green + blue;

          if (color != "#000000") {
            device.on_off = true;
          } else {
            device.on_off = false;
          }
        }
      }
    })
  }
}

export default ledApi;
