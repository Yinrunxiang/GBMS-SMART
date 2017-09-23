<template>
    <el-col :span="24">
        <div class="music" style="width:350px;height:550px;padding:0;margin:20px auto;text-align: center;">

            <div class="music-title">
                <p>{{deviceProperty.music_name}}</p>
            </div>
            <div class="music-content" :span="24">
                <div v-show="!deviceProperty.on_off" class="fa fa-play content-icon mid" @click="play()"></div>
                <div v-show="deviceProperty.on_off" class="fa fa-pause content-icon mid" @click="pause()"></div>
                <div class="fa fa-step-backward content-icon left" @click="pre()"></div>
                <div class="fa fa-step-forward content-icon right" @click="next()"></div>
            </div>
            <div class="model-item">
                <div></div>
            </div>
            <div class="music-list">
                <ul style="margin:0;padding:0;">
                    <li @dblclick="selectSong(song)" v-for="song in deviceProperty.songlist">
                        <div class="song">
                            <!-- <div class="song-num">{{song.songNo}}</div> -->
                            <div class="song-name">{{song.songName}}</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- <el-col :span="8">
                                <div>
                                    <div class="">
                                        <i class="fa fa-music big-music-icon"></i>
                                    </div>
                                </div>
                            </el-col>
                            <el-col :span="16" class="m-b-20 p-l-20 p-r-20 ovf-hd" style="margin-top:80px">
                                <el-row>
                                    <el-col :span="8" style="line-height:36px;">
                                        <span>{{deviceProperty.music_name}}</span>
                                        <span>{{deviceProperty.music_autor}}</span>
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
                                                    <el-slider v-model="deviceProperty.vol" :min='0' :max='79' :step="1" @change="vol_change">
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
                                                <el-slider v-model="deviceProperty.time_now" :min='0' :max='30' :step="1" @change="time_change">
                                                </el-slider>
                                            </div>
                                        </template>
                                    </el-col>
                                    <el-col :span="5" :offset="1">
                                        <span>{{deviceProperty.time_now}}</span>
                                        <span>{{deviceProperty.time_over}}</span>
                                    </el-col>
                                </el-row>
                                <el-row class="m-t-20">
                                    <el-col :span="10">
                                        <el-button @click="pre()" class="music-btn">
                                            <i class="fa fa-step-backward"></i>
                                        </el-button>
                                        <el-button @click="play()" class="music-btn" v-show="deviceProperty.on_off == false">
                                            <i class="fa fa-play"></i>
                                        </el-button>
                                        <el-button @click="pause()" class="music-btn" v-show="deviceProperty.on_off">
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
                                                    <li @dblclick="selectSong(song)" v-for="song in deviceProperty.songlist">{{song.songName}}</li>

                                        </ul>
                                    </el-col>
                                </el-row>
                            </el-col> -->
    </el-col>
</template>

<style>
.music {
    border: 1px solid #d1dbe5;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .12), 0 0 6px 0 rgba(0, 0, 0, .04);
    background-color: #666666;
    color: #CCCCCC;
}



/* .music-content .content-border {
    background-color: #333;
    margin: 20px auto;
    width: 180px;
    height: 180px;
    border:1px solid #aaa;
    border-radius: 180px
} */

.music-content {
    position: relative;
    width: 100%;
    height: 130px;
}

.music-content .left {
    position: absolute;
    top: 30px;
    left: 15px;
}

.music-content .mid {
    position: absolute;
    top: 30px;
    left: 156px;
    border: 2px solid #CCCCCC;
    border-radius: 38px
}

.music-content .right {
    position: absolute;
    top: 30px;
    right: 15px;
}

.music-content .content-icon {
    width: 38px;
    height: 38px;
    line-height: 38px;
    font-size: 18px;
}

.music .model-item {
    width: 100%;
    height: 16px;
    background-color: rgba(255, 255, 255, 0.3)
}

.music .model-item div {
    margin: 0 auto;
    width: 50px;
    height: 8px;
    border-bottom: 2px solid #ccc
}
.music-list{

    width: 100%;
    height: 372px;
    
    overflow-y:scroll;
    /* overflow: scroll; */
}
.music-list li {
        padding-left: 10px;
    width: 100%;
    height: 50px;
}
.music-list li:hover{
    background-color: #333;
}

.music-list li .song{
    width: 100%;
    height: 50px;
    padding: 5px 0px;
}

.music-list li .song-num{
    float: left;
    width:10%;
    height: 40px;
    line-height:40px;
    font-size:20px;
}
.music-list li .song-name{
    float: left;
    text-align: left;
    overflow:hidden;
    width:100%;
    height: 40px;
    line-height:40px;
    font-size:16px;
}




/* .music-btn {
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

.songlist li {
    padding-left: 10px;
    height: 30px;
    line-height: 30px;
    border-bottom: 1px solid #c4c4c4;
} */
</style>

<script>
import musicApi from "./music/music"
export default {
    data() {
        return {
            deviceProperty: {
                vol: 0,
                on_off: false,
                music_name: "any",
                music_autor: "any",
                time_now: 0,
                time_over: 0,
                albumno: 0,
                albumlist: [],
                songno: 0,
                songlist: [{
                    songNo:1,
                    songName:'asdasdasd'
                }],
            }
        }
    },
    methods: {
        time_change(val) {
            musicApi.time_change(val, this.device, this.deviceProperty)
        },
        vol_change(val) {
            musicApi.vol_change(val, this.device, this.deviceProperty)
        },
        pre() {
            musicApi.pre(this.device, this.deviceProperty)
        },
        next() {
            musicApi.next(this.device, this.deviceProperty)
        },
        play() {
            this.deviceProperty.on_off=true
            musicApi.play(this.device, this.deviceProperty)
        },
        pause() {
            this.deviceProperty.on_off=false
            musicApi.pause(this.device, this.deviceProperty)
        },
        random() {
            musicApi.random(this.device, this.deviceProperty)
        },
        single() {
            musicApi.single(this.device, this.deviceProperty)
        },
        allmusic() {
            musicApi.allmusic(this.device, this.deviceProperty)
        },
        selectSong(song) {
            this.deviceProperty.music_name = song.songName
            musicApi.selectSong(this.device, this.deviceProperty, song)
        }
    },
    mounted() {
        console.log('music')
        musicApi.readStatus(this.device, this.deviceProperty)
    },

    components: {

    },
    computed: {
        device() {
            var device = this.$store.state.device
            // device.vol = 0,
            // device.on_off = false
            // device.music_name = "any"
            // device.music_autor = "any"
            // device.time_now = 0
            // device.time_over = 0
            // device.albumno = 0
            // device.albumlist = []
            // device.songno = 0
            // device.songlist = []
            return device
        }
    }
}
</script>