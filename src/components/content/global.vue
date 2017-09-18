<template>
    <div>
        <div ref="worldmap" id="world-map"></div>
        <!-- <div ref="chart" id="chart"></div> -->
    </div>
</template>

<<style>
/* #world-map,#chart{
    width:1180px; 
    height: 100%;
    margin: 0 auto;
} */
</style>


<script>
import http from '../../assets/js/http'
import echarts from 'echarts'
// import 'echarts/lib/chart/map';
import 'echarts/map/js/world.js';


export default {
    data() {
        return {
            address: {}
            // record : this.$store.state.record
            // devices: this.$store.state.devices,
        }
    },
    methods: {
        initMapSize() {
            var width = document.body.clientWidth - 180
            var height = document.body.clientHeight - 60
            var map = this.$refs.worldmap
            map.style.width = width + 'px';
            map.style.height = height + 'px';
            // console.log(map.style.width + map.style.height)

        },
        // createChart(record) {
        //     var myChart = echarts.init(this.$refs.chart);
        //     // var base = +new Date(1968, 9, 3);
        //     // var oneDay = 24 * 3600 * 1000;
        //     var date = record.dateArr
        //     var data = record.recordArr
        //     // var date = [];

        //     // // var data = [Math.random() * 300];

        //     // // for (var i = 1; i < 20000; i++) {
        //     // //     var now = new Date(base += oneDay);
        //     // //     date.push([now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'));
        //     // //     data.push(Math.round((Math.random() - 0.5) * 20 + data[i - 1]));
        //     // // }

        //     var option = {
        //         tooltip: {
        //             trigger: 'axis',
        //             position: function(pt) {
        //                 return [pt[0], '10%'];
        //             }
        //         },
        //         title: {
        //             left: 'center',
        //             text: 'Power Mete',
        //         },
        //         toolbox: {
        //             feature: {
        //                 dataZoom: {
        //                     yAxisIndex: 'none'
        //                 },
        //                 restore: {},
        //                 saveAsImage: {}
        //             }
        //         },
        //         xAxis: {
        //             type: 'category',
        //             boundaryGap: false,
        //             data: date
        //         },
        //         yAxis: {
        //             type: 'value',
        //             boundaryGap: [0, '100%']
        //         },
        //         dataZoom: [{
        //             type: 'inside',
        //             start: 0,
        //             end: 10
        //         }, {
        //             start: 0,
        //             end: 10,
        //             handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
        //             handleSize: '80%',
        //             handleStyle: {
        //                 color: '#fff',
        //                 shadowBlur: 3,
        //                 shadowColor: 'rgba(0, 0, 0, 0.6)',
        //                 shadowOffsetX: 2,
        //                 shadowOffsetY: 2
        //             }
        //         }],
        //         series: [
        //             {
        //                 name: '模拟数据',
        //                 type: 'line',
        //                 smooth: true,
        //                 symbol: 'none',
        //                 sampling: 'average',
        //                 itemStyle: {
        //                     normal: {
        //                         color: 'rgb(255, 70, 131)'
        //                     }
        //                 },
        //                 areaStyle: {
        //                     normal: {
        //                         color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
        //                             offset: 0,
        //                             color: 'rgb(255, 158, 68)'
        //                         }, {
        //                             offset: 1,
        //                             color: 'rgb(255, 70, 131)'
        //                         }])
        //                     }
        //                 },
        //                 data: data
        //             }
        //         ]
        //     };
        //     myChart.setOption(option);
        // },
        // countryChange(country) {
        //     var records = {}
        //     records.dateArr = []
        //     records.recordArr = []
        //     for (var record of this.$store.state.record) {
        //         if (record.country == country) {
        //             if (records.dateArr.indexOf(record.record_date) == -1) {
        //                 records.dateArr.push(record.record_date)
        //                 records.recordArr.push(record.watts)
        //             } else {
        //                 var index = records.dateArr.indexOf(record.record_date)
        //                 records.recordArr[index] += record.watts
        //             }
        //         }
        //     }
        //     this.createChart(records)
        // },
        addressClick(addressName) {
            for (var country of this.countryArr) {
                for (var address of country.addressList) {
                    if (address.name == addressName) {
                        this.address = address
                    }
                }

            }
            // console.log(this.address)
            let url = '/home/global/hotel'
            router.push({ path: url, query: { address: this.address } })
        },
        //悬浮提示数据
        itCounteyTooltip(params) {
            // var itCounteyTooltip = 'Country : ' + params.name + '<br/>'
            // for(var item of this.countryList){
            //     if(item.name == params.name){
            //         itCounteyTooltip += 'Device : ' + item.device + ' Type : ' + item.devicetype + '<br/>'
            //     }
            // }
            // return itCounteyTooltip
            // console.log(this.countryArr)
            var itCounteyTooltip = 'Hotel : ' + params.name + '<br/>'
            for (var country of this.countryArr) {
                for (var address of country.addressList) {
                    if (address.name == params.name) {
                        for (var floor of address.floorList) {
                            for (var room of floor.roomList) {
                                for (var type of room.typeList) {
                                    itCounteyTooltip += type.name + ' : ' + address.deviceTypeNumber[type.name] + '<br/>'
                                }
                            }
                        }





                    }
                }
            }
            return itCounteyTooltip
        },
        // makeMapData() {
        //     var mapData = [];
        //     for (var i = 0; i < this.rawData.length; i++) {
        //         var geoCoord = this.geoCoordMap[this.rawData[i][0]];
        //         if (geoCoord) { 
        //             mapData.push({
        //                 name: this.rawData[i][0],
        //                 value: geoCoord.concat(this.rawData[i].slice(1))
        //             });
        //         }
        //     }
        //     console.log(mapData)
        //     return mapData;
        // },
        initData() {
            var mapData = []
            for (var address of this.allAddress) {
                var mapObj = {}
                mapObj.name = address.address
                mapObj.value = [address.lng, address.lat]
                mapData.push(mapObj)
            }
            // console.log(mapData)
            return mapData
        },
        //创建地图
        createmap(e) {
            var myChart = echarts.init(this.$refs.worldmap);
            var option = {
                backgroundColor: new echarts.graphic.RadialGradient(0.5, 0.5, 0.4, [{
                    offset: 0,
                    color: '#4b5769'
                }, {
                    offset: 1,
                    color: '#404a59'
                }]),
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        var str = e.itCounteyTooltip(params)
                        return str
                    }
                },
                toolbox: {
                    show: true,
                    left: 'right',
                    iconStyle: {
                        normal: {
                            borderColor: '#ddd'
                        }
                    },
                    feature: {
                    },
                    z: 202
                },
                // brush: {
                //     geoIndex: 0,
                //     brushLink: 'all',
                //     inBrush: {
                //         opacity: 1,
                //         symbolSize: 14
                //     },
                //     outOfBrush: {
                //         color: '#000',
                //         opacity: 0.2
                //     },
                //     z: 10
                // },
                geo: {
                    map: 'world',
                    silent: true,
                    label: {
                        emphasis: {
                            show: false,
                            areaColor: '#eee'
                        }
                    },
                    itemStyle: {
                        normal: {
                            borderWidth: 0.2,
                            borderColor: '#404a59'
                        }
                    },
                    left: '6%',
                    top: 50,
                    bottom: '10%',
                    right: '6%',
                    roam: true
                    // itemStyle: {
                    //     normal: {
                    //         areaColor: '#323c48',
                    //         borderColor: '#111'
                    //     },
                    //     emphasis: {
                    //         areaColor: '#2a333d'
                    //     }
                    // }
                },



                series: [
                    {
                        name: '',
                        type: 'scatter',
                        coordinateSystem: 'geo',
                        symbolSize: 8,
                        data: this.initData(),
                        activeOpacity: 1,
                        label: {
                            normal: {
                                formatter: '{b}',
                                position: 'right',
                                show: false
                            },
                            // emphasis: {
                            //     show: true
                            // }
                        },
                        symbolSize: 10,
                        // symbolSize: function (data) {
                        //     return Math.max(5, data[2] / 5);
                        // },
                        itemStyle: {
                            normal: {
                                borderColor: '#fff',
                                color: '#577ceb',
                            }
                        }
                    }
                ]
            };

            myChart.setOption(option);
            myChart.on('click', function(params) {
                var dataIndex = params.dataIndex;
                // console.log(params);
                e.addressClick(params.data.name)
            });
        },
    },
    mounted() {
        console.log('global')
        this.initMapSize()
        this.createmap(this)
    },
    components: {

    },
    computed: {
        devices() {
            return this.$store.state.devices
        },
        allAddress() {
            return this.$store.state.address
        },
        allFloor() {
            return this.$store.state.floor
        },
        allRoom() {
            return this.$store.state.room
        },
        //计算该国家的设备类型，各种设备类型的数量，生成国家数组让地图调用
        countryArr() {
            return this.$store.state.countryArr
        },
       

    },
    mixins: [http]
}
</script>