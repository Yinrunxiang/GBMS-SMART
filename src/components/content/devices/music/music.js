import api from "../../../../assets/js/api";

function strToarr(str) {
  var len = str.length / 2;
  var arr = [];
  for (var i = 0; i < len; i++) {
    arr.push(str.substr(i * 2, 2));
  }
  return arr;
}
function strToarr4(str) {
  var len = str.length / 4;
  var arr = [];
  for (var i = 0; i < len; i++) {
    arr.push(str.substr(i * 4, 4));
  }
  return arr;
}
const musicApi = {
  time_change(val, device) {
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
  vol_change(val, device, deviceProperty) {
    deviceProperty.vol = val;
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
  pre(device) {
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
  next(device) {
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
  play(device) {
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
  pause(device) {
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
  random(device) {
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
  single(device) {
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
  allmusic(device) {
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
  selectSong(device,deviceProperty,song) {
    var songNo = song.songNo
    songNo = _g.toHex(songNo)
    var songNoHigh = songNo.substr(0,1)
    var songNoLow = songNo.substr(1,1)
    const data = {
      params: {
        operatorCodefst: "02",
        operatorCodesec: "18",
        targetDeviceID: device.deviceid,
        additionalContentData: ("06,"+song.albumNo+"04").split(","),
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

  readStatus(device, deviceProperty) {
    var source = "01";
    var albumnum = 0;
    var albumNote = 0;
    let additionalContentData = "01".split(",");
    _g.sendUdp(
      "02",
      "E0",
      device.deviceid,
      additionalContentData,
      device.mac,
      device.ip,
      device.port
    );
    window.socketio.removeAllListeners("new_msg");
    window.socketio.on("new_msg", function (msg) {
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
          var additionalContentData = strToarr(albumpack);
          additionalContentData.unshift(source);
          // console.log(additionalContentData)
          _g.sendUdp(
            "02",
            "E2",
            device.deviceid,
            additionalContentData,
            device.mac,
            device.ip,
            device.port
          );
          console.log("02E1");
        }
        if (operationcode.toLowerCase() == "02e3") {
          var source = _g.getadditional(msg, 2);
          var firstAlbumNo = _g.getadditional(msg, 5);
          var albumLength = 0
          // device.albumno = '06'
          albumnum = _g.getadditional(msg, 4);
          var albumlist = msg.substring(60);
          deviceProperty.albumlist = [];



          for (var i = 0; i < albumlist.length; i += albumLength) {
            albumLength = albumlist.substr(i + 2, 2);
            albumLength = parseInt('0x' + albumLength) * 2
            var albumName = albumlist.substr(i + 4, albumLength)
            albumName = strToarr4(albumName)
            for (var item of albumName) {
              deviceProperty.albumlist.push(String.fromCharCode("0x" + item));
            }
            albumLength = albumLength + 4
          }
          deviceProperty.albumlist = deviceProperty.albumlist.join("");
          // deviceProperty.albumlist = deviceProperty.albumlist.match(/[a-zA-Z0-9.]+/g);
          // deviceProperty.albumlist = deviceProperty.albumlist ? deviceProperty.albumlist.join("") : "";
          deviceProperty.albumlist = deviceProperty.albumlist.split(".PLS");
          console.log(deviceProperty.albumlist);
          deviceProperty.songlist = [];
          albumnum = parseInt("0x" + albumnum);
          for (var i = 1; i <= albumnum; i++) {
            var additionalContentData = (source + "," + _g.toHex(i)).split(",");
            _g.sendUdp(
              "02",
              "E4",
              device.deviceid,
              additionalContentData,
              device.mac,
              device.ip,
              device.port
            );
          }
          // device.albumlist = []
          // for (i = 0; i < albumnum; i++) {
          //     var albumname = _g.getadditional(msg, 7 + i,50)
          //     device.albumlist.push(albumname)
          // }
          // console.log(albumlist)
          // var additionalContentData = (source + ',' + device.albumno).split(",")
          //     _g.sendUdp(e, "02", "E4", device.deviceid, additionalContentData, device.mac, device.ip, device.port)
          // console.log("02E3")
        }
        if (operationcode.toLowerCase() == "02e5") {
          var source = _g.getadditional(msg, 0);
          var albumno = _g.getadditional(msg, 1);
          var songpack = msg.substring(54, 56);

          songpack = parseInt("0x" + songpack);
          for (var i = 1; i <= songpack; i++) {
            var additionalContentData = (source + "," + albumno + "," + _g.toHex(i)).split(",");
            _g.sendUdp(
              "02",
              "E6",
              device.deviceid,
              additionalContentData,
              device.mac,
              device.ip,
              device.port
            );
          }
        }
        if (operationcode.toLowerCase() == "02e7") {
          var songNum = _g.getadditional(msg, 5)
          songNum = parseInt("0x" + songNum)
          var songCount = 0
          var songForNum = 0
          // device.songno = _g.getadditional(msg, 6)
          var currentSonglist = msg.substring(62)
          var albumno = _g.getadditional(msg, 3)
          
          
          var songlist = [];
          for (var i = 0; i <= songNum; i++) {
            var songLength = currentSonglist.substr(songCount + 4, 2);
            songLength = parseInt('0x' + songLength) * 2
            var songName = currentSonglist.substr(songCount + 6, songLength)
            songName = strToarr4(songName)
            var songNameList = []
            for (var item of songName) {
              songNameList.push(String.fromCharCode("0x" + item));
            }
            songName = songNameList.join("");
            // songlist.push(songName)
            var songObj = {}
            songObj.albumNo = parseInt("0x" + albumno)
            songObj.songNo = parseInt("0x" + (i+1))
            songObj.songName = songName
            deviceProperty.songlist.push(songObj);
            songCount = songCount + songLength + 6
          }
         
          // songlist = songlist.match(/[a-zA-Z0-9\u4e00-\u9fa5. ]+/g);
          // // songlist = songlist.match(/[a-zA-Z0-9. ]+/g);
          // // if (songlist) {
          // songlist = songlist ? songlist.join("") : "";
          // songlist = songlist.split(".mp3");
          // for (var i = 0; i < songlist.length; i++) {
          //   if (songlist[i] == "") {
          //     songlist.splice(i, 1);
          //   }
          // }
          
          console.log(deviceProperty.songlist);
          albumNote = albumNote + 1;
          // if (albumNote == albumnum - 1)
          //   window.socketio.removeAllListeners("new_msg");
          // }
        }
      }
    });
  }
};

export default musicApi;
