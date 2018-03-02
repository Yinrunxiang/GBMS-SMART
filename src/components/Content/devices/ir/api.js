import api from "../../../../assets/js/api";
const lightApi = {
  switch_change(val, device) {
    if (val) {
      if (device.operation_6) {
        const data = {
          params: {
            operatorCodefst: "00",
            operatorCodesec: "31",
            targetSubnetID: device.subnetid,
            targetDeviceID: device.deviceid,
            additionalContentData: (
              _g.toHex(device.operation_6) +
              ",64").split(","),
            macAddress: device.mac ? device.mac.split(".") : "",
            dest_address: device.ip ? device.ip : "",
            dest_port: device.port ? device.port : ""
          }
        };
        api.apiGet("udp/sendUdp.php", data).then(res => {
          // console.log("res = ", _g.j2s(res));
          // _g.closeGlobalLoading()
        });
      }
    } else {
      if (device.operation_6) {
        const data = {
          params: {
            operatorCodefst: "00",
            operatorCodesec: "31",
            targetSubnetID: device.subnetid,
            targetDeviceID: device.deviceid,
            additionalContentData: (
              _g.toHex(device.operation_7) +
              ",64").split(","),
            macAddress: device.mac ? device.mac.split(".") : "",
            dest_address: device.ip ? device.ip : "",
            dest_port: device.port ? device.port : ""
          }
        };
        api.apiGet("udp/sendUdp.php", data).then(res => {
          // console.log("res = ", _g.j2s(res));
          // _g.closeGlobalLoading()
        });
      }
    }
  },
  buttonClick(device, value) {
    const data = {
      params: {
        operatorCodefst: "00",
        operatorCodesec: "31",
        targetSubnetID: device.subnetid,
        targetDeviceID: device.deviceid,
        additionalContentData: (_g.toHex(value) + ",64").split(","),
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : ""
      }
    };
    console.log(value+' '+_g.toHex(value))
    api.apiGet("udp/sendUdp.php", data).then(res => {
      // console.log(res);
      // _g.closeGlobalLoading()
    });
  }
}

export default lightApi;
