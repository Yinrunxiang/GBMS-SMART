import ac from "../../components/Content/devices/ac/api";
import light from "../../components/Content/devices/light/api";
import led from "../../components/Content/devices/led/api";
import curtain from "../../components/Content/devices/curtain/api";
import music from "../../components/Content/devices/music/api";
const api = {
  apiPost(url, data) {
    return new Promise((resolve, reject) => {
      axios.get(url, data).then((response) => {
        resolve(response.data)
      }, (response) => {
        reject(response)
        _g.closeGlobalLoading()
        // bus.$message({
        //   message: 'Request timeout, please check the network',
        //   type: 'warning'
        // })
      })
    })
  },
  //UDP重发机制
  sendUdp(device, data, type) {
    if (!type || type == "") {
      this.apiPost("admin/udp/sendUdp", data).then(res => {
      });
    } else {
      var pass = false
      var index = 1
      var operatorCode = data.operatorCodefst + data.operatorCodesec
      operatorCode = _g.toHex(parseInt('0x' + operatorCode) + 1)
      if (operatorCode.length < 4) {
        var zero = ""
        for (var i = 0; i < 4 - operatorCode.length; i++) {
          zero = zero + '0'
        }
        operatorCode = zero + operatorCode
      }
      window.socketio.on("new_msg", function (msg) {
        var subnetid = msg.substr(34, 2);
        var deviceid = msg.substr(36, 2);
        if (
          subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
          deviceid.toLowerCase() != device.deviceid.toLowerCase()
        ) return
        var operatorCodeCurrent = msg.substr(42, 4)
        if (operatorCodeCurrent != operatorCode) return
        pass = true
      })
      var $this = this
      var sendUdp = setInterval(function () {
        if (pass || index > 3) {
          clearInterval(sendUdp);
          return;
        }
        $this.apiPost("admin/udp/sendUdp", data).then(res => {
        });
        index++
      }, 100);
    }

  },
  //发送UDP列表
  sendUdpArr(arr) {
    console.log('sendUdpArr')
    var $this = this
    var arrIndex = 0
    var sendUdp = function (device, data, type) {
      if (!type || type == "") {
        this.apiPost("admin/udp/sendUdp", data).then(res => {
        });
      } else {
        var pass = false
        var index = 1
        var operatorCode = data.operatorCodefst + data.operatorCodesec
        operatorCode = _g.toHex(parseInt('0x' + operatorCode) + 1)
        if (operatorCode.length < 4) {
          var zero = ""
          for (var i = 0; i < 4 - operatorCode.length; i++) {
            zero = zero + '0'
          }
          operatorCode = zero + operatorCode
        }
        window.socketio.on("new_msg", function (msg) {
          var subnetid = msg.substr(34, 2);
          var deviceid = msg.substr(36, 2);
          if (
            subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
            deviceid.toLowerCase() != device.deviceid.toLowerCase()
          ) return
          var operatorCodeCurrent = msg.substr(42, 4)
          if (operatorCodeCurrent != operatorCode) return
          pass = true
        })
        
        var sendUdpFor = setInterval(function () {
          if (pass || index > 3) {
            clearInterval(sendUdpFor);
            arrIndex++
            if (arr[arrIndex]) {
              sendUdp(arr[arrIndex].device, arr[arrIndex].data, 1)
            }
            return;
          }
          $this.apiPost("admin/udp/sendUdp", data).then(res => {
          });
          index++
        }, 100);
      }

    }
    sendUdp(arr[arrIndex].device, arr[arrIndex].data, 1)
  },
  //返回要发送的UDP数据
  getUdp(device, operatorCodefst, operatorCodesec, additionalContentData) {
    const data = {
      params: {
        operatorCodefst: operatorCodefst,
        operatorCodesec: operatorCodesec,
        targetSubnetID: device.subnetid,
        targetDeviceID: device.deviceid,
        additionalContentData: additionalContentData,
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : "",
        udp_type: device.udp_type ? device.udp_type : ""
      }
    }
    return data
  },
  //返回要发的UDP数据列表
  getUdpArr(deviceList){
    var udpArr = [];
      for (var device of deviceList) {
        switch (device.devicetype) {
          case "ac":
            if (device.on_off == "1") {
              udpArr.push({
                device: device,
                data: ac.get_switch_change(true, device)
              });
              switch (device.mode) {
                case "cool":
                  udpArr.push({
                    device: device,
                    data: ac.get_coolbtn(device)
                  });
                  udpArr.push({
                    device: device,
                    data: ac.get_cooltmp_change(device.status_1, device)
                  });
                  break;
                case "fan":
                  udpArr.push({
                    device: device,
                    data: ac.get_fanbtn(device)
                  });
                  udpArr.push({
                    device: device,
                    data: ac.get_cooltmp_change(device.status_1, device)
                  });
                  break;
                case "heat":
                  udpArr.push({
                    device: device,
                    data: ac.get_heatbtn(device)
                  });
                  udpArr.push({
                    device: device,
                    data: ac.get_heattmp_change(device.status_1, device)
                  });
                  break;
                case "auto":
                  udpArr.push({
                    device: device,
                    data: ac.get_autobtn(device)
                  });
                  udpArr.push({
                    device: device,
                    data: ac.get_autotmp_change(device.status_1, device)
                  });
                  break;
              }
              switch (device.grade) {
                case "wind_auto":
                  udpArr.push({
                    device: device,
                    data: ac.get_wind_change("0", device)
                  });
                  break;
                case "high":
                  udpArr.push({
                    device: device,
                    data: ac.get_wind_change("1", device)
                  });
                  break;
                case "medial":
                  udpArr.push({
                    device: device,
                    data: ac.get_wind_change("2", device)
                  });
                  break;
                case "low":
                  udpArr.push({
                    device: device,
                    data: ac.get_wind_change("3", device)
                  });
                  break;
              }
            } else {
              udpArr.push({
                device: device,
                data: ac.get_switch_change(false, device)
              });
            }
            break;
          case "light":
            if (device.on_off == "1") {
              var deviceProperty = {
                brightness: 100
              };
              udpArr.push({
                device: device,
                data: light.get_switch_change(true, device, deviceProperty)
              });
            } else {
              var deviceProperty = {
                brightness: 0
              };
              udpArr.push({
                device: device,
                data: light.get_switch_change(false, device, deviceProperty)
              });
            }
            break;
          case "led":
            if (device.on_off == "1") {
              var deviceProperty = {
                color: device.mode
              };
              udpArr.push({
                device: device,
                data: led.get_switch_change(true, device, deviceProperty)
              });
              // udpArr.push(light.get_slider_change(true, device));
            } else {
              var deviceProperty = {
                color: "#000000"
              };
              udpArr.push({
                device: device,
                data: led.get_switch_change(false, device, deviceProperty)
              });
            }
            break;
          case "curtain":
            if (device.on_off == "1") {
              udpArr.push({
                device: device,
                data: curtain.get_switch_change(true, device)
              });
              // udpArr.push(light.get_slider_change(true, device));
            } else {
              udpArr.push({
                device: device,
                data: curtain.get_switch_change(false, device)
              });
            }
            break;
          case "music":
            break;
        }
      }
      api.sendUdpArr(udpArr);
  }
}
export default api;