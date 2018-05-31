import api from "./api";
import ac from "./ac/api";
import light from "./light/api";
import led from "./led/api";
import curtain from "./curtain/api";
import music from "./music/api";
const udpArr = {
  sendUdpArr(deviceList) {
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
                  data: ac.get_coolTmp_change(device.status_1, device)
                });
                break;
              case "fan":
                udpArr.push({
                  device: device,
                  data: ac.get_fanbtn(device)
                });
                udpArr.push({
                  device: device,
                  data: ac.get_coolTmp_change(device.status_1, device)
                });
                break;
              case "heat":
                udpArr.push({
                  device: device,
                  data: ac.get_heatbtn(device)
                });
                udpArr.push({
                  device: device,
                  data: ac.get_heatTmp_change(device.status_1, device)
                });
                break;
              case "auto":
                udpArr.push({
                  device: device,
                  data: ac.get_autobtn(device)
                });
                udpArr.push({
                  device: device,
                  data: ac.get_autoTmp_change(device.status_1, device)
                });
                break;
            }
            switch (device.grade) {
              case "0":
                udpArr.push({
                  device: device,
                  data: ac.get_wind_change("0", device)
                });
                break;
              case "1":
                udpArr.push({
                  device: device,
                  data: ac.get_wind_change("1", device)
                });
                break;
              case "2":
                udpArr.push({
                  device: device,
                  data: ac.get_wind_change("2", device)
                });
                break;
              case "3":
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
            udpArr.push({
              device: device,
              data: light.get_switch_change(true, device)
            });
          } else {
            udpArr.push({
              device: device,
              data: light.get_switch_change(false, device)
            });
          }
          break;
        case "led":
          if (device.on_off == "1") {
            udpArr.push({
              device: device,
              data: led.get_switch_change(true, device)
            });
            // udpArr.push(light.get_slider_change(true, device));
          } else {
            udpArr.push({
              device: device,
              data: led.get_switch_change(false, device)
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
          if (device.on_off == "1") {
            var song = {
              albumNo: device.status_4.substr(0, 2),
              songNoHigh: device.status_4.substr(2, 2),
              songNoLow: device.status_4.substr(4, 2),
            }
            udpArr.push({
              device: device,
              data: music.get_vol_change(true, device)
            });
            udpArr.push({
              device: device,
              data: music.get_source_change(device.status_2,device)
            });
            udpArr.push({
              device: device,
              data: music.get_selectSong(device, song)
            });
            // udpArr.push({
            //   device: device,
            //   data: music.play(device)
            // });
            // udpArr.push(light.get_slider_change(true, device));
          } else {
            udpArr.push({
              device: device,
              data: music.get_pause(device)
            });
          }
          break;
      }
    }
    api.sendUdpArr(udpArr);
  }
}
export default udpArr;