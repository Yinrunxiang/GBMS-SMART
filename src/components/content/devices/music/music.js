import api from "../../../../assets/js/api";
const musicApi = {
  time_change(val,device) {
    // device.autotmp = val;
    // device.loading = true
    // const data = {
    //     params: {
    //         operatorCodefst: "02",
    //         operatorCodesec: "18",
    //         targetDeviceID: device.deviceid,
    //         additionalContentData: ("05,01,03"+ _g.toHex(val)).split(","),
    //         macAddress: device.mac ? device.mac.split(".") : "",
    //         dest_address: device.ip ? device.ip.split(".") : "",
    //         dest_port: device.port ? device.port.split(".") : "",
    //     }
    // }
    // api.apiGet('udp/sendUdp.php', data).then((res) => {
    //     // console.log('res = ', _g.j2s(res))
    //     // _g.closeGlobalLoading()
    // })
  },
  vol_change(val,device) {
    device.cooltmp = val;
    device.loading = true;
    val = 79 - val;
    // if(val < 10)
    // val = '0'+val
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        // additionalContentData: ("05,01,03," + val).split(","),
        additionalContentData: ("05,01,03," + _g.toHex(val)).split(","),
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
  pre() {
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        additionalContentData: "04,01,00,00".split(","),
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log('res = ', _g.j2s(res))
      // _g.closeGlobalLoading()
    });
  },
  next() {
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        additionalContentData: "04,02,00,00".split(","),
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log('res = ', _g.j2s(res))
      // _g.closeGlobalLoading()
    });
  },
  play() {
    device.on_off = true;
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        additionalContentData: "04,03,00,00".split(","),
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
  pause() {
    device.on_off = false;
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        additionalContentData: "06,04".split(","),
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log('res = ', _g.j2s(res))
      // _g.closeGlobalLoading()
    });
  },
  random() {
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        additionalContentData: "02,01".split(","),
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log('res = ', _g.j2s(res))
      // _g.closeGlobalLoading()
    });
  },
  single() {
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        additionalContentData: "02,02".split(","),
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log('res = ', _g.j2s(res))
      // _g.closeGlobalLoading()
    });
  },
  allmusic() {
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        additionalContentData: "02,04".split(","),
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log('res = ', _g.j2s(res))
      // _g.closeGlobalLoading()
    });
  },
  strToarr(str) {
    var len = str.length / 2;
    var arr = [];
    for (var i = 0; i < len; i++) {
      arr.push(str.substr(i * 2, 2));
    }
    return arr;
  },

  readStatus(device) {
    var source = "01";
    var albumnum = 0;
    var albumNote = 0;
    let additionalContentData = "01".split(",");
    _g.sendUdp(
      "02",
      "E0",
      device.device.deviceid,
      additionalContentData,
      device.device.mac,
      device.device.ip,
      device.device.port
    );
    window.socketio.removeAllListeners("new_msg");
    window.socketio.on("new_msg", function(msg) {
      var msglen = msg.length;
      var stop = msglen - 4;
      var subnetid = msg.substr(34, 2);
      var deviceid = msg.substr(36, 2);

      // var channel = msg.substr(52, 2);
      if (
        subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
        deviceid.toLowerCase() == device.deviceid.toLowerCase()
      ) {
        //操作码
        var operationcode = _g.getoperationcode(msg);

        if (operationcode.toLowerCase() == "02e1") {
          var source = _g.getadditional(msg, 0);
          var albumpack = msg.substring(52, stop);
          var additionalContentData = device.strToarr(albumpack);
          additionalContentData.unshift(source);
          // console.log(additionalContentData)
          _g.sendUdp(
            "02",
            "E2",
            device.device.deviceid,
            additionalContentData,
            device.device.mac,
            device.device.ip,
            device.device.port
          );
          console.log("02E1");
        }
        if (operationcode.toLowerCase() == "02e3") {
          var source = _g.getadditional(msg, 2);
          var firstAlbumNo = _g.getadditional(msg, 5);
          // device.albumno = '06'
          albumnum = _g.getadditional(msg, 4);
          var albumlist = msg.substring(64);
          device.albumlist = [];
          var albumlist = device.strToarr(albumlist);
          for (var item of albumlist) {
            item = parseInt("0x" + item);
            device.albumlist.push(String.fromCharCode(item));
          }
          device.albumlist = device.albumlist.join("");
          device.albumlist = device.albumlist.match(/[a-zA-Z0-9.]+/g);
          device.albumlist = device.albumlist ? device.albumlist.join("") : "";
          device.albumlist = device.albumlist.split(".PLS");
          console.log(e.albumlist);
          device.songlist = [];
          albumnum = parseInt("0x" + albumnum);
          for (var i = 1; i <= albumnum; i++) {
            var additionalContentData = (source + "," + _g.toHex(i)).split(",");
            _g.sendUdp(
              "02",
              "E4",
              device.device.deviceid,
              additionalContentData,
              device.device.mac,
              device.device.ip,
              device.device.port
            );
          }
          // device.albumlist = []
          // for (i = 0; i < albumnum; i++) {
          //     var albumname = _g.getadditional(msg, 7 + i,50)
          //     device.albumlist.push(albumname)
          // }
          // console.log(albumlist)
          // var additionalContentData = (source + ',' + device.albumno).split(",")
          //     _g.sendUdp(e, "02", "E4", device.device.deviceid, additionalContentData, device.device.mac, device.device.ip, device.device.port)
          // console.log("02E3")
        }
        if (operationcode.toLowerCase() == "02e5") {
          var source = _g.getadditional(msg, 0);
          var albumno = _g.getadditional(msg, 1);
          var songpack = msg.substring(54, 56);
          var additionalContentData = device.strToarr(songpack);
          additionalContentData.unshift(albumno);
          additionalContentData.unshift(source);
          console.log(additionalContentData);
          // let additionalContentData = (source + ',' + albumno + ',' + songpack).split(",")
          _g.sendUdp(
            "02",
            "E6",
            device.device.deviceid,
            additionalContentData,
            device.device.mac,
            device.device.ip,
            device.device.port
          );
          // console.log("02E5")
        }
        if (operationcode.toLowerCase() == "02e7") {
          var songnum = _g.getadditional(msg, 5);
          // device.songno = _g.getadditional(msg, 6)
          var currentSonglist = msg.substring(68);
          var albumno = _g.getadditional(msg, 3);
          var songObj = {};
          songObj.albumno = parseInt("0x" + albumno);
          var songlist = [];
          var currentSonglist = device.strToarr(currentSonglist);
          for (var item of currentSonglist) {
            item = parseInt("0x" + item);
            songlist.push(String.fromCharCode(item));
          }
          songlist = songlist.join("");
          songlist = songlist.match(/[a-zA-Z0-9. ]+/g);
          // if (songlist) {
          songlist = songlist ? songlist.join("") : "";
          songlist = songlist.split(".mp3");
          for (var i = 0; i < songlist.length; i++) {
            if (songlist[i] == "") {
              songlist.splice(i, 1);
            }
          }
          songObj.songlist = songlist;
          device.songlist.push(songObj);
          console.log(e.songlist);
          albumNote = albumNote + 1;
          if (albumNote == albumnum - 1)
            window.socketio.removeAllListeners("new_msg");
          // }
        }
      }
    });
  }
};

export default musicApi;
