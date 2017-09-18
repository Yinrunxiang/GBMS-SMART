<template>
    <div>
        <div ref="lineChart" class="line-chart fl" style="width:600px;height:500px"></div>
        <div ref="pieChart" class="pie-chart fl" style="width:500px;height:500px"></div>
    </div>
</template>

<script>

import echarts from 'echarts'
export default {
    data() {
        return {
            // tableData: [],
            // dataCount: null,
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
        initLineChart() {
            var myChart = echarts.init(this.$refs.lineChart);
            var base = +new Date(2017, 1, 1);
            var oneDay = 24 * 3600 * 1000;
            var date = [];

            var data = [Math.random() * 300];

            for (var i = 1; i < 2000; i++) {
                var now = new Date(base += oneDay);
                date.push([now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'));
                data.push(Math.round((Math.random() - 0.5) * 20 + data[i - 1]));
            }

            var option = {
                tooltip: {
                    trigger: 'axis',
                    position: function(pt) {
                        return [pt[0], '10%'];
                    }
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
        initPieChart() {
            var myChart = echarts.init(this.$refs.pieChart);
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
                    min: 80,
                    max: 600,
                    inRange: {
                        colorLightness: [0, 1]
                    }
                },
                series: [
                    {
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '50%'],
                        data: [
                            { value: 335, name: 'Light' },
                            { value: 310, name: 'LED' },
                            { value: 274, name: 'Music' },
                            { value: 235, name: 'Other' },
                            { value: 400, name: 'AC' }
                        ].sort(function(a, b) { return a.value - b.value; }),
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
            myChart.setOption(option);
        },

    },
    created() {
        console.log('Plan')
        

    },
    mounted(){
        this.initLineChart()
        this.initPieChart()
    },
    components: {

    },
    computed: {

    }
}
</script>