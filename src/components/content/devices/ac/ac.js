import api from "../../../../assets/js/api";
const acApi = {
    get_switch_change(val, device, deviceProperty) {
        if (val) {
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetSubnetID: device.subnetid,
                    targetDeviceID: device.deviceid,
                    additionalContentData: ("03,01").split(","),
                    macAddress: device.mac ? device.mac.split(".") : "",
                    dest_address: device.ip ? device.ip : "",
                    dest_port: device.port ? device.port : "",
                }
            }
            return data
        } else {
            const data = {
                params: {
                    operatorCodefst: "E3",
                    operatorCodesec: "D8",
                    targetSubnetID: device.subnetid,
                    targetDeviceID: device.deviceid,
                    additionalContentData: ("03,00").split(","),
                    macAddress: device.mac ? device.mac.split(".") : "",
                    dest_address: device.ip ? device.ip : "",
                    dest_port: device.port ? device.port : "",
                }
            }
            return data
        }
    },
    get_autotmp_change(val, device, deviceProperty) {
        const data = {
            params: {
                operatorCodefst: "E3",
                operatorCodesec: "D8",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("08," + _g.toHex(val)).split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        return data
    },
    get_cooltmp_change(val, device, deviceProperty) {
        const data = {
            params: {
                operatorCodefst: "E3",
                operatorCodesec: "D8",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("04," + _g.toHex(val)).split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        return data
    },
    get_heattmp_change(val, device, deviceProperty) {
        const data = {
            params: {
                operatorCodefst: "E3",
                operatorCodesec: "D8",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("07," + _g.toHex(val)).split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        return data
    },
    get_wind_change(val, device, deviceProperty) {
        const data = {
            params: {
                operatorCodefst: "E3",
                operatorCodesec: "D8",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("05," + _g.toHex(val)).split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        return data
    },
    get_autobtn(device, deviceProperty) {
        const data = {
            params: {
                operatorCodefst: "E3",
                operatorCodesec: "D8",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("06,03").split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        return data
    },
    get_fanbtn(device, deviceProperty) {
        const data = {
            params: {
                operatorCodefst: "E3",
                operatorCodesec: "D8",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("06,02").split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        return data
    },
    get_coolbtn(device, deviceProperty) {
        const data = {
            params: {
                operatorCodefst: "E3",
                operatorCodesec: "D8",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("06,00").split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        return data
    },
    get_heatbtn(device, deviceProperty) {
        const data = {
            params: {
                operatorCodefst: "E3",
                operatorCodesec: "D8",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("06,01").split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        return data
    },
    switch_change(val, device, deviceProperty) {
        const data = this.get_switch_change(val, device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    autotmp_change(val, device, deviceProperty) {

        const data = this.get_autotmp_change(val, device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    cooltmp_change(val, device, deviceProperty) {

        const data = this.get_cooltmp_change(val, device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    heattmp_change(val, device, deviceProperty) {

        const data = this.get_heattmp_change(val, device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    wind_change(val, device, deviceProperty) {

        const data = this.get_wind_change(val, device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    autobtn(device, deviceProperty) {
        const data = this.get_autobtn(device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    fanbtn(device, deviceProperty) {
        const data = this.get_fanbtn(device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    coolbtn(device, deviceProperty) {
        const data = this.get_coolbtn(device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    heatbtn(device, deviceProperty) {
        const data = this.get_heatbtn( device, deviceProperty)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
    },
    readStatus(device, deviceProperty) {
        let data = {
            params: {
                operatorCodefst: "E0",
                operatorCodesec: "EC",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("00").split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        // console.log(device)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
        // var socket = window.socket("http://" + document.domain + ":2120");
        // window.socketio.removeAllListeners("new_msg");
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
                        deviceProperty.on_off = true
                        // brightnessSlider.slider("setValue", msg2);
                    } else {
                        deviceProperty.on_off = false
                    }
                    //制冷温度
                    var cooltempdata = msg.substr(52, 2);
                    deviceProperty.cooltmp = parseInt("0x" + cooltempdata);

                    //制热温度
                    var heattempdata = msg.substr(60, 2);
                    deviceProperty.heattmp = parseInt("0x" + heattempdata);

                    //自动温度
                    var autotempdata = msg.substr(64, 2);
                    deviceProperty.autotmp = parseInt("0x" + autotempdata);

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
                    var current = msg.substr(60, 2);
                    deviceProperty.tmp = parseInt("0x" + current)
                } else if (msg1.toLowerCase() == "e3d9") {
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
                            deviceProperty.cooltmp = parseInt("0x" + value);
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
                            deviceProperty.heattemp = parseInt("0x" + value);
                            break;
                        case "08":
                            deviceProperty.autotemp = parseInt("0x" + value);
                            break;
                    }
                }
            }
        });
    },
    readOpen(device) {
        let data = {
            params: {
                operatorCodefst: "E0",
                operatorCodesec: "EC",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: ("00").split(","),
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : "",
            }
        }
        // console.log(device)
        api.apiGet('udp/sendUdp.php', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            // _g.closeGlobalLoading()
        })
        // var socket = window.socket("http://" + document.domain + ":2120");
        // window.socketio.removeAllListeners("new_msg");
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
                    var current = msg.substr(60, 2);
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
                            device.heattemp = parseInt("0x" + value);
                            break;
                        case "08":
                            device.autotemp = parseInt("0x" + value);
                            break;
                    }
                }
            }
        });
    },
}

export default acApi;
