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
      params: {
        operatorCodefst: operatorCodefst,
        operatorCodesec: operatorCodesec,
        targetSubnetID: targetSubnetID,
        targetDeviceID: targetDeviceID,
        additionalContentData: additionalContentData,
        macAddress: macAddress ? macAddress.split(".") : "",
        dest_address: dest_address ? dest_address.split(".") : "",
        dest_port: dest_port ? dest_port.split(".") : ""
      }
    };
    api.apiGet("udp/sendUdp.php", data).then(res => {
      console.log("res = ", _g.j2s(res));
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
  }
};

export default commonFn;
