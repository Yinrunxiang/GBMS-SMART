import api from "../api";
import $ from "jquery";
const ledApi = {
  socketio:{},
  //开关滑块
  //驱动颜色变化的数据为 255色的16位进制
  //UDP发送的颜色数据为  100色的16位进制
  get_switch_change(val, device) {
    if (val) {
      //255色转100色
      var color = device.deviceProperty.color
      // console.log(color)
      var red = _g.toHex(Math.round(parseInt("0x" + color.substr(1, 2)) / 255 * 100));
      var green = _g.toHex(
        Math.round(parseInt("0x" + color.substr(3, 2)) / 255 * 100)
      );
      var blue = _g.toHex(Math.round(parseInt("0x" + color.substr(5, 2)) / 255 * 100));
      var operatorCodefst = "F0",
        operatorCodesec = "80",
        additionalContentData = [red, green, blue, "00", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    } else {
      var operatorCodefst = "F0",
        operatorCodesec = "80",
        additionalContentData = ["00", "00", "00", "00", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    }
  },
  //当颜色值发生改变时
  get_headleChangeColor(val, device) {
    // var color = val.hex
    // color = color.substring(1)
    // color = _g.strToarr(color)
    var color = device.deviceProperty.color;
    $(".led-light").css("color", color);
    var red = _g.toHex(Math.round(parseInt("0x" + color.substr(1, 2)) / 255 * 100));
    var green = _g.toHex(
      Math.round(parseInt("0x" + color.substr(3, 2)) / 255 * 100)
    );
    var blue = _g.toHex(Math.round(parseInt("0x" + color.substr(5, 2)) / 255 * 100));
    if (device.deviceProperty.on_off) {
      var operatorCodefst = "F0",
        operatorCodesec = "80",
        additionalContentData = [red, green, blue, "00", "00", "00"]
      return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    }
  },
  switch_change(val, device) {
    const data = this.get_switch_change(val, device)
    api.sendUdp(device, data)
  },
  //当颜色值发生改变时
  headleChangeColor(val, device) {
    const data = this.get_headleChangeColor(val, device)
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
    // console.log(device);
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
    //     var tocolor = function (str) {
    //       var col = _g.toHex(Math.round(parseInt("0x" + str) / 100 * 255));
    //       return col;
    //     };
    //     var msg1 = msg.substr(42, 4);
    //     if (msg1 == "f081") {
    //       var red = tocolor(msg.substr(52, 2));
    //       var green = tocolor(msg.substr(54, 2));
    //       var blue = tocolor(msg.substr(56, 2));
    //       var color = "#" + red + green + blue;

    //       if (color != "#000000") {
    //         deviceProperty.on_off = true;
    //         // deviceProperty.red = red;
    //         // deviceProperty.green = green;
    //         // deviceProperty.blue = blue;
    //         deviceProperty.color = color;
    //         $(".led-light").css("color", color);
    //       } else {
    //         deviceProperty.on_off = false;
    //       }
    //     } else if (msg1 == "0034") {
    //       var red = tocolor(msg.substr(52, 2));
    //       var green = tocolor(msg.substr(54, 2));
    //       var blue = tocolor(msg.substr(56, 2));
    //       var color = "#" + red + green + blue;

    //       if (color != "#000000") {
    //         deviceProperty.on_off = true;
    //         // deviceProperty.red = red;
    //         // deviceProperty.green = green;
    //         // deviceProperty.blue = blue;
    //         deviceProperty.color = color;
    //         $(".led-light").css("color", color);
    //       } else {
    //         deviceProperty.on_off = false;
    //       }
    //     }
    //   }
    // });
  },
  readOpen(device) {
    var operatorCodefst = "00",
      operatorCodesec = "33",
      additionalContentData = []
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
    // window.socketio.on("new_msg", function (msg) {
    //   var subnetid = msg.substr(34, 2);
    //   var deviceid = msg.substr(36, 2);
    //   var channel = msg.substr(52, 2);
    //   if (
    //     subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
    //     deviceid.toLowerCase() == device.deviceid.toLowerCase()
    //   ) {
    //     var tocolor = function (str) {
    //       var col = _g.toHex(Math.round(parseInt("0x" + str) / 100 * 255));
    //       return col;
    //     };
    //     var msg1 = msg.substr(42, 4);
    //     if (msg1 == "f081") {
    //       var red = tocolor(msg.substr(52, 2));
    //       var green = tocolor(msg.substr(54, 2));
    //       var blue = tocolor(msg.substr(56, 2));
    //       var color = "#" + red + green + blue;

    //       if (color != "#000000") {
    //         device.on_off = true;
    //       } else {
    //         device.on_off = false;
    //       }
    //     } else if (msg1 == "0034") {
    //       var red = tocolor(msg.substr(52, 2));
    //       var green = tocolor(msg.substr(54, 2));
    //       var blue = tocolor(msg.substr(56, 2));
    //       var color = "#" + red + green + blue;

    //       if (color != "#000000") {
    //         device.on_off = true;
    //       } else {
    //         device.on_off = false;
    //       }
    //     }
    //   }
    // })
  }
}

export default ledApi;
