import api from "./api"
const commonFn = {
  j2s(obj) {
    return JSON.stringify(obj);
  },
  // 十进制转十六进制
  toHex(num) {
    let num16 = parseInt(num).toString(16);
    num = num < 16 ? "0" + num16 : num16;
    return num;
  },
  // 十六进制字符串 转 数组
  strToarr(str) {
    var len = str.length / 2
    var arr = []
    for (var i = 0; i < len; i++) {
      arr.push(str.substr(i * 2, 2))
    }
    return arr
  },
  // 获取操作码
  getoperationcode(msg) {
    msg = msg.substr(42, 4)
    return msg
  },
  // 获取可变参数
  getadditional(msg, num, len) {
    num = num ? num : 0
    len = len ? len : 0
    msg = msg.substr((50 + num * 2), 2 * (len + 1))
    return msg
  },
  sendUdp(operatorCodefst, operatorCodesec, targetSubnetID, targetDeviceID, additionalContentData, macAddress, dest_address, dest_port) {
    let data = {
        operatorCodefst: operatorCodefst,
        operatorCodesec: operatorCodesec,
        targetSubnetID: targetSubnetID,
        targetDeviceID: targetDeviceID,
        additionalContentData: additionalContentData,
        macAddress: macAddress ? macAddress.split(".") : "",
        dest_address: dest_address ? dest_address.split(".") : "",
        dest_port: dest_port ? dest_port.split(".") : ""
    };
    api.apiPost("admin/udp/sendUdp", data).then(res => {
      // console.log("res = ", _g.j2s(res));
      // _g.closeGlobalLoading()
    });
  },
  shallowRefresh(name) {
    router.replace({ path: "/home/refresh", query: { name: name } });
  },
  closeGlobalLoading() {
    setTimeout(() => {
      store.dispatch("showLoading", false);
    }, 0);
  },
  openGlobalLoading() {
    setTimeout(() => {
      store.dispatch("showLoading", true);
    }, 0);
  },
  cloneJson(obj) {
    return JSON.parse(JSON.stringify(obj));
  },
  toastMsg(type, msg) {
    switch (type) {
      case "normal":
        bus.$message(msg);
        break;
      case "success":
        bus.$message({
          message: msg,
          type: "success"
        });
        break;
      case "warning":
        bus.$message({
          message: msg,
          type: "warning"
        });
        break;
      case "error":
        bus.$message.error(msg);
        break;
    }
  },
  clearVuex(cate) {
    store.dispatch(cate, []);
  },
  getHasRule(val) {
    const moduleRule = "admin";
    let userInfo = Lockr.get("userInfo");
    if (userInfo.id == 1) {
      return true;
    } else {
      let authList = moduleRule + Lockr.get("authList");
      return _.includes(authList, val);
    }
  },
  formatDate(date) {
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    var hour = date.getHours()
    var min = date.getMinutes()
    var sec = '00'
    if (month >= 1 && month <= 9) {
      month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
      strDate = "0" + strDate;
    }
    if (hour >= 0 && hour <= 9) {
      hour = "0" + hour;
    }
    if (min >= 0 && min <= 9) {
      min = "0" + min;
    }
    // if (sec >= 0 && sec <= 9) {
    //   sec = "0" + sec;
    // }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
      + " " + hour + seperator2 + min
      + seperator2 + sec;
    return currentdate;
  },
  getDay(){
    var date = new Date();
    var seperator1 = "-";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
      month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
      strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate;
    return currentdate;
  },
  getMonth(){
    var date = new Date();
    var seperator1 = "-";
    var month = date.getMonth() + 1;
    if (month >= 1 && month <= 9) {
      month = "0" + month;
    }
    var currentmonth = date.getFullYear() + seperator1 + month;
    return currentmonth;
  },
  addDeviceProperty(devices) {
    for (var device of devices) {
      switch (device.devicetype) {
        case "ac":
          device.deviceProperty = {
            on_off: device.on_off,
            coolTmp: 26,
            autoTmp: 0,
            heatTmp: 0,
            tmp: 26,
            grade: 2,
            mode: "cool",
            cool_strat: 0,
            cool_end: 36,
            heat_strat: 0,
            heat_end: 36,
            auto_strat: 0,
            auto_end: 36
          };
          break;
        case "light":
          device.deviceProperty = {
            brightness: 0,
            on_off: device.on_off
          };
          break;
        case "led":
          device.deviceProperty = {
            on_off: device.on_off,
            brightness: 0,
            color: device.mode
            // red: "c0",
            // green: "cc",
            // blue: "da"
          };
          break;
        case "curtain":
          device.deviceProperty = {
            on_off: device.on_off,
            brightness: 0
          };
          break;
        case "floorheat":
          device.deviceProperty = {
            on_off: device.on_off,
            manualTemperature: 26,
            dayTemperature: 26,
            nightTemperature: 26,
            awayTemperature: 26,
            alarmTemperature: 26,
            mode: "manual",
            dayTime: "",
            nightTime: "",
            insideTemperature: 26,
            outsideTemperature: 26,
            insideSensor: {
              targetSubnetID: "",
              targetDeviceID: "",
              channel: ""
            }
          };
          break;
        case "security":
          device.deviceProperty = {
            on_off: device.on_off
          };
          break;
        case "ir":
          device.deviceProperty = {
            on_off: device.on_off
          };
          break;
        case "music":
          device.deviceProperty = {
            vol: 20,
            mode: "random",
            on_off: device.on_off,
            music_name: "Waitting",
            music_autor: "Waitting",
            time_now: 0,
            time_over: 0,
            albumno: 0,
            albumlist: [],
            songno: 0,
            songList: [
              {
                songNo: 1,
                songName: "Waitting",
                select: true
              }
            ],
            songListAll: [],
            musicLoading: true,
            source: "01"
          };
          break;
      }
    }
  }
};

export default commonFn;
