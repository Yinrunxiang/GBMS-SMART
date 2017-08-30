const api = {
apiGet(url, data) {
    return new Promise((resolve, reject) => {
      axios.get(url, data).then((response) => {
        resolve(response.data)
      }, (response) => {
        reject(response)
        _g.closeGlobalLoading()
        bus.$message({
          message: 'Request timeout, please check the network',
          type: 'warning'
        })
      })
    })
  },
}
export default api;