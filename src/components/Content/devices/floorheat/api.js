import api from "../../../../assets/js/api";
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
    get_switch_change(val, device, deviceProperty) {
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
    get_manualButtonClick(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelManual, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_dayButtonClick(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelDay, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_nightButtonClick(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelNight, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_awayButtonClick(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelAway, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_alarmButtonClick(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlModel, modelTimer, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_temperatureChange(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = [controlTemp, deviceProperty.temp, device.channel]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    switch_change(val, device, deviceProperty) {
        const data = this.get_switch_change(val, device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    manualButtonClick(device, deviceProperty) {
        const data = this.get_manualButtonClick(device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    dayButtonClick(device, deviceProperty) {
        const data = this.get_dayButtonClick(device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    nightButtonClick(device, deviceProperty) {
        const data = this.get_nightButtonClick(device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    awayButtonClick(device, deviceProperty) {
        const data = this.get_awayButtonClick(device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    alarmButtonClick(device, deviceProperty) {
        const data = this.get_alarmButtonClick(device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    readStatus(device, deviceProperty) {
        // 读取定时器模式下的开始与结束时间
        var operatorCodefst = "03",
            operatorCodesec = "CB",
            additionalContentData = []
        api.apiGet('udp/sendUdp.php', api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)).then((res) => {
        })
        // 读取模式匹配温度与传感器的地址
        var operatorCodefst = "03",
            operatorCodesec = "C7",
            additionalContentData = [device.channel]
        api.apiGet('udp/sendUdp.php', api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)).then((res) => {
        })
        // 读取室外温度 (读摄氏温度)
        var operatorCodefst = "E3",
            operatorCodesec = "E7",
            additionalContentData = ["01"]
        api.apiGet('udp/sendUdp.php', api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)).then((res) => {
        })
        // 读取开关状态
        var operatorCodefst = "E3",
            operatorCodesec = "DA",
            additionalContentData = [controlOnAndOff, device.channel]
        api.apiGet('udp/sendUdp.php', api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)).then((res) => {
        })
        // 读取模式状态
        var operatorCodefst = "E3",
            operatorCodesec = "DA",
            additionalContentData = [controlModel, device.channel]
        api.apiGet('udp/sendUdp.php', api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)).then((res) => {
        })
        window.socketio.on("new_msg", function (msg) {
            var subnetid = msg.substr(34, 2);
            var deviceid = msg.substr(36, 2);
            // var channel = msg.substr(52, 2);
            if (
                subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
                deviceid.toLowerCase() == device.deviceid.toLowerCase()
            ) {
                //操作码
                var operatorCode = msg.substr(42, 4).toLowerCase();
                switch (operatorCode) {
                    case "efff":
                        var zoneCount = parseInt('0x' + _g.getadditional(msg, 0))
                        var channelCount = parseInt('0x' + _g.getadditional(msg, zoneCount + 1))
                        var deviceChannelArea = parseInt(parseInt(device.channel) / 8)
                        var channelStatus = parseInt('0x' + _g.getadditional(msg, zoneCount + 1 + deviceChannelArea + 1)).toString(2)
                        deviceChannel = parseInt(device.channel) - (deviceChannelArea * 8)
                        deviceChannelStatus = channelStatus.charAt(deviceChannel - 1)
                        deviceProperty.on_off = deviceChannelStatus == "1" ? true : false
                        break
                    case "e3d9":
                        var channel = _g.getadditional(msg, 2)
                        if (device.channel != channel) return
                        var operatorKind = _g.getadditional(msg, 0)
                        var operatorResult = _g.getadditional(msg, 1)
                        switch (operatorKind) {
                            case controlOnAndOff:
                                deviceProperty.on_off = operatorResult == "01" ? true : false
                                console.log("开关" + operatorResult)
                                break
                            case controlModel:
                                deviceProperty.mode = operatorResult
                                console.log("操作" + operatorResult)
                                break
                            case controlTemp:
                                deviceProperty.temp = parseInt(operatorResult)
                                console.log("温度" + operatorResult)
                                break
                        }
                        break
                    case "03c8":
                        var channel = _g.getadditional(msg, 0)
                        if (device.channel != channel) return
                        var manualTemperature = parseInt('0x' + _g.getadditional(msg, 1))
                        var dayTemperature = parseInt('0x' + _g.getadditional(msg, 3))
                        var nightTemperature = parseInt('0x' + _g.getadditional(msg, 5))
                        var awayTemperature = parseInt('0x' + _g.getadditional(msg, 7))
                        deviceProperty.insideSensor = {
                            targetSubnetID: _g.getadditional(msg, 9),
                            targetDeviceID: _g.getadditional(msg, 10),
                            channel: _g.getadditional(msg, 11),
                        }
                        var operatorCodefst = "E3",
                            operatorCodesec = "E7",
                            additionalContentData = ["01"]
                        api.apiGet('udp/sendUdp.php', api.getUdp(deviceProperty.insideSensor, operatorCodefst, operatorCodesec, additionalContentData)).then((res) => {
                        })
                        break
                    case "03ca":
                        var operatorCodefst = "03",
                            operatorCodesec = "CB",
                            additionalContentData = []
                        api.apiGet('udp/sendUdp.php', api.getUdp(deviceProperty.insideSensor, operatorCodefst, operatorCodesec, additionalContentData)).then((res) => {
                        })
                        break
                    case "03cc":
                        var dayTimeHour = _g.getadditional(msg, 0)
                        var dayTimeMinute = _g.getadditional(msg, 1)
                        var nightTimeHour = _g.getadditional(msg, 2)
                        var nightTimeMinute = _g.getadditional(msg, 3)
                        deviceProperty.dayTime = dayTimeHour+':'+dayTimeMinute
                        deviceProperty.nightTime = nightTimeHour+':'+nightTimeMinute
                        break
                    case "e3d8":
                        channel = _g.getadditional(msg, 2)
                        if (deviceProperty.channel != channel) {
                            return
                        }
                        operatorKind = _g.getadditional(msg, 0)
                        operatorResult = _g.getadditional(msg, 1)
                        switch (operatorKind) {
                            case controlOnAndOff:
                                deviceProperty.on_off = operatorResult
                                break;
                            case controlModel:
                                deviceProperty.mode = operatorResult
                                break;
                        }
                        break
                    case "e3e8":
                        if (_g.getadditional(msg, 0)) {
                            return
                        }
                        if (subnetid != deviceProperty.insideSensor.targetSubnetID || deviceid != deviceProperty.insideSensor.targetSubnetID) {
                            return
                        }
                        var insideChannel = parseInt('0x' + deviceProperty.insideSensor.channel);
                        var outsideChannel = parseInt('0x' + device.outsideSensor.channel);

                        deviceProperty.insideTemperature = _g.getadditional(msg, insideChannel + 8) ? (0 - parseInt('0x' + _g.getadditional(msg, insideChannel))) : parseInt('0x' + _g.getadditional(msg, insideChannel))

                        deviceProperty.outsideTemperature = _g.getadditional(msg, outsideChannel + 8) ? (0 - parseInt('0x' + _g.getadditional(msg, outsideChannel))) : parseInt('0x' + _g.getadditional(msg, outsideChannel))
                        break
                }
            }
        });
    },
    readOpen(device) {
        var operatorCodefst = "E3",
            operatorCodesec = "DA",
            additionalContentData = [controlOnAndOff, device.channel]
        api.apiGet('udp/sendUdp.php', api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)).then((res) => {
        })
        window.socketio.on("new_msg", function (msg) {
            var subnetid = msg.substr(34, 2);
            var deviceid = msg.substr(36, 2);
            // var channel = msg.substr(52, 2);
            if (
                subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
                deviceid.toLowerCase() == device.deviceid.toLowerCase()
            ) {
                var operatorCode = msg.substr(42, 4).toLowerCase();
                if (operatorCode != "e3d9") return
                var channel = _g.getadditional(msg, 2)
                if (device.channel != channel) return
                var operatorKind = _g.getadditional(msg, 0)
                var operatorResult = _g.getadditional(msg, 1)
                switch (operatorKind) {
                    case controlOnAndOff:
                        device.on_off = operatorResult == "01" ? true : false
                        break
                }
            }
        });
    },
}

export default acApi;
