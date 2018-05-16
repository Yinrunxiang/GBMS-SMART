import api from "../api";

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
    // api.apiGet('dmin/udp/sendUdp', data).then((res) => {
    //     // console.log('res = ', _g.j2s(res))
    //     // _g.closeGlobalLoading()
    // })
  },
  source_change(device, deviceProperty) {
    var source = deviceProperty.source == '02' ? '03' : deviceProperty.source
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["01", source]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  vol_change(val, device, deviceProperty) {
    deviceProperty.vol = val;
    device.loading = true;
    val = 79 - val;
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["05", "01", "03", _g.toHex(val)]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  pre(device) {
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["04", "01", "00", "00"]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  next(device) {
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["04", "02", "00", "00"]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  switch_change(val, device) {
    device.on_off = val;
    var data = {}
    switch (val) {
      case true:
        var operatorCodefst = "02",
          operatorCodesec = "18",
          additionalContentData = ["04", "03", "00", "00"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        break
      case false:
        var operatorCodefst = "02",
          operatorCodesec = "18",
          additionalContentData = ["04", "04", "00", "00"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        break
    }
    api.sendUdp(device, data)
  },
  play(device) {
    device.on_off = true;
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["04", "03", "00", "00"]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  pause(device) {
    device.on_off = false;
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["04", "04", "00", "00"]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  random(device) {
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["02", "01"]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  single(device) {
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["02", "02"]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  allmusic(device) {
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["02", "04"]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },
  selectSong(device, deviceProperty, song) {
    var operatorCodefst = "02",
      operatorCodesec = "18",
      additionalContentData = ["06", song.albumNo, song.songNoHigh, song.songNoLow]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
  },

  readStatus(device, deviceProperty) {
    console.log('music_api')
    var $this = this
    var albumnum = 0;
    var albumNote = 0;
    var albumNoList = {}
    var songNoList = {}
    var songList = []
    var udpArrSong = []
    var operatorCodefst = "02",
      operatorCodesec = "E0",
      additionalContentData = [deviceProperty.source]
    var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    api.sendUdp(device, data)
    // window.socketio.connect()
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
          if (deviceProperty.source == source) {
            var albumpack = msg.substring(52, stop);
            var additionalContentData = strToarr(albumpack);
            additionalContentData.unshift(source);
            var operatorCodefst = "02",
              operatorCodesec = "E2"
            var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
            api.sendUdp(device, data)
          }

        }
        if (operationcode.toLowerCase() == "02e3") {
          var source = _g.getadditional(msg, 2);
          if (deviceProperty.source == source) {
            var firstAlbumNo = _g.getadditional(msg, 5);
            var albumCount = 0
            // device.albumno = '06'
            albumnum = _g.getadditional(msg, 4);
            var albumlist = msg.substring(60);
            deviceProperty.albumlist = [];
            deviceProperty.songList = [];
            deviceProperty.songListAll = [];
            var albumList = []
            songList = []
            for (var i = 0; i < albumnum; i++) {
              var albumNo = albumlist.substr(albumCount, 2);
              var albumLength = albumlist.substr(albumCount + 2, 2);
              albumLength = parseInt('0x' + albumLength) * 2
              var albumNameList = albumlist.substr(albumCount + 4, albumLength)
              albumNameList = strToarr4(albumNameList)
              var albumName = []
              for (var item of albumNameList) {
                albumName.push(String.fromCharCode("0x" + item));
              }
              albumName = albumName.join("");
              albumNo = albumlist.substr(albumCount, 2);
              var albumObj = {
                albumName: albumName,
                albumNo: albumNo
              }
              albumList.push(albumObj)
              albumNoList[albumNo] = false
              albumCount = albumCount + albumLength + 4
            }
            var udpArrAlbum = []
            // var udpArrSong = []
            for (var key in albumNoList) {
              var operatorCodefst = "02",
                operatorCodesec = "E4",
                additionalContentData = [source, key]
              var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
              var udpObj = {
                device: device,
                data: data,
                key: "album"
              }
              udpArrAlbum.push(udpObj)
            }
            $this.sendUdpArr(udpArrAlbum)
            var albumInterval = setInterval(function () {
              var check = true
              var udpArrAlbum = []
              // console.log(albumNoList)
              for (var key in albumNoList) {
                if (!albumNoList[key]) {
                  check = false
                  var operatorCodefst = "02",
                    operatorCodesec = "E4",
                    additionalContentData = [source, key]
                  var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
                  var udpObj = {
                    device: device,
                    data: data,
                    key: "album"
                  }
                  udpArrAlbum.push(udpObj)
                }
              }
              $this.sendUdpArr(udpArrAlbum)
              if (check) {
                clearInterval(albumInterval)
                albumList.sort(function (a, b) {
                  // return a.songNo - b.songNo
                  return parseInt(a.albumNo) - parseInt(b.albumNo)
                });
                deviceProperty.albumlist = albumList
                $this.sendUdpArr(udpArrSong)
                var songInterval = setInterval(function () {
                  var check = true
                  var udpArrSong = []
                  // console.log(songNoList)
                  for (var key in songNoList) {
                    if (!songNoList[key]) {
                      check = false
                      var operatorCodefst = "02",
                        operatorCodesec = "E6",
                        additionalContentData = [source, key.substr(0, 2), key.substr(2, 2)]
                      var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
                      // api.sendUdp(device, data)
                      var udpObj = {
                        device: device,
                        data: data,
                        key: "song"
                      }
                      udpArrSong.push(udpObj)
                    }
                  }

                  $this.sendUdpArr(udpArrSong)
                  if (check) {
                    clearInterval(songInterval)
                    // var hash = {};
                    // songList = songList.reduce(function (item, next) {
                    //   hash[next.songNo] ? '' : hash[next.songNo] = true && item.push(next);
                    //   return item
                    // }, [])
                    // songList.sort(function (a, b) {
                    //   // return a.songNo - b.songNo
                    //   return parseInt(a.albumNo + a.No) - parseInt(b.albumNo + b.No)
                    // });
                    deviceProperty.songList = songList
                    deviceProperty.songListAll = songList
                    deviceProperty.musicLoading = false
                    Lockr.set('music_' + device.id + '_' + deviceProperty.source, deviceProperty)
                    // window.socketio.disconnect()
                  }
                }, 10000)
              }
            }, 5000)
          }

        }
        if (operationcode.toLowerCase() == "02e5") {
          var source = _g.getadditional(msg, 0);
          if (deviceProperty.source == source) {
            var albumno = _g.getadditional(msg, 1);
            var songpack = msg.substring(54, 56);
            if (!albumNoList[albumno]) {
              albumNoList[albumno] = true

              songpack = parseInt("0x" + songpack);

              for (var i = 1; i <= songpack; i++) {
                songNoList[albumno + _g.toHex(i)] = false
                var operatorCodefst = "02",
                  operatorCodesec = "E6",
                  additionalContentData = [source, albumno, _g.toHex(i)]
                var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
                // api.sendUdp(device, data)
                var udpObj = {
                  device: device,
                  data: data,
                  key: "song"
                }
                udpArrSong.push(udpObj)
              }


            }
          }

        }

        if (operationcode.toLowerCase() == "02e7") {
          var source = _g.getadditional(msg, 2);
          if (deviceProperty.source == source) {
            var songNum = _g.getadditional(msg, 5)
            songNum = parseInt("0x" + songNum)
            var songCount = 0
            var songForNum = 0
            var currentSonglist = msg.substring(62)
            var albumno = _g.getadditional(msg, 3)
            var packNo = _g.getadditional(msg, 4)
            if (!songNoList[albumno + packNo]) {
              songNoList[albumno + packNo] = true
              for (var i = 0; i < songNum; i++) {
                var songLength = currentSonglist.substr(songCount + 4, 2);
                songLength = parseInt('0x' + songLength) * 2
                var songName = currentSonglist.substr(songCount + 6, songLength)
                songName = strToarr4(songName)
                var songNameList = []
                for (var item of songName) {
                  songNameList.push(String.fromCharCode("0x" + item));
                }
                songName = songNameList.join("");
                var songObj = {}
                songObj.albumNo = albumno
                songObj.No = parseInt('0x' + currentSonglist.substr(songCount, 4))
                songObj.songNo = songObj.albumNo + currentSonglist.substr(songCount, 4)
                songObj.songNoHigh = currentSonglist.substr(songCount, 2);
                songObj.songNoLow = currentSonglist.substr(songCount + 2, 2);
                songObj.songName = songName
                songObj.select = false
                songList.push(songObj);
                songCount = songCount + songLength + 6
              }
              albumNote = albumNote + 1;
            }
          }

        }
      }
    });
  },
  // sendUdpArr(arr) {
  //   if (!arr || arr.length == 0) return
  //   var arrLenght = arr.length
  //   var index = 0
  //   var sendUdpFor = setInterval(function () {
  //     if (index >= arrLenght) {
  //       clearInterval(sendUdpFor);
  //       return;
  //     }
  //     api.apiPost("admin/udp/sendUdp", arr[index].data).then(res => {
  //     });
  //     console.log(index)
  //     index++
  //   }, 500);

  // },
  sendUdpArr(arr) {
    if (!arr || arr.length == 0) return
    var $this = this
    var arrIndex = 0
    var arrIndex = 0
    var sendUdp = function (device, data, type, key) {
      if (!type || type == "") {
        api.apiPost("admin/udp/sendUdp", data).then(res => {
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
          if (operatorCodeCurrent.toLowerCase() == "02e5") {
            var source = _g.getadditional(msg, 0)
            var albumNo = _g.getadditional(msg, 1)
            if (data.additionalContentData[0] != source || data.additionalContentData[1] != albumNo) return
          } else if (operatorCodeCurrent.toLowerCase() == "02e7") {
            var source = _g.getadditional(msg, 2)
            var albumNo = _g.getadditional(msg, 3)
            var songNo = _g.getadditional(msg, 4)
            if (data.additionalContentData[0] != source || data.additionalContentData[1] != albumNo || data.additionalContentData[2] != songNo) return
          }

          pass = true
        })

        var sendUdpFor = setInterval(function () {
          if (pass || index > 3) {
            clearInterval(sendUdpFor);
            console.log(arrIndex)
            arrIndex++
            if (arr[arrIndex]) {
              if (arr[arrIndex].device.time && parseInt(arr[arrIndex].device.time) > 0) {
                var time = parseInt(arr[arrIndex].device.time) * 1000
                var timeCode = function () {
                  sendUdp(arr[arrIndex].device, arr[arrIndex].data, 1)
                }
                setTimeout(timeCode, time)
              } else {
                sendUdp(arr[arrIndex].device, arr[arrIndex].data, 1)
              }
            }
            return;
          }
          api.apiPost("admin/udp/sendUdp", data).then(res => {
          });
          index++
        }, 100);
      }

    }
    if (arr[arrIndex].device.time && parseInt(arr[arrIndex].device.time) > 0) {
      var time = parseInt(arr[arrIndex].device.time) * 1000
      var timeCode = function () {
        sendUdp(arr[arrIndex].device, arr[arrIndex].data, 1)
      }
      setTimeout(timeCode, time)
    } else {
      sendUdp(arr[arrIndex].device, arr[arrIndex].data, 1)
    }

  },
};

export default musicApi;
