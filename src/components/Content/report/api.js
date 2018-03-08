import http from "../../../assets/js/http"
let recordList = []
function getRecordList(beginDate, endDate,vm,count, start, end,  setIntervalRecord) {
    const data = {
        params: {
            action: "getrecord",
            start: start,
            end: end,
            beginDate: beginDate,
            endDate: endDate
        }
    };
    http.methods.apiGet("device/index.php", data).then(res => {
        var records = res;
        var newRecords = [];
        var addresss = vm.$store.state.address;
        var ac_breeds = vm.$store.state.ac_breed;
        var light_breeds = vm.$store.state.light_breed;
        var led_breeds = vm.$store.state.led_breed;
        for (var record of records) {
            if (record.on_off == "on") {
                switch (record.devicetype) {
                    case "ac":
                        for (var ac_breed of ac_breeds) {
                            if (record.breed == ac_breed.breed) {
                                record.watts =
                                    parseInt(ac_breed[record.mode]) +
                                    parseInt(ac_breed[record.grade]);
                            }
                        }
                        break;
                    case "light":
                        for (var light_breed of light_breeds) {
                            if (record.breed == light_breed.breed) {
                                record.watts = parseInt(light_breed.watts);
                            }
                        }
                        break;
                    case "led":
                        for (var led_breed of led_breeds) {
                            if (record.breed == led_breed.breed) {
                                record.watts = parseInt(led_breed.watts);
                            }
                        }
                        break;
                }
                if (record.watts) {
                    record.watts = parseFloat(record.watts / 1000 /6);
                    record.usd = 0;
                    for (var address of addresss) {
                        if (address.address == record.address && address.kw_usd) {
                            record.usd = record.watts * parseFloat(address.kw_usd);
                        }
                    }
                    newRecords.push(record);
                }
            }
        }

        //记录数据处理完成
        //以下是记录数据的使用
        recordList = recordList.concat(newRecords);
        if (end >= count) {
            
            clearInterval(setIntervalRecord);
            vm.$store.dispatch("setRecord", recordList);
            vm.$store.dispatch("setCurrentRecord", recordList);
            vm.$store.dispatch("setRecordLoading", false);
            return
        }
    });
}
function forGetRecord(beginDate,endDate,vm,count) {
    var i = 0,
        start = 0,
        end = 0

    var setIntervalRecord = setInterval(function () {
        start = i + 1;
        i += 3000;
        end = i>=count ? count:i;
        if(end <= count){
            getRecordList(beginDate, endDate,vm,count, start, end,  setIntervalRecord);
        }
    }, 1000);
}
const record = {
    
    getRecord(beginDate,endDate,vm) {
        vm.$store.dispatch("setRecordLoading", true);
        const data = {
            params: {
                action: "getRecordCount",
                beginDate: beginDate,
                endDate: endDate
            }
        };
        http.methods.apiGet("device/index.php", data).then(res => {
            var count = parseInt(res[0].count);
            forGetRecord(beginDate,endDate,vm,count);
        });
    },
}

export default record