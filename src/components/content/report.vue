<template>
    <el-row class="panel m-w-1100">
        <el-cascader class="m-t-10 m-l-15" :options="allAddress" change-on-select @change="addressChange"></el-cascader>
        <div>
            <div ref="lineChart" class="line-chart fl"></div>
            <div ref="pieChart" class="pie-chart fl"></div>
        </div>
    </el-row>
</template>

<script>

import echarts from 'echarts'
export default {
    data() {
        return {
            // tableData: [],
            // dataCount: null,
            selectRecord: [],
            allRecord: [],
            currentPage: null,
            keywords: '',
            thisdevice: {},
            notHotel: true,
            multipleSelection: [],
            limit: 15,
            showDeviceUpdate: false,
        }
    },
    methods: {
        addressChange(value) {
            var selectRecord = []
            var len = value.length
            switch (len) {
                case 1:
                    for (var record of this.record) {
                        if (record.address == value[0]) {
                            selectRecord.push(record)
                        }
                    }
                    break
                case 2:
                    for (var record of this.record) {
                        if (record.address == value[0] && record.floor == value[1]) {
                            selectRecord.push(record)
                        }
                    }
                    break
                case 3:
                    for (var record of this.record) {
                        if (record.address == value[0] && record.floor == value[1] && record.room == value[2]) {
                            selectRecord.push(record)
                        }
                    }
                    break
            }
            this.selectRecord = selectRecord

        },
        initMapSize(chart) {
            var width = (document.body.clientWidth - 180) * 0.5
            var height = (document.body.clientHeight - 60) * 0.8
            chart.style.width = width + 'px';
            chart.style.height = height + 'px';
        },
        initLineChart() {
            var chart = this.$refs.lineChart
            this.initMapSize(chart)
            var chart = echarts.init(chart);
            // var base = +new Date(2017, 1, 1);
            // var oneDay = 24 * 3600 * 1000;
            // var date = [];

            // var data = [Math.random() * 300];

            // for (var i = 1; i < 2000; i++) {
            //     var now = new Date(base += oneDay);
            //     date.push([now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'));
            //     data.push(Math.round((Math.random() - 0.5) * 20 + data[i - 1]));
            // }
            var date = this.allRecord.dateArr
            var data = this.allRecord.recordArr

            var option = {
                tooltip: {
                    trigger: 'axis',
                    position: function(pt) {
                        return [pt[0], '10%'];
                    },
                    formatter: "{b} : {c}w"
                },
                title: {
                    left: 'center',
                    text: 'Line Chart',
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
                    end: 100
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
            chart.setOption(option);
        },
        initPieChart() {
            var chart = this.$refs.pieChart
            this.initMapSize(chart)
            var chart = echarts.init(chart);

            var option = {
                backgroundColor: '#f1f2f7',

                title: {
                    text: 'Pie Chart',
                    left: 'center',
                    top: 20,
                    textStyle: {
                        color: '#000'
                    }
                },

                tooltip: {
                    trigger: 'item',
                    formatter: "{b} : {c}w ({d}%)"
                },

                visualMap: {
                    show: false,
                    min: this.allRecord.wattSort[0] * 0.8,
                    max: this.allRecord.wattSort[this.allRecord.wattSort.length - 1] * 1.5,
                    inRange: {
                        colorLightness: [0, 1]
                    }
                },
                series: [
                    {
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '50%'],
                        data: this.allRecord.typeArr.sort(function(a, b) { return a.value - b.value; }),
                        // data: [
                        //     { value: 335, name: 'Light' },
                        //     { value: 310, name: 'LED' },
                        //     { value: 274, name: 'Music' },
                        //     { value: 235, name: 'Other' },
                        //     { value: 400, name: 'AC' }
                        // ].sort(function(a, b) { return a.value - b.value; }),
                        roseType: 'radius',
                        label: {
                            normal: {
                                textStyle: {
                                    color: 'rgb(0, 0, 0)'
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                lineStyle: {
                                    color: 'rgba(255, 255, 255, 0.3)'
                                },
                                smooth: 0.2,
                                length: 10,
                                length2: 20
                            }
                        },
                        itemStyle: {
                            normal: {
                                color: '#c23531',
                                // shadowBlur: 200,
                                // shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },

                        animationType: 'scale',
                        animationEasing: 'elasticOut',
                        animationDelay: function(idx) {
                            return Math.random() * 200;
                        }
                    }
                ]
            };
            chart.setOption(option);
        },

    },
    created() {
        console.log('Report')
        _g.closeGlobalLoading()

    },
    mounted() {
        this.selectRecord = this.record
        // this.initLineChart()
        // this.initPieChart()
        console.log(this.allRecord)
    },
    components: {

    },
    watch: {
        selectRecord: {
            handler: function(val, oldVal) {
                var allRecord = {}
                allRecord.dateArr = []
                allRecord.recordArr = []
                allRecord.typeArr = []
                var typeArr = {}
                for (var record of this.selectRecord) {
                    var date = record.record_date.substr(0, 15) + '0'
                    if (allRecord.dateArr.indexOf(date) == -1) {
                        allRecord.dateArr.push(date)
                        allRecord.recordArr.push(parseInt(record.watts))
                    } else {
                        var index = allRecord.dateArr.indexOf(date)
                        allRecord.recordArr[index] += parseInt(record.watts)
                    }

                    typeArr[record.devicetype] ? typeArr[record.devicetype] += parseInt(record.watts) : typeArr[record.devicetype] = parseInt(record.watts)
                    // if (typeArr.indexOf(record.devicetype) == -1){
                    //     typeArr[record.devicetype] = parseInt(record.watts)
                    // }else{
                    //     typeArr[record.devicetype] += parseInt(record.watts)
                    // }
                }
                var wattSort = []
                for (var key in typeArr) {

                    var typeObj = {}
                    typeObj.name = key
                    typeObj.value = typeArr[key]
                    wattSort.push(typeArr[key])
                    allRecord.typeArr.push(typeObj)
                }
                function sortNumber(a, b) {
                    return a - b
                }
                wattSort.sort(sortNumber)
                allRecord.wattSort = wattSort
                this.allRecord = allRecord
                this.initLineChart()
                this.initPieChart()
            },
            deep: true
        }
    },
    computed: {
        record() {
            // this.selectRecord = this.$store.state.record
            return this.$store.state.record
        },
        // allRecord() {
        //     var records = {}
        //     records.dateArr = []
        //     records.recordArr = []
        //     records.typeArr = []
        //     var typeArr = {}
        //     for (var record of this.selectRecord) {
        //         var date = record.record_date.substr(0, 15) + '0'
        //         if (records.dateArr.indexOf(date) == -1) {
        //             records.dateArr.push(date)
        //             records.recordArr.push(parseInt(record.watts))
        //         } else {
        //             var index = records.dateArr.indexOf(date)
        //             records.recordArr[index] += parseInt(record.watts)
        //         }

        //         typeArr[record.devicetype] ? typeArr[record.devicetype] += parseInt(record.watts) : typeArr[record.devicetype] = parseInt(record.watts)
        //         // if (typeArr.indexOf(record.devicetype) == -1){
        //         //     typeArr[record.devicetype] = parseInt(record.watts)
        //         // }else{
        //         //     typeArr[record.devicetype] += parseInt(record.watts)
        //         // }
        //     }
        //     var wattSort = []
        //     for (var key in typeArr) {

        //         var typeObj = {}
        //         typeObj.name = key
        //         typeObj.value = typeArr[key]
        //         wattSort.push(typeArr[key])
        //         records.typeArr.push(typeObj)
        //     }
        //     function sortNumber(a, b) {
        //         return a - b
        //     }
        //     wattSort.sort(sortNumber)
        //     records.wattSort = wattSort
        //     return records
        // },
        allAddress() {
            var allAddress = []
            for (var address of this.$store.state.address) {
                var addressObj = {
                    value: address.address,
                    label: address.address,
                    children: [],
                }
                for (var floor of this.$store.state.floor) {
                    if (floor.address == address.address) {
                        var floorObj = {
                            value: floor.floor,
                            label: floor.floor,
                            children: [],
                        }
                        for (var room of this.$store.state.room) {
                            if (room.floor == floor.floor && room.address == address.address) {
                                var roomObj = {
                                    value: room.room,
                                    label: room.room,
                                }
                                floorObj.children.push(roomObj)
                            }
                        }
                        addressObj.children.push(floorObj)
                    }
                }
                allAddress.push(addressObj)
            }
            console.log(allAddress)
            return allAddress
        }
    }
}
</script>