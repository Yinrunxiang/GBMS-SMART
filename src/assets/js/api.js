const api = {
  apiGet(url, data) {
    return new Promise((resolve, reject) => {
      axios.get(url, data).then((response) => {
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
  getUdp(device, operatorCodefst, operatorCodesec, additionalContentData) {
    const data = {
      params: {
        operatorCodefst: operatorCodefst,
        operatorCodesec: operatorCodesec,
        targetSubnetID: device.subnetid,
        targetDeviceID: device.deviceid,
        additionalContentData: additionalContentData,
        macAddress: device.mac ? device.mac.split(".") : "",
        dest_address: device.ip ? device.ip : "",
        dest_port: device.port ? device.port : "",
        udp_type :device.udp_type?device.udp_type:""
      }
    }
    return data
  }
}
export default api;