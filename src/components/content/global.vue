<template>
    <div class="panel m-w-1100" v-loading="globalLoading">
        <div ref="worldmap" id="world-map"></div>
        <!-- <div ref="chart" id="chart"></div> -->
    </div>
</template>

<style>
/* #world-map,#chart{
    width:1180px; 
    height: 100%;
    margin: 0 auto;
} */
</style>


<script>
// import pace from 'pace-progress'
// import 'pace-progress/themes/blue/pace-theme-center-atom.css'

// window.paceOptions = {
//   ajax: {
//     trackMethods: ['GET', 'POST']
//   },
//   eventLag: false,
//   elements: false
// }
import http from "../../assets/js/http";
import echarts from "echarts";
// import 'echarts/lib/chart/map';
import "echarts/map/js/world.js";

export default {
  data() {
    return {
      address: {}
      // record : this.$store.state.record
      // devices: this.$store.state.devices,
    };
  },
  props: ["dataReady"],
  methods: {
    initMapSize() {
      var width = document.body.clientWidth - 180;
      var height = document.body.clientHeight - 60;
      var map = this.$refs.worldmap;
      map.style.width = width + "px";
      map.style.height = height + "px";
    },
    addressClick(addressName) {
      for (var country of this.countryArr) {
        for (var address of country.addressList) {
          if (address.name == addressName) {
            this.address = address;
          }
        }
      }
      let url = "/home/global/hotel";
      router.push({ path: url, query: { address: this.address } });
    },
    //  addressClick(addressName) {
    //     for (var country of this.countryArr) {
    //         for (var address of country.addressList) {
    //             if (address.name == addressName) {
    //                 this.address = address
    //             }
    //         }

    //     }
    //     var floorList = []
    //     var floor_num = 0
    //     for (var address of this.allAddress) {
    //         if (address.address == addressName) {
    //             floor_num = address.floor_num ? parseInt(address.floor_num) : 0
    //         }
    //     }
    //     for (var i = 0; i < floor_num; i++) {
    //         var obj = {
    //             name: i
    //         }
    //         floorList.push(obj)
    //     }
    //     for (var floor of this.address.floorList) {
    //         for (var floor_t of floorList) {
    //             if (floor.name == floor_t.name) {
    //                 floor_t = floor
    //             }
    //         }

    //     }
    //     this.address.floorList = floorList
    //     console.log(this.address)
    //     let url = '/home/global/hotel'
    //     router.push({ path: url, query: { address: this.address } })
    // },
    //悬浮提示数据
    itCounteyTooltip(params) {
      var itCounteyTooltip = "Building : " + params.name + "<br/>";
      for (var country of this.countryArr) {
        for (var address of country.addressList) {
          if (address.name == params.name) {
            // for (var floor of address.floorList) {
            //     for (var room of floor.roomList) {
            //         for (var type of room.typeList) {
            //             itCounteyTooltip += type.name + ' : ' + address.deviceTypeNumber[type.name] + '<br/>'
            //         }
            //     }
            // }
            for (var type in address.deviceTypeNumber) {
              itCounteyTooltip +=
                type + " : " + address.deviceTypeNumber[type] + "<br/>";
            }
            itCounteyTooltip += "Watts: " + parseFloat(address.watts.toFixed(2)) + " W<br/>";
            itCounteyTooltip += "Cost : " + parseFloat(address.usd.toFixed(2)) + " USD<br/>";
          }
        }
      }

      return itCounteyTooltip;
    },
    initData() {
      var mapData = [];
      for (var address of this.allAddress) {
        var mapObj = {};
        mapObj.name = address.address;
        mapObj.value = [address.lng, address.lat];
        mapData.push(mapObj);
      }
      // console.log(mapData)
      return mapData;
    },
    //创建地图
    createmap(e) {
      var myChart = echarts.init(this.$refs.worldmap);
      var option = {
        backgroundColor: new echarts.graphic.RadialGradient(0.5, 0.5, 0.4, [
          {
            offset: 0,
            color: "#4b5769"
          },
          {
            offset: 1,
            color: "#404a59"
          }
        ]),
        tooltip: {
          trigger: "item",
          formatter: function(params) {
            var str = e.itCounteyTooltip(params);
            return str;
          }
        },
        toolbox: {
          show: true,
          left: "right",
          iconStyle: {
            normal: {
              borderColor: "#ddd"
            }
          },
          feature: {},
          z: 202
        },
        geo: {
          map: "world",
          silent: true,
          label: {
            emphasis: {
              show: false,
              areaColor: "#eee"
            }
          },
          itemStyle: {
            normal: {
              borderWidth: 0.2,
              borderColor: "#404a59"
            }
          },
          left: "6%",
          top: 50,
          bottom: "10%",
          right: "6%",
          roam: true
        },

        series: [
          {
            name: "",
            type: "scatter",
            coordinateSystem: "geo",
            symbolSize: 8,
            data: this.initData(),
            activeOpacity: 1,
            label: {
              normal: {
                formatter: "{b}",
                position: "right",
                show: false
              }
            },
            symbolSize: 10,
            itemStyle: {
              normal: {
                borderColor: "#fff",
                color: "#577ceb"
              }
            }
          }
        ]
      };

      myChart.setOption(option);
      myChart.on("click", function(params) {
        var dataIndex = params.dataIndex;
        // console.log(params);
        e.addressClick(params.data.name);
      });
    },
    init() {
      this.initMapSize();
      this.createmap(this);
      _g.closeGlobalLoading();
    }
  },
  mounted() {
    console.log("global");
    var vm = this;
    var time = setInterval(function() {
      if (vm.dataReady) {
        vm.init();
        clearInterval(time);
      }
    }, 100);
  },
  components: {},
  computed: {
    devices() {
      return this.$store.state.devices;
    },
    allAddress() {
      return this.$store.state.address;
    },
    allFloor() {
      return this.$store.state.floor;
    },
    allRoom() {
      return this.$store.state.room;
    },
    //计算该国家的设备类型，各种设备类型的数量，生成国家数组让地图调用
    countryArr() {
      return this.$store.state.countryArr;
    },
    globalLoading() {
      return store.state.globalLoading;
    }
  },
  // watch: {
  //     countryArr: {
  //         handler: function(val, oldVal) {
  //             if (this.countryArr.length > 0) {
  //                 this.initMapSize()
  //                 this.createmap(this)
  //                 _g.closeGlobalLoading()
  //             }
  //         },
  //         deep: true
  //     }
  // },

  mixins: [http]
};
</script>