
const api = {
    apiPost(url, data) {
        console.log(data)
        return new Promise((resolve, reject) => {
            axios.post(url, data).then((response) => {
                resolve(response.data)
            }, (response) => {
                reject(response)
                _g.closeGlobalLoading()
                // bus.$message({
                //   message: 'Request timeout, please check the network',
                //   type: 'warning'
                // })
            })
        })
    },
    //UDP重发机制

    getUdp(device, operatorCodefst, operatorCodesec, additionalContentData) {
        const data = {
            operatorCodefst: operatorCodefst,
            operatorCodesec: operatorCodesec,
            targetSubnetID: device.subnetid,
            targetDeviceID: device.deviceid,
            additionalContentData: additionalContentData,
            macAddress: device.mac ? device.mac.split(".") : "",
            dest_address: device.ip ? device.ip : "",
            dest_port: device.port ? device.port : "",
            udp_type: device.udp_type ? device.udp_type : ""
        }
        return data
    },
    sendUdp(device, data, type) {
        if (!type || type == "") {
            api.apiPost("admin/udp/sendUdp", data).then(res => {
                // console.log(res)
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
            // window.socketio.on("new_msg", function (msg) {
            //     var subnetid = msg.substr(34, 2);
            //     var deviceid = msg.substr(36, 2);
            //     if (
            //         subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
            //         deviceid.toLowerCase() != device.deviceid.toLowerCase()
            //     ) return
            //     var operatorCodeCurrent = msg.substr(42, 4)
            //     if (operatorCodeCurrent != operatorCode) return
            //     pass = true
            // })
            var sendUdp = setInterval(function () {
                var udpDevice = window.store.state.udpDevice
                if (udpDevice.subnetid == device.subnetid.toLowerCase() && udpDevice.deviceid == device.deviceid.toLowerCase() && udpDevice.operatorCode == operatorCode) {
                    pass = true
                }

                if (pass || index > 3) {
                    clearInterval(sendUdp);
                    return;
                }
                console.log(data)
                api.apiPost("admin/udp/sendUdp", data).then(res => {
                });
                index++
            }, 700);
        }
    },
    sendUdpArr(arr) {
        var $this = this
        var arrIndex = 0
        var sendUdp = function (device, data, type) {
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
                // window.socketio.on("new_msg", function (msg) {
                //     var subnetid = msg.substr(34, 2);
                //     var deviceid = msg.substr(36, 2);
                //     if (
                //         subnetid.toLowerCase() != device.subnetid.toLowerCase() ||
                //         deviceid.toLowerCase() != device.deviceid.toLowerCase()
                //     ) return
                //     var operatorCodeCurrent = msg.substr(42, 4)
                //     if (operatorCodeCurrent != operatorCode) return
                //     if (operatorCodeCurrent == '0032') {
                //         var channel = _g.getadditional(msg, 0)
                //         if (device.channel != channel) return
                //     }
                //     pass = true
                // })

                var sendUdpFor = setInterval(function () {
                    var udpDevice = window.store.state.udpDevice
                    if (udpDevice.subnetid == device.subnetid.toLowerCase() && udpDevice.deviceid == device.deviceid.toLowerCase() && udpDevice.operatorCode == operatorCode) {
                        if (device.channel) {
                            if (udpDevice.channel == device.channel.toLowerCase()) {
                                pass = true
                            }
                        }
                    }
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
                    console.log(data)
                    api.apiPost("admin/udp/sendUdp", data).then(res => {
                    });
                    index++
                }, 700);
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
export default api;