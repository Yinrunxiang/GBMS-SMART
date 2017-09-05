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
            // record : this.$store.state.record
            // devices: this.$store.state.devices,
            geoCoordMap: {
                "Amsterdam": [4.895168, 52.370216],
                "Athens": [-83.357567, 33.951935],
                "Auckland": [174.763332, -36.84846],
                "Bangkok": [100.501765, 13.756331],
                "Barcelona": [2.173403, 41.385064],
                "Beijing": [116.407395, 39.904211],
                "Berlin": [13.404954, 52.520007],
                "Bogotá": [-74.072092, 4.710989],
                "Bratislava": [17.107748, 48.148596],
                "Brussels": [4.35171, 50.85034],
                "Budapest": [19.040235, 47.497912],
                "Buenos Aires": [-58.381559, -34.603684],
                "Bucharest": [26.102538, 44.426767],
                "Caracas": [-66.903606, 10.480594],
                "Chicago": [-87.629798, 41.878114],
                "Delhi": [77.209021, 28.613939],
                "Doha": [51.53104, 25.285447],
                "Dubai": [55.270783, 25.204849],
                "Dublin": [-6.26031, 53.349805],
                "Frankfurt": [8.682127, 50.110922],
                "Geneva": [6.143158, 46.204391],
                "Helsinki": [24.938379, 60.169856],
                "Hong Kong": [114.109497, 22.396428],
                "Istanbul": [28.978359, 41.008238],
                "Jakarta": [106.845599, -6.208763],
                "Johannesburg": [28.047305, -26.204103],
                "Cairo": [31.235712, 30.04442],
                "Kiev": [30.5234, 50.4501],
                "Copenhagen": [12.568337, 55.676097],
                "Kuala Lumpur": [101.686855, 3.139003],
                "Lima": [-77.042793, -12.046374],
                "Lisbon": [-9.139337, 38.722252],
                "Ljubljana": [14.505751, 46.056947],
                "London": [-0.127758, 51.507351],
                "Los Angeles": [-118.243685, 34.052234],
                "Luxembourg": [6.129583, 49.815273],
                "Lyon": [4.835659, 45.764043],
                "Madrid": [-3.70379, 40.416775],
                "Milan": [9.185924, 45.465422],
                "Manama": [50.58605, 26.228516],
                "Manila": [120.984219, 14.599512],
                "Mexico City": [-99.133208, 19.432608],
                "Miami": [-80.19179, 25.76168],
                "Montreal": [-73.567256, 45.501689],
                "Moscow": [37.6173, 55.755826],
                "Mumbai": [72.877656, 19.075984],
                "Munich": [11.581981, 48.135125],
                "Nairobi": [36.821946, -1.292066],
                "New York": [-74.005941, 40.712784],
                "Nicosia": [33.382276, 35.185566],
                "Oslo": [10.752245, 59.913869],
                "Paris": [2.352222, 48.856614],
                "Prague": [14.4378, 50.075538],
                "Riga": [24.105186, 56.949649],
                "Rio de Janeiro": [-43.172896, -22.906847],
                "Rome": [12.496366, 41.902783],
                "Santiago de Chile": [-70.669265, -33.44889],
                "São Paulo": [-46.633309, -23.55052],
                "Seoul": [126.977969, 37.566535],
                "Shanghai": [121.473701, 31.230416],
                "Singapore": [103.819836, 1.352083],
                "Sofia": [23.321868, 42.697708],
                "Stockholm": [18.068581, 59.329323],
                "Sydney": [151.209296, -33.86882],
                "Taipei": [121.565418, 25.032969],
                "Tallinn": [24.753575, 59.436961],
                "Tel Aviv": [34.781768, 32.0853],
                "Tokyo": [139.691706, 35.689487],
                "Toronto": [-79.383184, 43.653226],
                "Vilnius": [25.279651, 54.687156],
                "Warsaw": [21.012229, 52.229676],
                "Vienna": [16.373819, 48.208174],
                "Zurich": [8.541694, 47.376887]
            },
            schema: [
                "Cities",
                "Gross purchasing power",
                "Net purchasing power",
                "Prices (excl. rent)",
                "Prices (incl. rent)",
                "Gross wages",
                "Net wages",
                "Working time [hours per year]",
                "Vacation [paid working days per year]",
                "Time required for 1 Big Mac [minutes]",
                "Time required for 1 kg of bread [minutes]",
                "Time required for 1 kg of rice [minutes]",
                "Time required for 1 iPhone 4S, 16 GB [hours]",
                "City break",
                "Inflation 2006",
                "Inflation 2007",
                "Inflation 2008",
                "Inflation 2009",
                "Inflation 2010",
                "Inflation 2011",
                "Prices (incl. rent)",
                "Food basket",
                "Services",
                "Normal local rent medium [USD per month]",
                "Household appliances",
                "Bus or tram or underground",
                "Train",
                "Taxi  [USD per 5 km trip]",
                "Medium-sized cars price",
                "Medium-sized cars tax",
                "Medium-sized cars gas",
                "Restaurant [USD per dinner]",
                "Hotel *** [USD per night]",
                "Hotel ***** [USD per night]",
                "Women's medium clothing",
                "Men's medium clothing",
                "Furnished medium 4-room apartment [USD per month]",
                "Unfurnished medium 3-room apartment [USD per month]",
                "Net hourly wages [USD per hour]",
                "Gross hourly wages [USD per hour]",
                "Taxes and social security contributions",
                "Primary school teacher [USD per year]",
                "Bus driver [USD per year]",
                "Automobile mechanic [USD per year]",
                "Building labourer [USD per year]",
                "Skilled industrial worker [USD per year]",
                "Cook [USD per year]",
                "Departement head [USD per year]",
                "Product manager [USD per year]",
                "Engineer [USD per year]",
                "Bank credit clerk [USD per year]",
                "Secretary [USD per year]",
                "Saleswoman [USD per year]",
                "Female industrial worker [USD per year]",
                "Female call center worker [USD per year]",
                "Financial analyst [USD per year]",
                "Financial analyst [USD pro Jahr]"
            ],
            rawData: [
                ["Amsterdam", 101.6, 90.1, 77.1, 69.1, 78.3, 69.4, 1755, 24, 15, 7, 9, 44, 720, 1.651, 1.59, 2.205, 0.974, 0.93, 2.477, 67.4, 364, 690, 1113, 4960, 3.19, 30.05, 16.34, 24000, 689, 1.8, 50, 200, 390, 690, 1040, 2331, 1580, 17.5, 25.5, 30, 48400, 39200, 26300, 30200, 55400, 39800, 104400, 58700, 64600, 49200, 40300, 31100, 40300, 27700, 66700, 66700],
                ["Athens", 62.6, 60.5, 66.2, 58.2, 41.4, 40, 1822, 22, 29, 13, 25, 86, 590, 3.314, 2.991, 4.236, 1.349, 4.701, 3.1, 56.8, 390, 580, 880, 4620, 1.81, 13.81, 5.5, 24900, 389, 2.02, 54, 100, 210, 630, 1110, 1489, 647, 10.1, 13.5, 24, 26200, 23300, 18500, 17100, 24500, 24200, 57200, 44000, 34100, 30700, 21000, 17700, 15400, 16300, 34400, 34400],
                ["Auckland", 77.9, 82.9, 76.7, 67.8, 59.8, 63.5, 1852, 20, 15, 16, 7, 51, 580, 3.362, 2.377, 3.959, 2.116, 2.303, 4.027, 66.1, 496, 630, 1023, 4450, 2.57, 40.86, 13.62, 23900, 226, 1.33, 45, 190, 280, 560, 670, 1644, 1333, 16, 19.5, 17, 35700, 31500, 36500, 28500, 41800, 31100, 61300, 55000, 56300, 37300, 33400, 26900, 27200, 27500, 64900, 64900],
                ["Bangkok", 26.4, 31.4, 55.4, 48.2, 14.6, 17.4, 2312, 7, 36, 25, 20, 165, 550, 4.637, 2.242, 5.468, -0.845, 3.272, 3.807, 47, 422, 440, 414, 4370, 0.75, 3.47, 2.47, 29600, 103, 1, 56, 90, 320, 400, 600, 1463, 932, 4.4, 4.8, 5, 8300, 8400, 11100, 3000, 10900, 10900, 32200, 22400, 24600, 14500, 7800, 6000, 5800, 6500, 19400, 19400],
                ["Barcelona", 79.7, 78.6, 74.7, 65.6, 59.6, 58.7, 1760, 29, 18, 11, 6, 52, 740, 3.563, 2.844, 4.13, -0.238, 2.043, 3.052, 64, 393, 750, 984, 5000, 2.59, 41.96, 10.36, 26900, 177, 1.77, 51, 170, 330, 580, 1110, 1269, 1087, 14.8, 19.4, 23, 41300, 34100, 29100, 29800, 31500, 32100, 40800, 67000, 43100, 38900, 28900, 25500, 25000, 28000, 58300, 58300],
                ["Beijing", 28.2, 29.9, 60.3, 51.8, 17, 18, 1979, 9, 34, 27, 16, 184, 730, 1.467, 4.767, 5.852, -0.683, 3.325, 5.417, 50.6, 463, 420, 310, 4370, 0.26, 14.25, 3.64, 23800, 67, 1.24, 41, 160, 400, 660, 700, 1554, 660, 4.5, 5.6, 17, 11400, 7000, 8500, 7600, 6200, 11900, 13300, 11700, 10700, 18300, 17100, 8900, 5400, 7600, 19800, 19800],
                ["Berlin", 109.7, 97.1, 72.2, 64.1, 79.2, 70.1, 1742, 28, 16, 11, 9, 56, 720, 1.784, 2.276, 2.754, 0.234, 1.15, 2.483, 62.5, 389, 530, 841, 4670, 2.98, 80.3, 10.79, 35600, 246, 2.1, 34, 120, 230, 570, 710, 2395, 1178, 17.7, 25.8, 30, 56900, 38600, 35500, 28500, 47400, 57600, 84200, 74500, 72100, 51700, 38100, 28200, 32000, 28100, 81700, 81700],
                ["Tokyo", 84.7, 82.9, 109, 100.1, 92.4, 90.4, 2012, 16, 8, 14, 15, 35, 1190, 0.3, 0, 1.396, -1.347, -0.72, -0.283, 97.7, 927, 940, 1631, 4820, 2.46, 44.72, 21.42, 26300, 495, 1.62, 72, 370, 730, 1220, 1880, 6177, 2486, 22.8, 30.1, 24, 78200, 56300, 54000, 47000, 77700, 70200, 89400, 102100, 77200, 79400, 48800, 35100, 48000, 44700, 144000, 144000],
                ["Toronto", 103.4, 92.4, 74.3, 67.2, 76.8, 68.6, 1847, 14, 10, 11, 9, 38, 680, 2.018, 2.123, 2.385, 0.3, 1.776, 2.891, 65.6, 453, 750, 1087, 4520, 3.08, 35.62, 13.31, 15000, 75, 1.25, 71, 150, 340, 310, 840, 2564, 2020, 17.3, 25, 27, 82900, 36700, 33300, 46200, 44300, 53000, 66600, 47300, 84800, 32900, 26300, 28000, 29700, 37400, 74600, 74600],
                ["Vilnius", 42.6, 41.6, 50.9, 43.6, 21.7, 21.2, 1789, 24, 32, 19, 33, 168, 410, 3.788, 5.772, 11.138, 4.164, 1.19, 4.124, 42.5, 284, 360, 323, 4770, 0.94, 13.73, 4.63, 23700, null, 1.72, 22, 90, 220, 480, 510, 984, 492, 5.3, 7.1, 23, 10500, 12200, 13900, 9800, 17700, 21900, 23600, 18500, 16600, 20200, 10400, 6500, 8500, 8000, 38500, 38500],
                ["Warsaw", 44.3, 40.8, 53.7, 48, 23.8, 21.9, 1792, 23, 35, 13, 24, 141, 650, 1.033, 2.493, 4.215, 3.45, 2.514, 4.268, 46.8, 291, 420, 712, 4410, 0.79, 14.62, 3.15, 25000, 55, 1.76, 31, 110, 280, 580, 950, 1618, 1204, 5.5, 7.7, 28, 11900, 10000, 12600, 9700, 13200, 20500, 20900, 27600, 17900, 11900, 11700, 11000, 8400, 7600, 24900, 24900],
                ["Vienna", 100.6, 88.8, 81.3, 72.1, 80.2, 70.8, 1786, 25, 13, 8, 8, 46, 830, 1.686, 2.203, 3.223, 0.401, 1.69, 3.6, 70.3, 503, 680, 945, 5560, 2.59, 42.03, 17.27, 29800, 453, 1.8, 47, 140, 360, 980, 1040, 2486, 1424, 17.8, 26.1, 29, 44700, 42900, 34100, 29500, 56200, 49000, 96100, 82900, 69800, 49100, 49900, 30000, 25400, 32600, 72500, 72500],
                ["Zurich", 119.1, 120.3, 110, 102.5, 131.1, 132.4, 1887, 23, 12, 5, 5, 22, 1250, 1.047, 0.732, 2.43, -0.476, 0.685, 0.228, 100, 704, 1130, 2551, 5130, 4.66, 68.47, 28.93, 45200, 426, 2.01, 90, 280, 630, 1100, 1190, 4481, 2499, 33.4, 42.7, 21, 104600, 90700, 68900, 61800, 79800, 69900, 137200, 130000, 115700, 96900, 71100, 61400, 53200, 58900, 140400, 140400]
            ]
        }
    },
    methods: {
        initMapSize(){
            var width = document.body.clientWidth -180
            var height = document.body.clientHeight -60
            var map = this.$refs.worldmap
            map.style.width = width + 'px';
            map.style.height = height + 'px';
            console.log(map.style.width+map.style.height)
            
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
        countryChange(country) {
            let url = '/home/report'
            router.push(url)
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
            var itCounteyTooltip = 'City : ' + params.name + '<br/>'
            for (var item of this.countryArr) {
                if (item.name == params.name) {
                    for (var address of item.addressList) {
                        for (var type of address.typeList) {
                            itCounteyTooltip += type.name + ' : ' + item.deviceTypeNumber[type.name] + '<br/>'
                        }
                    }
                }
            }
            return itCounteyTooltip
        },
        makeMapData() {
            var mapData = [];
            for (var i = 0; i < this.rawData.length; i++) {
                var geoCoord = this.geoCoordMap[this.rawData[i][0]];
                if (geoCoord) {
                    mapData.push({
                        name: this.rawData[i][0],
                        value: geoCoord.concat(this.rawData[i].slice(1))
                    });
                }
            }
            return mapData;
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
                        data: this.makeMapData(this.rawData),
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
                console.log(params);
                e.countryChange(params.data.name)
            });
        },
    },
    mounted() {
        console.log('global')
        this.initMapSize()
        // const data = {
        // 	params: {
        // 		action: "getrecord"
        // 	}
        // }
        // this.apiGet("device/index.php", data).then(res => {
        // 	this.$store.dispatch('setDevices', res)
        // });
        this.createmap(this)
        // this.createChart(this.allRecord)
    },
    components: {

    },
    computed: {
        // devices() {
        //     var devices = []
        //     for (var device of this.$store.state.devices) {
        //         if (device.status == 'enabled') {
        //             devices.push(device)
        //         }
        //     }
        //     return devices
        // },
        //计算该国家的设备类型，各种设备类型的数量，生成国家数组让地图调用
        countryArr() {
            return this.$store.state.countryArr
        },
        record() {
            return this.$store.state.record
        },
        allRecord() {
            var records = {}
            records.dateArr = []
            records.recordArr = []
            for (var record of this.$store.state.record) {
                if (records.dateArr.indexOf(record.record_date) == -1) {
                    records.dateArr.push(record.record_date)
                    records.recordArr.push(record.watts)
                } else {
                    var index = records.dateArr.indexOf(record.record_date)
                    records.recordArr[index] += record.watts
                }
            }
            return records
        }

    },
    mixins: [http]
}
</script>