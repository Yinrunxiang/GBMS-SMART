<template>
    <el-col :span="24">
        <el-col :span="8">
            <div>
                <div class="">
                    <i class="fa fa-music big-music-icon"></i>
                </div>
            </div>
        </el-col>
        <el-col :span="16" class="m-b-20 p-l-20 p-r-20 ovf-hd" style="margin-top:80px">
            <el-row>
                <el-col :span="8" style="line-height:36px;">
                    <span>{{music_name}}</span>
                    <span>{{music_autor}}</span>
                </el-col>
                <el-col :span="6" :offset="2">
                    <el-col :span="3">
                        <span class="fr" style="line-height:36px">
                            <i class="fa fa-volume-up"></i>
                        </span>
                    </el-col>
                    <el-col :span="18" :offset="3">
                        <template>
                            <div>
                                <el-slider v-model="vol" :min='0' :max='79' :step="1" @change="vol_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="16">
                    <template>
                        <div>
                            <el-slider v-model="time_now" :min='0' :max='30' :step="1" @change="time_change">
                            </el-slider>
                        </div>
                    </template>
                </el-col>
                <el-col :span="5" :offset="1">
                    <span>{{time_now}}</span>
                    <span>{{time_over}}</span>
                </el-col>
            </el-row>
            <el-row class="m-t-20">
                <el-col :span="10">
                    <el-button @click="pre()" class="music-btn">
                        <i class="fa fa-step-backward"></i>
                    </el-button>
                    <el-button @click="play()" class="music-btn" v-show="on_off == false">
                        <i class="fa fa-play"></i>
                    </el-button>
                    <el-button @click="pause()" class="music-btn" v-show="on_off">
                        <i class="fa fa-pause"></i>
                    </el-button>
                    <el-button @click="next()" class="music-btn">
                        <i class="fa fa-step-forward"></i>
                    </el-button>
                </el-col>
                <el-col :span="10" :offset="2">
                    <el-button @click="random()" class="music-btn">
                        <i class="fa fa-random"></i>
                    </el-button>
                    <el-button @click="single()" class="music-btn">
                        <i class="fa fa-exchange"></i>
                    </el-button>
                    <el-button @click="allmusic()" class="music-btn">
                        <i class="fa fa-bar"></i>
                    </el-button>
                </el-col>
            </el-row>
            <el-row class="m-t-20">
                <el-col :span="24">
                    <ul class="songlist">
                        <li v-for="item in songlist">
                            <ul>
                                <li>album:{{albumlist[item.albumno]}}</li>
                                <li v-for = "song in item.songlist">{{song}}</li>
                            </ul>
                        </li>
                    </ul>
                </el-col>
            </el-row>
        </el-col>
    </el-col>
</template>

<style>
.music-btn {
    padding: 15px;
    width: 50px;
    height: 50px;
    text-align: center;
    font-size: 16px;
    border-radius: 50px;
}

.music-mode-btn {
    font-size: 25px;
    height: 50px;
    line-height: 50px;
}

.big-music-icon {
    display: inline-block;
    margin: 100px 80px 100px 80px;
    padding: 30px;
    width: 160px;
    height: 160px;
    font-size: 150px;
    border: 3px solid #c0ccda;
    border-radius: 160px;
    color: #c0ccda;
}

.songlist {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 350px;
    background: #fff;
    border: 1px solid #c4c4c4;
    border-radius: 5px;
    overflow-y: auto;
}

.songlist li ul li {
    padding-left: 10px;
    height: 30px;
    line-height: 30px;
    border-bottom: 1px solid #c4c4c4;
}
</style>

<script>
import http from '../../../assets/js/http'
export default {
    data() {
        return {
            vol: 0,
            on_off: false,
            music_name: "any",
            music_autor: "any",
            time_now: 0,
            time_over: 0,
            albumno: 0,
            albumlist: [],
            songno: 0,
            songlist: [],
            device: this.$store.state.device,
        }
    },
    methods: {
        time_change(val) {

            // this.autotmp = val;
            // this.loading = true
            // const data = {
            //     params: {
            //         operatorCodefst: "02",
            //         operatorCodesec: "18",
            //         targetDeviceID: this.device.deviceid,
            //         additionalContentData: ("05,01,03"+ _g.toHex(val)).split(","),
            //         macAddress: this.device.mac ? this.device.mac.split(".") : "",
            //         dest_address: this.device.ip ? this.device.ip.split(".") : "",
            //         dest_port: this.device.port ? this.device.port.split(".") : "",
            //     }
            // }
            // this.apiGet('udp/sendUdp.php', data).then((res) => {
            //     // console.log('res = ', _g.j2s(res))
            //     // _g.closeGlobalLoading()
            // })
        },
        vol_change(val) {

            this.cooltmp = val;
            this.loading = true
            val = 79 - val
            // if(val < 10)
            // val = '0'+val
            const data = {
                params: {
                    operatorCodefst: "02",
                    operatorCodesec: "18",
                    targetDeviceID: this.device.deviceid,
                    // additionalContentData: ("05,01,03," + val).split(","),
                    additionalContentData: ("05,01,03," + _g.toHex(val)).split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        pre() {
            const data = {
                params: {
                    operatorCodefst: "02",
                    operatorCodesec: "18",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("04,01,00,00").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        next() {
            const data = {
                params: {
                    operatorCodefst: "02",
                    operatorCodesec: "18",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("04,02,00,00").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        play() {
            this.on_off = true
            const data = {
                params: {
                    operatorCodefst: "02",
                    operatorCodesec: "18",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("04,03,00,00").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        pause() {
            this.on_off = false
            const data = {
                params: {
                    operatorCodefst: "02",
                    operatorCodesec: "18",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("06,04").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        random() {
            const data = {
                params: {
                    operatorCodefst: "02",
                    operatorCodesec: "18",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("02,01").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        single() {
            const data = {
                params: {
                    operatorCodefst: "02",
                    operatorCodesec: "18",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("02,02").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        allmusic() {
            const data = {
                params: {
                    operatorCodefst: "02",
                    operatorCodesec: "18",
                    targetDeviceID: this.device.deviceid,
                    additionalContentData: ("02,04").split(","),
                    macAddress: this.device.mac ? this.device.mac.split(".") : "",
                    dest_address: this.device.ip ? this.device.ip : "",
                    dest_port: this.device.port ? this.device.port : "",
                }
            }
            this.apiGet('udp/sendUdp.php', data).then((res) => {
                // console.log('res = ', _g.j2s(res))
                // _g.closeGlobalLoading()
            })
        },
        strToarr(str) {
            var len = str.length / 2
            var arr = []
            for (var i = 0; i < len; i++) {
                arr.push(str.substr(i * 2, 2))
            }
            return arr
        },

        readStatus(e) {
            var source = '01'
            var albumnum = 0
            var albumNote = 0
            let additionalContentData = ("01").split(",")
            _g.sendUdp(e, "02", "E0", e.device.deviceid, additionalContentData, e.device.mac, e.device.ip, e.device.port)
            window.socketio.removeAllListeners("new_msg");
            window.socketio.on("new_msg", function (msg) {
                var msglen = msg.length
                var stop = msglen - 4
                var subnetid = msg.substr(34, 2);
                var deviceid = msg.substr(36, 2);

                // var channel = msg.substr(52, 2);
                if (
                    subnetid.toLowerCase() == e.device.subnetid.toLowerCase() &&
                    deviceid.toLowerCase() == e.device.deviceid.toLowerCase()
                ) {
                    //操作码
                    var operationcode = _g.getoperationcode(msg);

                    if (operationcode.toLowerCase() == "02e1") {
                        var source = _g.getadditional(msg, 0)
                        var albumpack = msg.substring(52, stop)
                        var additionalContentData = e.strToarr(albumpack)
                        additionalContentData.unshift(source)
                        // console.log(additionalContentData)
                        _g.sendUdp(e, "02", "E2", e.device.deviceid, additionalContentData, e.device.mac, e.device.ip, e.device.port)
                        console.log("02E1")
                    }
                    if (operationcode.toLowerCase() == "02e3") {
                        var source = _g.getadditional(msg, 2)
                        var firstAlbumNo = _g.getadditional(msg, 5)
                        // e.albumno = '06'
                        albumnum = _g.getadditional(msg, 4)
                        var albumlist = msg.substring(64)
                        e.albumlist = []
                        var albumlist = e.strToarr(albumlist)
                        for (var item of albumlist) {
                            item = parseInt('0x' + item)
                            e.albumlist.push(String.fromCharCode(item))
                        }
                        e.albumlist = e.albumlist.join("")
                        e.albumlist = e.albumlist.match(/[a-zA-Z0-9.]+/g)
                        e.albumlist = e.albumlist ? e.albumlist.join("") : ""
                        e.albumlist = e.albumlist.split('.PLS')
                        console.log(e.albumlist)
                        e.songlist = []
                        albumnum = parseInt('0x' + albumnum)
                        for (var i = 1; i <= albumnum; i++) {
                            var additionalContentData = (source + ',' + _g.toHex(i)).split(",")
                            _g.sendUdp(e, "02", "E4", e.device.deviceid, additionalContentData, e.device.mac, e.device.ip, e.device.port)
                        }
                        // e.albumlist = []
                        // for (i = 0; i < albumnum; i++) {
                        //     var albumname = _g.getadditional(msg, 7 + i,50)
                        //     e.albumlist.push(albumname)
                        // }
                        // console.log(albumlist)
                        // var additionalContentData = (source + ',' + e.albumno).split(",")
                        //     _g.sendUdp(e, "02", "E4", e.device.deviceid, additionalContentData, e.device.mac, e.device.ip, e.device.port)
                        // console.log("02E3")
                    }
                    if (operationcode.toLowerCase() == "02e5") {
                        var source = _g.getadditional(msg, 0)
                        var albumno = _g.getadditional(msg, 1)
                        var songpack = msg.substring(54, 56)
                        var additionalContentData = e.strToarr(songpack)
                        additionalContentData.unshift(albumno)
                        additionalContentData.unshift(source)
                        console.log(additionalContentData)
                        // let additionalContentData = (source + ',' + albumno + ',' + songpack).split(",")
                        _g.sendUdp(e, "02", "E6", e.device.deviceid, additionalContentData, e.device.mac, e.device.ip, e.device.port)
                        // console.log("02E5")
                    }
                    if (operationcode.toLowerCase() == "02e7") {
                        var songnum = _g.getadditional(msg, 5)
                        // e.songno = _g.getadditional(msg, 6)
                        var currentSonglist = msg.substring(68)
                        var albumno = _g.getadditional(msg, 3)
                        var songObj = {}
                        songObj.albumno = parseInt('0x'+albumno) 
                        var songlist = []
                        var currentSonglist = e.strToarr(currentSonglist)
                        for (var item of currentSonglist) {
                            item = parseInt('0x' + item)
                            songlist.push(String.fromCharCode(item))
                        }
                        songlist = songlist.join("")
                        songlist = songlist.match(/[a-zA-Z0-9. ]+/g)
                        // if (songlist) {
                        songlist = songlist ? songlist.join(""):""
                        songlist = songlist.split('.mp3')
                        for (var i = 0; i < songlist.length; i++) {
                            if (songlist[i] == "") {
                                songlist.splice(i, 1)
                            }
                        }
                        songObj.songlist = songlist
                        e.songlist.push(songObj)
                        console.log(e.songlist)
                        albumNote  = albumNote + 1
                        if(albumNote == albumnum-1) window.socketio.removeAllListeners("new_msg");
                        // }
                    }
                }
            });
        },
    },
    mounted() {
        console.log('music')
        this.readStatus(this)
    },

    components: {

    },
    mixins: [http]
}
</script>