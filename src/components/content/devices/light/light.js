import api from "../../../../assets/js/api";
const lightApi = {
  switch_change(val,brightness,device) {
    if (val) {
      if (brightness == 0) brightness = 100;
      loading = true;
      const data = {
        params: {
          operatorCodefst: "00",
          operatorCodesec: "31",
          targetDeviceID: device.deviceid,
          additionalContentData: (device.channel +
            "," +
            _g.toHex(brightness) +
            ",00,00").split(","),
          macAddress: device.mac ? device.mac.split(".") : "",
          dest_address: device.ip ? device.ip : "",
          dest_port: device.port ? device.port : ""
        }
      };
      api.apiGet("udp/sendUdp.php", data).then(res => {
        console.log("res = ", _g.j2s(res));
        // _g.closeGlobalLoading()
      });
    } else {
      brightness == 0;
      const data = {
        params: {
          operatorCodefst: "00",
          operatorCodesec: "31",
          targetDeviceID: device.deviceid,
          additionalContentData: (device.channel + ",00,00,00").split(","),
          macAddress: device.mac ? device.mac.split(".") : "",
          dest_address: device.ip ? device.ip : "",
          dest_port: device.port ? device.port : ""
        }
      };
      api.apiGet("udp/sendUdp.php", data).then(res => {
        console.log("res = ", _g.j2s(res));
        // _g.closeGlobalLoading()
      });
    }
  },
  slider_change(val,brightness,device) {
    brightness = val;
    const data = {
      params: {
        operatorCodefst: "00",
        operatorCodesec: "31",
        targetDeviceID: device.deviceid,
        additionalContentData: (device.channel +
          "," +
          _g.toHex(val) +
          ",00,00").split(","),
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    api.apiGet("udp/sendUdp.php", data).then(res => {
      console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
  },
  readStatus(on_off,brightness,device) {
    let data = {
      params: {
        operatorCodefst: "00",
        operatorCodesec: "33",
        targetDeviceID: device.deviceid,
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    console.log(device);
    api.apiGet("udp/sendUdp.php", data).then(res => {
      console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
    // var socket = window.socket("http://" + document.domain + ":2120");
    window.socketio.removeAllListeners("new_msg");
    window.socketio.on("new_msg", function(msg) {
      var subnetid = msg.substr(34, 2);
      var deviceid = msg.substr(36, 2);
      var channel = msg.substr(52, 2);
      if (
        subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
        deviceid.toLowerCase() == device.deviceid.toLowerCase()
      ) {
        var msg1 = msg.substr(42, 4);
        if (msg1 == "0032") {
          var channel = msg.substr(50, 2);
          if (channel.toLowerCase() == device.channel.toLowerCase()) {
            var msg2 = msg.substr(54, 2);
            if (msg2 != "00") {
              on_off = true;
            } else {
              on_off = false;
              brightness = 0;
            }
          }
        } else if (msg1 == "0034") {
          var len = 50 + parseInt("0x" + device.channel) * 2;
          var msg2 = msg.substr(len, 2);
          var msg3 = parseInt("0x" + msg2);
          if (msg2 != "00") {
            on_off = true;
            brightness = msg3;
          } else {
            on_off = false;
            brightness = msg3;
          }
        }
      }
    })
  },
  readOpen(on_off,device) {
    let data = {
      params: {
        operatorCodefst: "00",
        operatorCodesec: "33",
        targetDeviceID: device.deviceid,
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    console.log(device);
    api.apiGet("udp/sendUdp.php", data).then(res => {
      console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
    // var socket = window.socket("http://" + document.domain + ":2120");
    window.socketio.removeAllListeners("new_msg");
    window.socketio.on("new_msg", function(msg) {
      var subnetid = msg.substr(34, 2);
      var deviceid = msg.substr(36, 2);
      var channel = msg.substr(52, 2);
      if (
        subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
        deviceid.toLowerCase() == device.deviceid.toLowerCase()
      ) {
        var msg1 = msg.substr(42, 4);
        if (msg1 == "0032") {
          var channel = msg.substr(50, 2);
          if (channel.toLowerCase() == device.channel.toLowerCase()) {
            var msg2 = msg.substr(54, 2);
            if (msg2 != "00") {
              on_off = true;
            } else {
              on_off = false;
            }
          }
        } else if (msg1 == "0034") {
          var len = 50 + parseInt("0x" + device.channel) * 2;
          var msg2 = msg.substr(len, 2);
          if (msg2 != "00") {
            on_off = true;
          } else {
            on_off = false;
          }
        }
      }
    })
  }
}

export default lightApi;
