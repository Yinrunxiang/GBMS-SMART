<template>
    <div  v-loading="recordLoading" class="w-100p h-100p">
      <el-row :gutter="20" class="p-20">
        <el-col class="m-b-10" :xs = "24" :md = "{span: 5}">
          <el-date-picker class="w-100p"
          v-model="beginDate"
          type="date"
          value-format = "yyyy-MM-dd"
          placeholder="Begin date" 
          @change="beginChange">
          </el-date-picker>
        </el-col>
        <el-col class="m-b-10" :xs = "24" :md = "{span: 5}">
          <el-date-picker class="w-100p"
            v-model="endDate"
            type="date"
            value-format = "yyyy-MM-dd"
            placeholder="End date" 
            @change="endChange">
          </el-date-picker>
        </el-col>
        <el-col class="m-b-10" :xs = "24" :md = "{span: 5}">
            <el-cascader class="w-100p" :options="allAddress" change-on-select @change="addressChange"></el-cascader>
          </el-col>
          <el-col class="m-b-10" :xs = "24" :md = "{span: 5}">
            <el-select class="w-100p" v-model="type" placeholder="Select" @change="typeChange">
              <el-option
                v-for="item in typeList"
                :key="item.value"
                :label="item.label"
                :value="item.value"
                >
              </el-option>
            </el-select>
          </el-col>
          <el-col class="m-b-10" :xs = "24" :md = "{span: 2}">
             <el-button class="w-100p" type="primary" icon="el-icon-search"></el-button>
          </el-col>
      </el-row>
      <el-row >
        <el-col class="m-b-10" :xs = "24" :md = "{span: 10,offset:1}" :lg = "{span: 8,offset:1}">
             <div ref="lineChart" class="line-chart fl"></div>
          </el-col>
          <el-col class="m-b-10" :xs = "24" :md = "{span: 10,offset:1}" :lg = "{span: 8,offset:1}">
             <div ref="pieChart" class="pie-chart fl"></div>
          </el-col>
      </el-row>
    </div>
</template>
<style>
  /* .line-chart {
  width: 100%;
  height: 100%;
  margin: 0 auto;
}
  .pie-chart {
  width: 100%;
  height: 100%;
  margin: 0 auto;
} */
</style>
<script>
import echarts from "echarts/lib/echarts";
// 引入折线图和饼状图
require("echarts/lib/chart/line");
require("echarts/lib/chart/bar");
import recordApi from "./api";
export default {
  data() {
    return {
      beginDate: "",
      endDate: "",
      dateList: [],
      month: "",
      type: "watts",
      typeList: [
        {
          value: "watts",
          label: "Watts"
        },
        {
          value: "usd",
          label: "USD"
        }
      ],
      dateRecord: [],
      // tableData: [],
      // dataCount: null,
      monthRecord: [],
      selectRecord: [],
      allRecord: [],
      currentPage: null,
      keywords: "",
      thisdevice: {},
      notHotel: true,
      multipleSelection: [],
      limit: 15,
      showDeviceUpdate: false,
      lineChart: {},
      pieChart: {}
    };
  },
  methods: {
    // monthChange(val) {
    //   var selectRecord = [];
    //   for (var record of this.record) {
    //     //   var recordData = Date.parse(new Date(record.record_date))
    //     //   var month = Date.parse(new Date(val))
    //       var recordData = Date.parse(new Date(record.record_date))
    //       var month = Date.parse(new Date(val))
    //     //   if()
    //   }
    // },
    beginChange(val) {
      this.beginDate = val;
      this.getDate(this.beginDate, this.endDate);
      var selectRecord = [];
      var beginDate = Date.parse(new Date(val + " 00:00"));
      var endDate = Date.parse(new Date(this.endDate + " 23:59"));
      recordApi.getRecord(val + " 00:00", this.endDate + " 23:59", this);
      // this.dateRecord = this.record;
      // this.selectRecord = this.record;
    },
    endChange(val) {
      this.endDate = val;
      this.getDate(this.beginDate, this.endDate);
      var selectRecord = [];
      var beginDate = Date.parse(new Date(this.beginDate + " 00:00"));
      var endDate = Date.parse(new Date(val + " 23:59"));
      recordApi.getRecord(this.beginDate + " 00:00", val + " 23:59", this);
      // this.selectRecord = this.record;
    },
    typeChange(val) {
      // this.type = val
      this.setLineChart();
      this.setPieChart();
    },
    addressChange(value) {
      var selectRecord = [];
      var len = value.length;
      this.$store.dispatch("setRecord", []);
      switch (len) {
        case 1:
          for (var record of this.currentRecord) {
            if (record.address == value[0]) {
              selectRecord.push(record);
            }
          }
          break;
        case 2:
          for (var record of this.currentRecord) {
            if (record.address == value[0] && record.floor == value[1]) {
              selectRecord.push(record);
            }
          }
          break;
        case 3:
          for (var record of this.currentRecord) {
            if (
              record.address == value[0] &&
              record.floor == value[1] &&
              record.room == value[2]
            ) {
              selectRecord.push(record);
            }
          }
          break;
      }
      this.$store.dispatch("setRecord", selectRecord);
    },
    getDate(start, end) {
      var dateList = [];
      function initDate(datestr) {
        // var temp = datestr.split("-");
        // var date = new Date(temp[0], temp[1], temp[2]);
        var date = new Date(datestr);
        return date;
      }
      function formatDate(date) {
        var date = date.toString();
        if (date.length == 1) {
          date = "0" + date;
        }
        return date;
      }
      var startTime = initDate(start);
      var endTime = initDate(end);
      while (endTime.getTime() - startTime.getTime() >= 0) {
        var year = startTime.getFullYear();
        var month = formatDate(startTime.getMonth() + 1);
        var day = formatDate(startTime.getDate());
        for (var h = 0; h < 24; h++) {
          var date = year + "-" + month + "-" + day + " " + formatDate(h);

          dateList.push(date);
        }
        // console.log(startTime)
        startTime.setDate(startTime.getDate() + 1);
      }
      this.dateList = dateList;
    },
    initMapSize(chart) {
      var width,height
      if (document.body.clientWidth < 992) {
        width = document.body.clientWidth;
        height = 300;
      }else{
        width = (document.body.clientWidth - 180) * 0.4;
        height = (document.body.clientHeight - 60) * 0.8;
      }
      chart.style.width = width + "px";
      chart.style.height = height + "px";
    },
    initLineChart() {
      var chart = this.$refs.lineChart;
      this.initMapSize(chart);
      this.lineChart = echarts.init(chart);
      // var base = new Date(2017, 1, 1);
      // var oneDay = 24 * 3600 * 1000;
      // var date = [];

      // var data = [Math.random() * 300];

      // for (var i = 1; i < 2000; i++) {
      //     var now = new Date(base += oneDay);
      //     date.push([now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'));
      //     data.push(Math.round((Math.random() - 0.5) * 20 + data[i - 1]));
      // }
    },
    initPieChart() {
      var chart = this.$refs.pieChart;
      this.initMapSize(chart);
      this.pieChart = echarts.init(chart);
    },
    setLineChart() {
      this.lineChart.clear();
      var date = this.allRecord.dateArr;
      var data = [];
      if (this.type == "watts") {
        data = this.allRecord.recordArr;
      } else {
        data = this.allRecord.usdArr;
      }

      var option = {
        tooltip: {
          trigger: "axis",
          position: function(pt) {
            return [pt[0], "10%"];
          },
          formatter: this.type == "watts" ? "{b} : {c}kw" : "{b} : {c}usd"
        },
        title: {
          left: "center",
          text: "Line Chart"
        },
        xAxis: {
          type: "category",
          boundaryGap: false,
          data: date
        },
        yAxis: {
          type: "value",
          boundaryGap: [0, "100%"]
        },
        dataZoom: [
          {
            type: "inside",
            start: 0,
            end: 100
          },
          {
            start: 0,
            end: 10,
            handleIcon:
              "M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z",
            handleSize: "80%",
            handleStyle: {
              color: "#fff",
              shadowBlur: 3,
              shadowColor: "rgba(0, 0, 0, 0.6)",
              shadowOffsetX: 2,
              shadowOffsetY: 2
            }
          }
        ],
        series: [
          {
            type: "line",
            smooth: true,
            symbol: "none",
            sampling: "average",
            itemStyle: {
              normal: {
                color: "rgb(255, 70, 131)"
              }
            },
            areaStyle: {
              normal: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                  {
                    offset: 0,
                    color: "rgb(255, 158, 68)"
                  },
                  {
                    offset: 1,
                    color: "rgb(255, 70, 131)"
                  }
                ])
              }
            },
            data: data
          }
        ]
      };
      this.lineChart.setOption(option);
    },
    setPieChart() {
      this.pieChart.clear();
      var option = {
        backgroundColor: "#f1f2f7",
        title: {
          text: "Pie Chart",
          x: "center"
        },
        tooltip: {
          trigger: "item",
          formatter:
            this.type == "watts" ? "{b} : {c}kw ({d}%)" : "{b} : {c}usd ({d}%)"
        },
        // legend: {
        //   orient: "vertical",
        //   left: "left",
        //   data: ["直接访问", "邮件营销", "联盟广告", "视频广告", "搜索引擎"]
        // },
        color: [
          "#61a0a8",
          "#d48265",
          "#91c7ae",
          "#749f83",
          "#ca8622",
          "#bda29a",
          "#6e7074"
        ],
        series: [
          {
            name: "",
            type: "pie",
            radius: "55%",
            center: ["50%", "60%"],
            data:
              this.type == "watts"
                ? this.allRecord.typeWattsArr.sort(function(a, b) {
                    return a.value - b.value;
                  })
                : this.allRecord.typeUsdArr.sort(function(a, b) {
                    return a.value - b.value;
                  }),
            itemStyle: {
              emphasis: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: "rgba(0, 0, 0, 0.5)"
              }
            }
          }
        ]
      };

      this.pieChart.setOption(option);
    },
    setPieChart1() {
      this.pieChart.clear();
      var option = {
        backgroundColor: "#f1f2f7",

        title: {
          text: "Pie Chart",
          left: "center",
          top: 20,
          textStyle: {
            color: "#000"
          }
        },

        tooltip: {
          trigger: "item",
          formatter:
            this.type == "watts" ? "{b} : {c}kw ({d}%)" : "{b} : {c}usd ({d}%)"
        },

        visualMap: {
          show: false,
          min:
            this.type == "watts"
              ? this.allRecord.wattSort[0] * 0.8
              : this.allRecord.usdSort[0] * 0.8,
          max:
            this.type == "watts"
              ? this.allRecord.wattSort[this.allRecord.wattSort.length - 1] *
                1.5
              : this.allRecord.usdSort[this.allRecord.usdSort.length - 1] * 1.5,
          inRange: {
            colorLightness: [0, 1]
          }
        },
        series: [
          {
            type: "pie",
            radius: "55%",
            center: ["50%", "50%"],
            data:
              this.type == "watts"
                ? this.allRecord.typeWattsArr.sort(function(a, b) {
                    return a.value - b.value;
                  })
                : this.allRecord.typeUsdArr.sort(function(a, b) {
                    return a.value - b.value;
                  }),
            // data: [
            //     { value: 335, name: 'Light' },
            //     { value: 310, name: 'LED' },
            //     { value: 274, name: 'Music' },
            //     { value: 235, name: 'Other' },
            //     { value: 400, name: 'AC' }
            // ].sort(function(a, b) { return a.value - b.value; }),
            roseType: "radius",
            label: {
              normal: {
                textStyle: {
                  color: "rgb(0, 0, 0)"
                }
              }
            },
            labelLine: {
              normal: {
                lineStyle: {
                  color: "rgba(255, 255, 255, 0.3)"
                },
                smooth: 0.2,
                length: 10,
                length2: 20
              }
            },
            itemStyle: {
              normal: {
                color: "#c23531"
                // shadowBlur: 200,
                // shadowColor: 'rgba(0, 0, 0, 0.5)'
              }
            },

            animationType: "scale",
            animationEasing: "elasticOut",
            animationDelay: function(idx) {
              return Math.random() * 200;
            }
          }
        ]
      };
      this.pieChart.setOption(option);
    }
  },
  created() {
    var myData = new Date();
    var year = myData.getFullYear();
    var month = myData.getMonth() + 1;
    var lastDate = new Date(year, month, 0).getDate();
    month = month < 10 ? "0" + month : month;
    this.beginDate = year + "-" + month + "-01";
    this.endDate = year + "-" + month + "-" + lastDate;
    this.beginChange(this.beginDate);
    // this.endChange(this.endDate);
    // month = month < 10 ? "0" + month : month;
    // this.month = myData.getFullYear() + "-" + month;
    console.log("Report");
  },
  mounted() {
    this.initLineChart();
    this.initPieChart();

    // console.log(this.allRecord)
  },
  components: {},
  watch: {
    record: {
      handler: function(val, oldVal) {
        this.allRecord = {}
        var allRecord = {};
        allRecord.dateArr = this.dateList;
        allRecord.recordArr = [];
        allRecord.usdArr = [];
        for (var i = 0; i < allRecord.dateArr.length; i++) {
          allRecord.recordArr.push(0);
          allRecord.usdArr.push(0);
        }

        allRecord.typeWattsArr = [];
        allRecord.typeUsdArr = [];
        var typeWattsArr = {};
        var typeUsdArr = {};
        // console.log(this.selectRecord)
        for (var record of val) {
          //   var date = record.record_date.substr(0, 15) + "0";
          var date = record.record_date.substr(0, 13);
          var index = allRecord.dateArr.indexOf(date);
          if (index != -1) {
            allRecord.recordArr[index] += parseFloat(record.watts);
            allRecord.recordArr[index] = parseFloat(
              allRecord.recordArr[index].toFixed(2)
            );
            allRecord.usdArr[index] += parseFloat(record.usd);
            allRecord.usdArr[index] = parseFloat(
              allRecord.usdArr[index].toFixed(2)
            );
            //   if (allRecord.dateArr.indexOf(date) == -1) {
            //     allRecord.dateArr.push(date);
            //     allRecord.recordArr.push(parseInt(record.watts));
            //     allRecord.usdArr.push(parseInt(record.usd));
            //   } else {
            //     var index = allRecord.dateArr.indexOf(date);
            //     allRecord.recordArr[index] += parseInt(record.watts);
            //     allRecord.usdArr[index] += parseInt(record.usd);
            //   }

            typeWattsArr[record.devicetype]
              ? (typeWattsArr[record.devicetype] += parseFloat(record.watts))
              : (typeWattsArr[record.devicetype] = parseFloat(record.watts));
            typeWattsArr[record.devicetype] = parseFloat(
              typeWattsArr[record.devicetype].toFixed(2)
            );
            typeUsdArr[record.devicetype]
              ? (typeUsdArr[record.devicetype] += parseFloat(record.usd))
              : (typeUsdArr[record.devicetype] = parseFloat(record.usd));
            typeUsdArr[record.devicetype] = parseFloat(
              typeUsdArr[record.devicetype].toFixed(2)
            );
            // if (typeArr.indexOf(record.devicetype) == -1){
            //     typeArr[record.devicetype] = parseInt(record.watts)
            // }else{
            //     typeArr[record.devicetype] += parseInt(record.watts)
            // }
          }
        }
        var wattSort = [];
        var usdSort = [];
        for (var key in typeWattsArr) {
          var typeObj = {};
          typeObj.name = key;
          typeObj.value = typeWattsArr[key];
          wattSort.push(typeWattsArr[key]);
          allRecord.typeWattsArr.push(typeObj);
        }
        for (var key in typeUsdArr) {
          var typeObj = {};
          typeObj.name = key;
          typeObj.value = typeUsdArr[key];
          usdSort.push(typeUsdArr[key]);
          allRecord.typeUsdArr.push(typeObj);
        }
        function sortNumber(a, b) {
          return a - b;
        }
        wattSort.sort(sortNumber);
        usdSort.sort(sortNumber);
        allRecord.wattSort = wattSort;
        allRecord.usdSort = usdSort;
        this.allRecord = allRecord;
        this.setLineChart();
        this.setPieChart();
      },
      deep: true
    },
  },
  computed: {
    record() {
      return this.$store.state.record;
    },
    currentRecord() {
      return this.$store.state.currentRecord;
    },
    recordLoading() {
      return this.$store.state.recordLoading;
    },
    // recordLoading() {
    //   // return this.$store.state.recordLoading
    //   var recordLoading = this.$store.state.recordLoading;
    //   if (!recordLoading) {
    //     this.selectRecord = this.$store.state.record;
    //     return false;
    //   }
    //   return true;
    // },
   allAddress() {
      var allAddress = [];
      for (var address of this.$store.state.address) {
        var addressObj = {
          value: address.id,
          label: address.address,
          children: []
        };
        for (var floor of this.$store.state.floor) {
          if (floor.address == address.id) {
            var floorObj = {
              value: floor.id,
              label: "floor " + floor.floor,
              children: []
            };
            for (var room of this.$store.state.room) {
              if (room.floor == floor.id && room.address == address.id) {
                var roomObj = {
                  value: room.id,
                  label: room.room_name
                };
                floorObj.children.push(roomObj);
              }
            }
            addressObj.children.push(floorObj);
          }
        }
        allAddress.push(addressObj);
      }
      // console.log(allAddress)
      return allAddress;
    }
  },
};
</script>