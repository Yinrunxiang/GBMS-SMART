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
function getAscii(str) {
    return str.toString().charCodeAt().toString(16)
}
var zoneFlag = '01',
    ftp = '02',
    ftp_true = '03'
const musicApi = {
    //更新FTP服务器数据
    updateAudioFtpServerData(device, deviceProperty) {
        var ftp = '02',
            ftp_true = '03'
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        source = getAscii(parseInt('0x' + source))
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = []
        additionalContentData[0] = "2A" //*
        additionalContentData[1] = "53" //S
        additionalContentData[2] = source //source
        additionalContentData[3] = "55" //U
        additionalContentData[4] = "50" //P
        additionalContentData[5] = "44" //D
        additionalContentData[6] = "41" //A
        additionalContentData[7] = "54" //T
        additionalContentData[8] = "45" //E
        additionalContentData[9] = "4C" //L
        additionalContentData[10] = "49" //I
        additionalContentData[11] = "53" //S
        additionalContentData[12] = "54" //T
        additionalContentData[13] = "0D" // <CR>
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //切换音源
    source_change(device, deviceProperty) {
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        var operatorCodefst = "02",
            operatorCodesec = "18",
            additionalContentData = ["01", source]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //切换音乐模式
    mode_change(device, deviceProperty) {
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        source = getAscii(parseInt('0x' + source))
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = []
        additionalContentData[0] = "2A" // *
        additionalContentData[1] = "53" // S
        additionalContentData[2] = source   // source: sd=1
        additionalContentData[3] = "4D" // M
        additionalContentData[4] = "4F" // O
        additionalContentData[5] = "44" // D
        additionalContentData[6] = "45" // E
        additionalContentData[7] = "2B" // +
        additionalContentData[8] = "0D" // <CR>
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //读取音乐状态
    readMusicStatus(device, deviceProperty) {
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = ["2A", "5A", "01", "53", "54", "41", "54", "55", "53", "3F", "0D"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //读取音乐模式
    readMusicModel(device, deviceProperty) {
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = ["2A", "53", "01", "50", "4C", "59", "4D", "4F", "44", "45", "3F", "0D"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //切换到收音机
    changeRadio(device, deviceProperty) {
        var operatorCodefst = "02",
            operatorCodesec = "18",
            additionalContentData = ["03", "06", device.channel, "00"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //上一首
    pre(device, deviceProperty) {
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        source = getAscii(parseInt('0x' + source))
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = []
        additionalContentData[0] = "2A" //*
        additionalContentData[1] = "53" //S
        additionalContentData[2] = source
        additionalContentData[3] = "50" //P
        additionalContentData[4] = "52" //R
        additionalContentData[5] = "45"//E
        additionalContentData[6] = "56"//V
        additionalContentData[7] = "0D"// <CR>

        additionalContentData[8] = "55"
        additionalContentData[9] = "AA"
        additionalContentData[10] = zoneFlag
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //下一首
    next(device, deviceProperty) {
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        source = getAscii(parseInt('0x' + source))
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = []
        additionalContentData[0] = "2A" //*
        additionalContentData[1] = "53" //S
        additionalContentData[2] = source
        additionalContentData[3] = "4E" //N
        additionalContentData[4] = "45" //E
        additionalContentData[5] = "58"//X
        additionalContentData[6] = "54"//T
        additionalContentData[7] = "0D"// <CR>

        additionalContentData[8] = "55"
        additionalContentData[9] = "AA"
        additionalContentData[10] = zoneFlag
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //调节音量大小
    vol_change(vol, device, deviceProperty) {
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        source = getAscii(parseInt('0x' + source))
        var vol = 79 - vol
        vol = vol.toString().split("")
        for (var index in vol) {
            vol[index] = getAscii(vol[index])
        }
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = []
        additionalContentData.push("2A")
        additionalContentData.push("5A")
        additionalContentData.push(source)
        additionalContentData.push("56")
        additionalContentData.push("4F")
        additionalContentData.push("4C")
        additionalContentData = additionalContentData.concat(vol)
        additionalContentData.push("0D")
        additionalContentData.push("55")
        additionalContentData.push("AA")
        additionalContentData.push(zoneFlag)
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //停止音乐
    pause(device, deviceProperty) {
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        source = getAscii(parseInt('0x' + source))
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = []
        additionalContentData[0] = "2A" //*
        additionalContentData[1] = "53" //S
        additionalContentData[2] = source
        additionalContentData[3] = "53" //S
        additionalContentData[4] = "54"  //T
        additionalContentData[5] = "4F" //O
        additionalContentData[6] = "50" //P
        additionalContentData[7] = "0D" // CR
        additionalContentData[8] = "55"
        additionalContentData[9] = "AA"
        additionalContentData[10] = zoneFlag
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //指定播放音乐
    selectSong(device, deviceProperty, song) {
        var albumNo = parseInt('0x' + song.albumNo).toString().split("")
        for (var index in albumNo) {
            albumNo[index] = getAscii(albumNo[index])
        }
        var songNo = song.songNoHigh + song.songNoLow
        songNo = parseInt('0x' + songNo).toString().split("")
        for (var index in songNo) {
            songNo[index] = getAscii(songNo[index])
        }
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        source = getAscii(parseInt('0x' + source))
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = []
        additionalContentData.push("2A")
        additionalContentData.push("53")
        additionalContentData.push(source)
        additionalContentData.push("4C")
        additionalContentData.push("49")
        additionalContentData.push("53")
        additionalContentData.push("54")
        additionalContentData = additionalContentData.concat(albumNo)
        additionalContentData.push("2C")
        additionalContentData.push("53")
        additionalContentData.push("4F")
        additionalContentData.push("4E")
        additionalContentData.push("47")
        additionalContentData = additionalContentData.concat(songNo)
        additionalContentData.push("0D")
        additionalContentData.push("55")
        additionalContentData.push("AA")
        additionalContentData.push(zoneFlag)
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    //播放任意音乐
    play(device, deviceProperty) {
        var source = deviceProperty.source == ftp ? ftp_true : deviceProperty.source
        source = getAscii(parseInt('0x' + source))
        var operatorCodefst = "19",
            operatorCodesec = "2E",
            additionalContentData = []
        additionalContentData[0] = "2A" //*
        additionalContentData[1] = "53" //S
        additionalContentData[2] = source
        additionalContentData[3] = "50" //P
        additionalContentData[4] = "4C"  //L
        additionalContentData[5] = "41" //A
        additionalContentData[6] = "59" //Y
        additionalContentData[7] = "0D" // CR
        additionalContentData[8] = "55"
        additionalContentData[9] = "AA"
        additionalContentData[10] = zoneFlag
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
    },
    readStatus(device, deviceProperty) {
        var $this = this
        var albumnum = 0;
        var albumNote = 0;
        var albumNoList = {}
        var songNoList = {}
        var songList = []

        var operatorCodefst = "02",
            operatorCodesec = "E0",
            additionalContentData = [deviceProperty.source]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
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
                                // console.log(deviceProperty.albumlist)
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
                            var udpArrSong = []
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
                                }
                            }, 5000)
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
                    if (key == "album") {
                        var source = _g.getadditional(msg, 0)
                        var albumNo = _g.getadditional(msg, 1)
                        if (data.additionalContentData[0] != source || data.additionalContentData[1] != albumNo) return
                    } else if (key == "song") {
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
}
export default musicApi;