<template>
    <div>
        <div ref="worldmap" id="world-map"></div>
         <div ref="chart" id="chart"></div>
    </div>
</template>

<<style>
#world-map,#chart{
    width:1100px; 
    height: 400px;
    margin: 0 auto;
}
</style>


<script>
import http from '../../assets/js/http'
import echarts from 'echarts'
// import 'echarts/lib/chart/map';
import 'echarts/map/js/world.js';


export default {
    data() {
        return {
            // record : this.$store.state.record
            // devices: this.$store.state.devices,
        }
    },
    methods: {
        
        createChart(record) {
            var myChart = echarts.init(this.$refs.chart);
            var base = +new Date(1968, 9, 3);
            var oneDay = 24 * 3600 * 1000;
            var date = record.dateArr
            var data = record.recordArr
            // var date = [];

            // // var data = [Math.random() * 300];

            // // for (var i = 1; i < 20000; i++) {
            // //     var now = new Date(base += oneDay);
            // //     date.push([now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'));
            // //     data.push(Math.round((Math.random() - 0.5) * 20 + data[i - 1]));
            // // }

            var option = {
                tooltip: {
                    trigger: 'axis',
                    position: function (pt) {
                        return [pt[0], '10%'];
                    }
                },
                title: {
                    left: 'center',
                    text: 'Power Mete',
                },
                toolbox: {
                    feature: {
                        dataZoom: {
                            yAxisIndex: 'none'
                        },
                        restore: {},
                        saveAsImage: {}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: date
                },
                yAxis: {
                    type: 'value',
                    boundaryGap: [0, '100%']
                },
                dataZoom: [{
                    type: 'inside',
                    start: 0,
                    end: 10
                }, {
                    start: 0,
                    end: 10,
                    handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
                    handleSize: '80%',
                    handleStyle: {
                        color: '#fff',
                        shadowBlur: 3,
                        shadowColor: 'rgba(0, 0, 0, 0.6)',
                        shadowOffsetX: 2,
                        shadowOffsetY: 2
                    }
                }],
                series: [
                    {
                        name: '模拟数据',
                        type: 'line',
                        smooth: true,
                        symbol: 'none',
                        sampling: 'average',
                        itemStyle: {
                            normal: {
                                color: 'rgb(255, 70, 131)'
                            }
                        },
                        areaStyle: {
                            normal: {
                                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                    offset: 0,
                                    color: 'rgb(255, 158, 68)'
                                }, {
                                    offset: 1,
                                    color: 'rgb(255, 70, 131)'
                                }])
                            }
                        },
                        data: data
                    }
                ]
            };
            myChart.setOption(option);
        },
        countryChange(country){
            var records = {}
            records.dateArr = []
            records.recordArr = []
            for(var record of this.$store.state.record){
                    if(record.country == country){
                        if(records.dateArr.indexOf(record.record_date) == -1){
                        records.dateArr.push(record.record_date)
                        records.recordArr.push(record.watts)
                    }else{
                        var index  = records.dateArr.indexOf(record.record_date)
                        records.recordArr[index] += record.watts
                    }
                }
            }
            this.createChart(records)
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
            var itCounteyTooltip = 'Country : ' + params.name + '<br/>'
            for (var item of this.mapIportCountryArr) {
                if (item.name == params.name) {
                    for (var type of item.deviceType) {
                        itCounteyTooltip += type + ' : ' + item.deviceTypeNumber[type] + '<br/>'
                    }
                }
            }
            return itCounteyTooltip
        },
        //创建地图
        createmap(e) {
            var myChart = echarts.init(this.$refs.worldmap);
            var option = {
                tooltip: {
                    show: true, //显示提示标签
                    //提示标签格式
                    formatter: function (params) {
                        var str = e.itCounteyTooltip(params)
                        return str
                        // var value = (params.value + '').split('.');
                        // value = value[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,') + '.' + value[1];
                        // return params.seriesName + '<br/>' + params.name + ' : ' + value;
                    },
                    backgroundColor: "#293c55",//提示标签背景颜色
                    textStyle: { color: "#ddd" } //提示标签字体颜色
                },
                // toolbox: {
                //     show: true,
                //     left: 'right',
                //     iconStyle: {
                //         normal: {
                //             borderColor: '#ddd'
                //         }
                //     },
                //     feature: {
                //     },
                //     z: 202
                // },
                series: [{
                    type: 'map',
                    mapType: 'world',
                    label: {
                        normal: {
                            show: false,//显示省份标签
                            textStyle: { color: "#c71585" }//省份标签字体颜色
                        },
                        emphasis: {//对应的鼠标悬浮效果
                            show: false,
                            textStyle: { color: "#800080" }
                        }
                    },
                    itemStyle: {
                        normal: {
                            borderWidth: .5,//区域边框宽度
                            borderColor: '#555',//区域边框颜色
                            areaColor: "#c0ccda",//区域颜色
                        },
                        emphasis: {
                            borderWidth: .5,
                            borderColor: '#555',
                            areaColor: "#324057",
                        }
                    },

                    data: this.mapIportCountryArr
                    // data: [
                    //     { name: this.country, selected: true }//福建为选中状态
                    // ]
                }],
            };

            myChart.setOption(option);
            myChart.on('click', function (params) {
                var dataIndex = params.dataIndex;
                console.log(params);
                e.countryChange(params.data.name)
            });
        },
    },
    mounted() {
        console.log('global')
        // const data = {
		// 	params: {
		// 		action: "getrecord"
		// 	}
		// }
		// this.apiGet("device/index.php", data).then(res => {
		// 	this.$store.dispatch('setDevices', res)
		// });
        this.createmap(this)
        this.createChart(this.allRecord)
    },
    components: {

    },
    computed: {
        devices() {
            var devices = []
            for(var device of this.$store.state.devices){
                if(device.status == 'enabled'){
                    devices.push(device)
                }
            }
            return devices
        },
        //计算该国家的设备类型，各种设备类型的数量，生成国家数组让地图调用
        mapIportCountryArr() {
            var mapIportCountryArr = []
            var countryList = []
            //this.devices原始设备数据
            for (var item of this.devices) {
                //筛选重复国家
                if (countryList.indexOf(item.country) == -1) {
                    countryList.push(item.country);
                    var mapIportCountryObject = {}
                    mapIportCountryObject.name = item.country
                    mapIportCountryObject.selected = true
                    mapIportCountryObject.deviceType = []
                    mapIportCountryObject.deviceTypeNumber = {}
                    mapIportCountryObject.deviceList = {}
                    mapIportCountryArr.push(mapIportCountryObject)
                }
                for (var country of mapIportCountryArr) {
                    //筛选重复类型
                    if (item.country == country.name) {
                        if (country.deviceType.indexOf(item.devicetype) == -1) {
                            country.deviceType.push(item.devicetype)
                        }
                        //计算各种设备类型的数量
                        country.deviceTypeNumber[item.devicetype] ? country.deviceTypeNumber[item.devicetype] += 1 : country.deviceTypeNumber[item.devicetype] = 1
                        //获取各种类型的设备名称
                        if (!country.deviceList[item.devicetype]) {
                            country.deviceList[item.devicetype] = [item.device]
                        } else {
                            country.deviceList[item.devicetype].push(item.device)
                        }
                    }
                }
            }
            // console.log(mapIportCountryArr)
            return mapIportCountryArr
        },
        record(){
            return this.$store.state.record
        },
        allRecord(){
            var records = {}
            records.dateArr = []
            records.recordArr = []
            for(var record of this.$store.state.record){
                if(records.dateArr.indexOf(record.record_date) == -1){
                    records.dateArr.push(record.record_date)
                    records.recordArr.push(record.watts)
                }else{
                    var index  = records.dateArr.indexOf(record.record_date)
                    records.recordArr[index] += record.watts
                }
            }
            return records
        }
        
    },
    mixins: [http]
}
</script>