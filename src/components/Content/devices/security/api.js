import api from "../api";
const lightApi = {
    socketio:{},
    get_command(val, device) {
        if (val.type == '04' || val.type == '08') {
            const data = {
                params: {
                    operatorCodefst: "01",
                    operatorCodesec: "0c",
                    targetSubnetID: device.subnetid,
                    targetDeviceID: device.deviceid,
                    additionalContentData: [val.area, val.type, '00', '00', val.area],
                    macAddress: device.mac ? device.mac.split(".") : "",
                    dest_address: device.ip ? device.ip : "",
                    dest_port: device.port ? device.port : ""
                }
            };
            return data
        } else {
            const data = {
                params: {
                    operatorCodefst: "01",
                    operatorCodesec: "04",
                    targetSubnetID: device.subnetid,
                    targetDeviceID: device.deviceid,
                    additionalContentData: [val.area, val.type],
                    macAddress: device.mac ? device.mac.split(".") : "",
                    dest_address: device.ip ? device.ip : "",
                    dest_port: device.port ? device.port : ""
                }
            };
            return data
        }

    },
    send_command(val, device) {
        const data = this.get_command(val, device)
        // console.log(data)
        api.sendUdp(device, data)
    },
    closeSocket(){
        this.socketio.removeAllListeners()
      },
    readStatus(val, device, deviceProperty) {
        let data = {
            params: {
                operatorCodefst: "01",
                operatorCodesec: "1e",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                additionalContentData: [val.area],
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : ""
            }
        };
        api.sendUdp(device, data)
        let userInfo = Lockr.get("userInfo");
    let port = userInfo.port;
        this.socketio = socket("http://" + document.domain + ":" + port);
        this.socketio.on("new_msg", function (msg) {
            var subnetid = msg.substr(34, 2);
            var deviceid = msg.substr(36, 2);
            var channel = msg.substr(52, 2);
            if (
                subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
                deviceid.toLowerCase() == device.deviceid.toLowerCase()
            ) {
                var msg1 = msg.substr(42, 4);
                if (msg1 == "0032") {
                    var channel = msg.substr(50, 2);
                    if (channel.toLowerCase() == device.channel.toLowerCase()) {
                        var msg2 = msg.substr(54, 2);
                        if (msg2 != "00") {
                            deviceProperty.on_off = true;
                        } else {
                            deviceProperty.on_off = false;
                            deviceProperty.brightness = 0;
                        }
                    }
                } else if (msg1 == "011f") {
                    var area = _g.getadditional(msg, 0)
                    var type = _g.getadditional(msg, 1)
                    if (area == val.area) {
                        deviceProperty.on_off = true;
                        deviceProperty.brightness = msg3;
                    }
                }
            }
        })
    },
    readOpen(device) {
        let data = {
            params: {
                operatorCodefst: "00",
                operatorCodesec: "33",
                targetSubnetID: device.subnetid,
                targetDeviceID: device.deviceid,
                macAddress: device.mac ? device.mac.split(".") : "",
                dest_address: device.ip ? device.ip : "",
                dest_port: device.port ? device.port : ""
            }
        };
        api.sendUdp(device, data)
        window.socketio.on("new_msg", function (msg) {
            var subnetid = msg.substr(34, 2);
            var deviceid = msg.substr(36, 2);
            var channel = msg.substr(52, 2);
            if (
                subnetid.toLowerCase() == device.subnetid.toLowerCase() &&
                deviceid.toLowerCase() == device.deviceid.toLowerCase()
            ) {
                var msg1 = msg.substr(42, 4);
                if (msg1 == "0032") {
                    var channel = msg.substr(50, 2);
                    if (channel.toLowerCase() == device.channel.toLowerCase()) {
                        var msg2 = msg.substr(54, 2);
                        if (msg2 != "00") {
                            device.on_off = true;
                        } else {
                            device.on_off = false;
                        }
                    }
                } else if (msg1 == "0034") {
                    var len = 50 + parseInt("0x" + device.channel) * 2;
                    var msg2 = msg.substr(len, 2);
                    if (msg2 != "00") {
                        device.on_off = true;
                    } else {
                        device.on_off = false;
                    }
                }
            }
        })
    }
}

export default lightApi;
