import api from "../api";
function toTmp(tmp) {
    tmp = parseInt('0x' + tmp)
    tmp = tmp >= 128 ? -256 + tmp : tmp
    return tmp
}
const acApi = {
    socketio:{},
    get_switch_change(val, device, deviceProperty) {
        if (val) {
            var operatorCodefst = "E3",
                operatorCodesec = "D8",
                additionalContentData = ["03", "01"]
            return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        } else {
            var operatorCodefst = "E3",
                operatorCodesec = "D8",
                additionalContentData = ["03", "00"]
            return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        }
    },
    get_autotmp_change(val, device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = ["08", _g.toHex(val < 0 ? val + 256 : val)]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_cooltmp_change(val, device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = ["04", _g.toHex(val < 0 ? val + 256 : val)]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_heattmp_change(val, device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = ["07", _g.toHex(val < 0 ? val + 256 : val)]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_wind_change(val, device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = ["05", _g.toHex(val)]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_autobtn(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = ["06", "03"]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_fanbtn(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = ["06", "02"]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_coolbtn(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = ["06", "00"]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_heatbtn(device, deviceProperty) {
        var operatorCodefst = "E3",
            operatorCodesec = "D8",
            additionalContentData = ["06", "01"]
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    get_readTmpRange(device, deviceProperty) {
        var operatorCodefst = "19",
            operatorCodesec = "00",
            additionalContentData = []
        return api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
    },
    switch_change(val, device, deviceProperty) {
        const data = this.get_switch_change(val, device, deviceProperty)
        api.sendUdp(device, data)
    },
    autotmp_change(val, device, deviceProperty) {

        const data = this.get_autotmp_change(val, device, deviceProperty)
        api.sendUdp(device, data)
    },
    cooltmp_change(val, device, deviceProperty) {

        const data = this.get_cooltmp_change(val, device, deviceProperty)
        api.sendUdp(device, data)
    },
    heattmp_change(val, device, deviceProperty) {

        const data = this.get_heattmp_change(val, device, deviceProperty)
        api.sendUdp(device, data)
    },
    wind_change(val, device, deviceProperty) {

        const data = this.get_wind_change(val, device, deviceProperty)
        api.sendUdp(device, data)
    },
    autobtn(device, deviceProperty) {
        const data = this.get_autobtn(device, deviceProperty)
        api.sendUdp(device, data)
    },
    fanbtn(device, deviceProperty) {
        const data = this.get_fanbtn(device, deviceProperty)
        api.sendUdp(device, data)
    },
    coolbtn(device, deviceProperty) {
        const data = this.get_coolbtn(device, deviceProperty)
        api.sendUdp(device, data)
    },
    heatbtn(device, deviceProperty) {
        const data = this.get_heatbtn(device, deviceProperty)
        api.sendUdp(device, data)
    },
    readTmpRange(device, deviceProperty) {
        const data = this.get_readTmpRange(device, deviceProperty)
        api.sendUdp(device, data)
    },
    closeSocket(){
        this.socketio.removeAllListeners()
      },
    readStatus(device, deviceProperty) {
        var operatorCodefst = "E0",
            operatorCodesec = "EC",
            additionalContentData = ["00"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        // console.log(device)
        api.sendUdp(device, data)
        let userInfo = Lockr.get("userInfo");
        let port = userInfo.port;
        this.socketio = socket("http://" + document.domain + ":" + port);
        this.socketio.on("new_msg", function (msg) {
            var subnetid = msg.substr(34, 2);
            var deviceid = msg.substr(36, 2);
            // var channel = msg.substr(52, 2);
            if (
                subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
                deviceid.toLowerCase() == device.deviceid.toLowerCase()
            ) {
                //操作码
                var msg1 = msg.substr(42, 4).toLowerCase();
                switch (msg1) {
                    case "e0ed":
                        // var channel = msg.substr(50, 2);
                        // if (channel.toLowerCase() == item.channel.toLowerCase()) {
                        //空调开关
                        var ac = msg.substr(50, 2);
                        if (ac != "00") {
                            deviceProperty.on_off = true
                            // brightnessSlider.slider("setValue", msg2);
                        } else {
                            deviceProperty.on_off = false
                        }
                        //制冷温度
                        var cooltempdata = msg.substr(52, 2);
                        deviceProperty.cooltmp = toTmp(cooltempdata)

                        //制热温度
                        var heattempdata = msg.substr(60, 2);
                        deviceProperty.heattmp = toTmp(heattempdata)

                        //自动温度
                        var autotempdata = msg.substr(64, 2);
                        deviceProperty.autotmp = toTmp(autotempdata)

                        //模式
                        var mode = msg.substr(54, 2);
                        if (
                            [
                                "00",
                                "01",
                                "02",
                                "03",
                                "04",
                                "05",
                                "06",
                                "07",
                                "08",
                                "09"
                            ].indexOf(mode) != -1
                        ) {
                            deviceProperty.mode = 'cool'
                        } else if (
                            [
                                "10",
                                "11",
                                "12",
                                "13",
                                "14",
                                "15",
                                "16",
                                "17",
                                "18",
                                "19"
                            ].indexOf(mode) != -1
                        ) {
                            deviceProperty.mode = 'heat'
                        } else if (
                            [
                                "20",
                                "21",
                                "22",
                                "23",
                                "24",
                                "25",
                                "26",
                                "27",
                                "28",
                                "29"
                            ].indexOf(mode) != -1
                        ) {
                            deviceProperty.mode = 'fan'
                        } else {
                            deviceProperty.mode = 'auto'
                        }

                        //风量大小
                        var fan = msg.substr(56, 2);
                        deviceProperty.wind = parseInt("0x" + fan);
                        //local flag
                        var flag = msg.substr(58, 2);
                        //当前温度current
                        var current = msg.substr(58, 2);
                        deviceProperty.tmp = toTmp(current)
                        break;
                    case "e3d9":
                        var type = msg.substr(50, 2);
                        var value = msg.substr(52, 2);
                        switch (type) {
                            case "03":
                                if (value == "01") {
                                    deviceProperty.on_off = true
                                } else {
                                    deviceProperty.on_off = false
                                }
                                break;
                            case "04":
                                deviceProperty.cooltmp = toTmp(value)
                                break;
                            case "05":
                                deviceProperty.wind = parseInt("0x" + value);
                                break;
                            case "06":
                                value = parseInt("0x" + value);
                                switch (value) {
                                    case 0:
                                        deviceProperty.mode = "cool"
                                        break;
                                    case 1:
                                        deviceProperty.mode = "heat"
                                        break;
                                    case 2:
                                        deviceProperty.mode = "fan"
                                        break;
                                    case 3:
                                        deviceProperty.mode = "auto"
                                        break;
                                }
                                break;
                            case "07":
                                deviceProperty.heattmp = toTmp(value)
                                break;
                            case "08":
                                deviceProperty.autotmp = toTmp(value)
                                break;
                        }
                        break
                    case "1901":

                        deviceProperty.cool_strat = toTmp(msg.substr(50, 2)),
                            deviceProperty.cool_end = toTmp(msg.substr(52, 2)),
                            deviceProperty.heat_strat = toTmp(msg.substr(54, 2)),
                            deviceProperty.heat_end = toTmp(msg.substr(56, 2)),
                            deviceProperty.auto_strat = toTmp(msg.substr(58, 2)),
                            deviceProperty.auto_end = toTmp(msg.substr(60, 2))
                        // var tmp_range = {
                        //     cool_strat:cool_strat,
                        //     cool_end:cool_end,
                        //     heat_strat:heat_strat,
                        //     heat_end:heat_end,
                        //     auto_strat:auto_strat,
                        //     auto_end:auto_end
                        // }
                        // console.log(tmp_range)
                        break
                }
            }
        });
    },
    readOpen(device) {
        var operatorCodefst = "E0",
            operatorCodesec = "EC",
            additionalContentData = ["00"]
        var data = api.getUdp(device, operatorCodefst, operatorCodesec, additionalContentData)
        api.sendUdp(device, data)
        window.socketio.on("new_msg", function (msg) {
            var subnetid = msg.substr(34, 2);
            var deviceid = msg.substr(36, 2);
            // var channel = msg.substr(52, 2);
            if (
                subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
                deviceid.toLowerCase() == device.deviceid.toLowerCase()
            ) {
                //操作码
                var msg1 = msg.substr(42, 4);
                if (msg1.toLowerCase() == "e0ed") {
                    // var channel = msg.substr(50, 2);
                    // if (channel.toLowerCase() == item.channel.toLowerCase()) {
                    //空调开关
                    var ac = msg.substr(50, 2);
                    if (ac != "00") {
                        device.on_off = true
                        // brightnessSlider.slider("setValue", msg2);
                    } else {
                        device.on_off = false
                    }
                    //制冷温度
                    var cooltempdata = msg.substr(52, 2);
                    device.cooltmp = parseInt("0x" + cooltempdata);

                    //制热温度
                    var heattempdata = msg.substr(60, 2);
                    device.heattmp = parseInt("0x" + heattempdata);

                    //自动温度
                    var autotempdata = msg.substr(64, 2);
                    device.autotmp = parseInt("0x" + autotempdata);

                    //模式
                    var mode = msg.substr(54, 2);
                    if (
                        [
                            "00",
                            "01",
                            "02",
                            "03",
                            "04",
                            "05",
                            "06",
                            "07",
                            "08",
                            "09"
                        ].indexOf(mode) != -1
                    ) {
                        device.mode = 'cool'
                    } else if (
                        [
                            "10",
                            "11",
                            "12",
                            "13",
                            "14",
                            "15",
                            "16",
                            "17",
                            "18",
                            "19"
                        ].indexOf(mode) != -1
                    ) {
                        device.mode = 'heat'
                    } else if (
                        [
                            "20",
                            "21",
                            "22",
                            "23",
                            "24",
                            "25",
                            "26",
                            "27",
                            "28",
                            "29"
                        ].indexOf(mode) != -1
                    ) {
                        device.mode = 'fan'
                    } else {
                        device.mode = 'auto'
                    }

                    //风量大小
                    var fan = msg.substr(56, 2);
                    device.wind = parseInt("0x" + fan);
                    //local flag
                    var flag = msg.substr(58, 2);
                    //当前温度current
                    var current = msg.substr(58, 2);
                    device.tmp = parseInt("0x" + current)
                } else if (msg1.toLowerCase() == "e3d9") {
                    var type = msg.substr(50, 2);
                    var value = msg.substr(52, 2);
                    switch (type) {
                        case "03":
                            if (value == "01") {
                                device.on_off = true
                            } else {
                                device.on_off = false
                            }
                            break;
                        case "04":
                            device.cooltmp = parseInt("0x" + value);
                            break;
                        case "05":
                            device.wind = parseInt("0x" + value);
                            break;
                        case "06":
                            value = parseInt("0x" + value);
                            switch (value) {
                                case 0:
                                    device.mode = "cool"
                                    break;
                                case 1:
                                    device.mode = "heat"
                                    break;
                                case 2:
                                    device.mode = "fan"
                                    break;
                                case 3:
                                    device.mode = "auto"
                                    break;
                            }
                            break;
                        case "07":
                            device.heattmp = parseInt("0x" + value);
                            break;
                        case "08":
                            device.autotmp = parseInt("0x" + value);
                            break;
                    }
                }
            }
        });
    },
}

export default acApi;
