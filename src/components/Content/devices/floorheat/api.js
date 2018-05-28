import api from "../api";
function toTmp(tmp) {
    tmp = parseInt('0x' + tmp)
    tmp = tmp >= 128 ? -256 + tmp : tmp
    return tmp
}
let controlOnAndOff = "14",
    controlModel = '15',
    controlTemp = '17',
    statusOn = '01',
    statusOff = '00',
    modelManual = '01',
    modelDay = '02',
    modelNight = '03',
    modelAway = '04',
    modelTimer = '05'
const acApi = {
    socketio: {},
    get_switch_change(val, device) {
        if (val) {
            var operatorCodefst = "E3",
                operatorCodesec = "D8",
                additionalContentData = [controlOnAndOff, statusOn, device.channel]
            return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        } else {
            var operatorCodefst = "E3",
                operatorCodesec = "D8",
                additionalContentData = [controlOnAndOff, statusOff, device.channel]
            return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        }
    },
    get_addTemperatureButtonClick(device) {
        device.deviceProperty.manualTemperature = device.deviceProperty.manualTemperature + 1
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlTemp, _g.toHex(device.deviceProperty.manualTemperature < 0 ? device.deviceProperty.manualTemperature + 256 : device.deviceProperty.manualTemperature), device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_reduceTemperatureButtonClick(device) {
        device.deviceProperty.manualTemperature = device.deviceProperty.manualTemperature - 1
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlTemp, _g.toHex(device.deviceProperty.manualTemperature < 0 ? device.deviceProperty.manualTemperature + 256 : device.deviceProperty.manualTemperature), device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_manualButtonClick(device) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelManual, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_dayButtonClick(device) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelDay, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_nightButtonClick(device) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelNight, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_awayButtonClick(device) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelAway, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_alarmButtonClick(device) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelTimer, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_temperatureChange(device) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlTemp, device.deviceProperty.temp, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_timeChange(device) {
        var dayTimeHour = _g.toHex(device.deviceProperty.dayTime.split(':')[0]),
            dayTimeMinute = _g.toHex(device.deviceProperty.dayTime.split(':')[1]),
            nightTimeHour = _g.toHex(device.deviceProperty.nightTime.split(':')[0]),
            nightTimeMinute = _g.toHex(device.deviceProperty.nightTime.split(':')[1])
        var operatorCodefst = "E3",
            operatorCodesec = "C9",
            additionalContentData = [dayTimeHour, dayTimeMinute, nightTimeHour, nightTimeMinute]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    switch_change(val, device) {
        const data = this.get_switch_change(val, device)
        api.sendUdp(device, data)
    },
    addTemperatureButtonClick(device) {
        const data = this.get_addTemperatureButtonClick(device)
        api.sendUdp(device, data)
    },
    reduceTemperatureButtonClick(device) {
        const data = this.get_reduceTemperatureButtonClick(device)
        api.sendUdp(device, data)
    },
    manualButtonClick(device) {
        const data = this.get_manualButtonClick(device)
        api.sendUdp(device, data)
    },
    dayButtonClick(device) {
        const data = this.get_dayButtonClick(device)
        api.sendUdp(device, data)
    },
    nightButtonClick(device) {
        const data = this.get_nightButtonClick(device)
        api.sendUdp(device, data)
    },
    awayButtonClick(device) {
        const data = this.get_awayButtonClick(device)
        api.sendUdp(device, data)
    },
    alarmButtonClick(device) {
        const data = this.get_alarmButtonClick(device)
        api.sendUdp(device, data)
    },
    timeChange(device) {
        const data = this.get_timeChange(device)
        api.sendUdp(device, data)
    },
    closeSocket() {
        // this.socketio.removeAllListeners()
    },
    readStatus(device) {
        console.log('floorheatApi')
        var udpArr = []
        // 读取定时器模式下的开始与结束时间
        var operatorCodefst = "03",
            operatorCodesec = "CB",
            additionalContentData = []
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        var udpObj = {
            device: device,
            data: data
        }
        udpArr.push(udpObj)
        // 读取模式匹配温度与传感器的地址
        var operatorCodefst = "03",
            operatorCodesec = "C7",
            additionalContentData = [device.channel]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        var udpObj = {
            device: device,
            data: data
        }
        udpArr.push(udpObj)
        // 读取室外温度 (读摄氏温度)
        var operatorCodefst = "E3",
            operatorCodesec = "E7",
            additionalContentData = ["01"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        var udpObj = {
            device: device,
            data: data
        }
        udpArr.push(udpObj)
        // 读取开关状态
        var operatorCodefst = "E3",
            operatorCodesec = "DA",
            additionalContentData = [controlOnAndOff, device.channel]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        var udpObj = {
            device: device,
            data: data
        }
        udpArr.push(udpObj)
        // 读取模式状态
        var operatorCodefst = "E3",
            operatorCodesec = "DA",
            additionalContentData = [controlModel, device.channel]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        var udpObj = {
            device: device,
            data: data
        }
        udpArr.push(udpObj)
        api.sendUdpArr(device, udpArr)
        // let userInfo = Lockr.get("userInfo");
        // let port = userInfo.port;
        // this.socketio = socket("http://" + document.domain + ":" + port);
        // this.socketio.on("new_msg", function (msg) {
        //     var subnetid = msg.substr(34, 2);
        //     var deviceid = msg.substr(36, 2);
        //     //操作码
        //     var operatorCode = msg.substr(42, 4).toLowerCase();
        //     switch (operatorCode) {
        //         case "efff":
        //             // if (
        //             //     subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
        //             //     deviceid.toLowerCase() != device.deviceid.toLowerCase()
        //             // ) return
        //             // var zoneCount = parseInt('0x' + _g.getadditional(msg, 0))
        //             // var channelCount = parseInt('0x' + _g.getadditional(msg, zoneCount + 1))
        //             // var deviceChannelArea = parseInt(parseInt(device.channel) / 8)
        //             // var channelStatus = parseInt('0x' + _g.getadditional(msg, zoneCount + 1 + deviceChannelArea + 1)).toString(2)
        //             // var deviceChannel = parseInt(device.channel) - (deviceChannelArea * 8)
        //             // var deviceChannelStatus = channelStatus.charAt(deviceChannel - 1)
        //             // deviceProperty.on_off = deviceChannelStatus == "1" ? true : false
        //             break
        //         case "e3d9":
        //             if (
        //                 subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
        //                 deviceid.toLowerCase() != device.deviceid.toLowerCase()
        //             ) return
        //             var channel = _g.getadditional(msg, 2)
        //             if (device.channel != channel) return
        //             var operatorKind = _g.getadditional(msg, 0)
        //             var operatorResult = _g.getadditional(msg, 1)
        //             switch (operatorKind) {
        //                 case controlOnAndOff:
        //                     deviceProperty.on_off = operatorResult == "01" ? true : false
        //                     break
        //                 case controlModel:
        //                     switch (operatorResult) {
        //                         case '01':
        //                             deviceProperty.mode = 'manual'
        //                             break
        //                         case '02':
        //                             deviceProperty.mode = 'day'
        //                             break
        //                         case '03':
        //                             deviceProperty.mode = 'night'
        //                             break
        //                         case '04':
        //                             deviceProperty.mode = 'away'
        //                             break
        //                         case '05':
        //                             deviceProperty.mode = 'alarm'
        //                             break
        //                     }
        //                     break
        //                 case controlTemp:
        //                     deviceProperty.manualTemperature = toTmp(operatorResult)

        //                     break
        //             }
        //             break
        //         case "03c8":
        //             if (
        //                 subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
        //                 deviceid.toLowerCase() != device.deviceid.toLowerCase()
        //             ) return
        //             var channel = _g.getadditional(msg, 0)
        //             if (device.channel != channel) return
        //             var manualTemperature = toTmp(_g.getadditional(msg, 1))
        //             var dayTemperature = toTmp(_g.getadditional(msg, 3))
        //             var nightTemperature = toTmp(_g.getadditional(msg, 5))
        //             var awayTemperature = toTmp(_g.getadditional(msg, 7))
        //             deviceProperty.manualTemperature = manualTemperature
        //             deviceProperty.dayTemperature = dayTemperature
        //             deviceProperty.nightTemperature = nightTemperature
        //             deviceProperty.awayTemperature = awayTemperature
        //             deviceProperty.insideSensor = {
        //                 targetSubnetID: _g.getadditional(msg, 9),
        //                 targetDeviceID: _g.getadditional(msg, 10),
        //                 channel: _g.getadditional(msg, 11),
        //             }
        //             var operatorCodefst = "E3",
        //                 operatorCodesec = "E7",
        //                 additionalContentData = ["01"]
        //             api.sendUdp(device, api.getUdp(deviceProperty.insideSensor, operatorCodefst, operatorCodesec, additionalContentData))
        //             break
        //         case "03ca":
        //             if (
        //                 subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
        //                 deviceid.toLowerCase() != device.deviceid.toLowerCase()
        //             ) return
        //             var operatorCodefst = "03",
        //                 operatorCodesec = "CB",
        //                 additionalContentData = []
        //             api.sendUdp(device, api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData))
        //             break
        //         case "03cc":
        //             if (
        //                 subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
        //                 deviceid.toLowerCase() != device.deviceid.toLowerCase()
        //             ) return
        //             var dayTimeHour = parseInt('0x' + _g.getadditional(msg, 0))
        //             var dayTimeMinute = parseInt('0x' + _g.getadditional(msg, 1))
        //             var nightTimeHour = parseInt('0x' + _g.getadditional(msg, 2))
        //             var nightTimeMinute = parseInt('0x' + _g.getadditional(msg, 3))
        //             deviceProperty.dayTime = dayTimeHour + ':' + dayTimeMinute
        //             deviceProperty.nightTime = nightTimeHour + ':' + nightTimeMinute
        //             break
        //         case "e3db":
        //             if (
        //                 subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
        //                 deviceid.toLowerCase() != device.deviceid.toLowerCase()
        //             ) return
        //             channel = _g.getadditional(msg, 2)
        //             if (device.channel != channel) {
        //                 return
        //             }
        //             operatorKind = _g.getadditional(msg, 0)
        //             operatorResult = _g.getadditional(msg, 1)
        //             switch (operatorKind) {
        //                 case controlOnAndOff:
        //                     deviceProperty.on_off = operatorResult == "01" ? true : false
        //                     break;
        //                 case controlModel:

        //                     switch (operatorResult) {
        //                         case '01':
        //                             deviceProperty.mode = 'manual'
        //                             break
        //                         case '02':
        //                             deviceProperty.mode = 'day'
        //                             break
        //                         case '03':
        //                             deviceProperty.mode = 'night'
        //                             break
        //                         case '04':
        //                             deviceProperty.mode = 'away'
        //                             break
        //                         case '05':
        //                             deviceProperty.mode = 'alarm'
        //                             break
        //                     }
        //                     break;
        //             }
        //             break
        //         case "e3e8":
        //             // if (_g.getadditional(msg, 0)) {
        //             //     return
        //             // }
        //             if (subnetid.toLowerCase() != deviceProperty.insideSensor.targetSubnetID.toLowerCase() || deviceid.toLowerCase() != deviceProperty.insideSensor.targetDeviceID.toLowerCase()) {
        //                 return
        //             }
        //             var insideChannel = parseInt('0x' + deviceProperty.insideSensor.channel);
        //             var outsideChannel = parseInt('0x' + device.outsideSensor.channel);

        //             deviceProperty.insideTemperature = _g.getadditional(msg, insideChannel + 8) ? (0 - parseInt('0x' + _g.getadditional(msg, insideChannel))) : parseInt('0x' + _g.getadditional(msg, insideChannel))

        //             deviceProperty.outsideTemperature = _g.getadditional(msg, outsideChannel + 8) ? (0 - parseInt('0x' + _g.getadditional(msg, outsideChannel))) : parseInt('0x' + _g.getadditional(msg, outsideChannel))
        //             break
        //     }

        // });
    },
    readOpen(device) {
        var operatorCodefst = "E3",
            operatorCodesec = "DA",
            additionalContentData = [controlOnAndOff, device.channel]
        api.sendUdp(device, api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData))
        // window.socketio.on("new_msg", function (msg) {
        //     var subnetid = msg.substr(34, 2);
        //     var deviceid = msg.substr(36, 2);
        //     // var channel = msg.substr(52, 2);
        //     if (
        //         subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
        //         deviceid.toLowerCase() == device.deviceid.toLowerCase()
        //     ) {
        //         var operatorCode = msg.substr(42, 4).toLowerCase();
        //         if (operatorCode != "e3d9" && operatorCode != "e3db") return
        //         var channel = _g.getadditional(msg, 2)
        //         if (device.channel != channel) return
        //         var operatorKind = _g.getadditional(msg, 0)
        //         var operatorResult = _g.getadditional(msg, 1)
        //         switch (operatorKind) {
        //             case controlOnAndOff:
        //                 device.on_off = operatorResult == "01" ? true : false
        //                 break
        //         }
        //     }
        // });
    },
}

export default acApi;
