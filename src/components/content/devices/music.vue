<template>
    <el-col :span="24">
        <div class="music" style="width:350px;height:550px;padding:0;margin:20px auto;text-align: center;">
            <div class="fa fa-bars album-btn" @click="albumBtnClick"></div>
            <div v-show="albumShow" class="album">
                <div class="fa fa-reply album-btn-back" @click="albumBtnClickBack"></div>
                <el-menu class="album-list">
                    <el-menu-item-group>
                        <span slot="title">Album</span>
                        <el-menu-item class="album-list-li" :index="album.albumNo" v-for="album in deviceProperty.albumlist" @click="albumClick(album.albumNo)">
                            </i>{{album.albumName}}</el-menu-item>
                    </el-menu-item-group>
                    <!-- <el-submenu index="1" class="album-btn">
                                                    <template slot="title">
                                                        <i class="el-icon-message"></i>
                                                        <span slot="title">导航一</span>
                                                    </template> -->
                    <!-- <el-menu-item v-for="album in deviceProperty.albumlist" index="plan">
                                                        <i class="el-icon-menu"></i>{{album.albumName}}</el-menu-item> -->

                    <!-- </el-submenu> -->
                </el-menu>
            </div>
            <div class="music-title">
                <p>{{deviceProperty.music_name}}</p>
            </div>
            <div class="music-content" :span="24">
                <div class="music-content-top">
                    <div v-show="!deviceProperty.on_off" class="fa fa-play content-icon mid" @click="play()"></div>
                    <div v-show="deviceProperty.on_off" class="fa fa-pause content-icon mid" @click="pause()"></div>
                    <div class="fa fa-step-backward content-icon left" @click="pre()"></div>
                    <div class="fa fa-step-forward content-icon right" @click="next()"></div>
                </div>
                <div class="music-content-bottom">
                    <div v-show="deviceProperty.mode == 'random'" class="fa fa-random content-icon fl" style="margin-left:16px" @click="random()"></div>
                    <div v-show="deviceProperty.mode == 'single'" class="fa fa-exchange content-icon fl" style="margin-left:16px" @click="single()"></div>
                    <div v-show="deviceProperty.mode == 'allmusic'" class="fa fa-navicon content-icon fl" style="margin-left:16px" @click="allmusic()"></div>

                    <el-col :span="15" class="fr" style="margin-right:26px">
                        <template>
                            <div>
                                <el-slider v-model="deviceProperty.vol" :min='0' :max='79' :step="1" @change="vol_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <div class="fa fa-volume-up content-icon fr"></div>
                </div>
            </div>
            <div class="model-item">
                <div></div>
            </div>
            <div v-loading="deviceProperty.musicLoading" class="music-list">
                <ul style="margin:0;padding:0;">
                    <li @dblclick="selectSong(song)" :class="song.select?'select':''" v-for="song in deviceProperty.songList">
                        <div class="song">
                            <!-- <div class="song-num">{{song.songNo}}</div> -->
                            <div class="song-name">{{song.songName}}</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </el-col>
</template>

<style>
.music {
    position: relative;
    border: 1px solid #d1dbe5;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .12), 0 0 6px 0 rgba(0, 0, 0, .04);
    background-color: #666666;
    color: #CCCCCC;
}

.music .album-btn {
    position: absolute;
    left: 8px;
    top: 1px;
    font-size: 22px;
    width: 50px;
    height: 50px;
    line-height: 50px;
}

.album {
    position: absolute;
    left: 0;
    top: 0;
    text-align: left;
    width: 150px;
    height: 100%;
    border: 1px solid #d1dbe5;
    overflow: hidden;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .12), 0 0 6px 0 rgba(0, 0, 0, .04);
    z-index: 99999;
}

.music .album-btn-back {
    position: absolute;
    left: 120px;
    top: 8px;
    font-size: 16px;
    width: 30px;
    height: 30px;
    line-height: 30px;
    color: #20a0ff;
    z-index: inherit;
}

.music .album-list {
    position: absolute;
    left: 0;
    top: 0;
    text-align: left;
    width: 150px;
    height: 100%;
    border: 1px solid #d1dbe5;
    overflow: hidden;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .12), 0 0 6px 0 rgba(0, 0, 0, .04);
}



.music-content {

    width: 100%;
    height: 130px;
}

.music-content .left {
    position: absolute;
    top: 30px;
    left: 15px;
}

.music-content .music-content-top {
    position: relative;
    width: 100%;
    height: 90px;
}

.music-content .music-content-bottom {

    width: 100%;
    height: 40px;
    /* line-height: 40px; */
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

.music-content .content-icon:hover {
    color: #20a0ff;
    border-color: #20a0ff;
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

.music-list {

    width: 100%;
    height: 372px;

    overflow-y: scroll;
    /* overflow: scroll; */
}

.music-list li {
    padding-left: 10px;
    width: 100%;
    height: 50px;
}

.music-list li:hover {
    background-color: #333;
}

.music-list li.select {
    color: #20a0ff;
    background-color: #333;
}

.music-list li .song {
    width: 100%;
    height: 50px;
    padding: 5px 0px;
}

.music-list li .song-num {
    float: left;
    width: 10%;
    height: 40px;
    line-height: 40px;
    font-size: 20px;
}

.music-list li .song-name {
    float: left;
    text-align: left;
    overflow: hidden;
    width: 100%;
    height: 40px;
    line-height: 40px;
    font-size: 16px;
}
</style>

<script>
import musicApi from "./music/music"
export default {
    data() {
        return {
            deviceProperty: {
                vol: 20,
                mode: "random",
                on_off: false,
                music_name: "Waitting",
                music_autor: "Waitting",
                time_now: 0,
                time_over: 0,
                albumno: 0,
                albumlist: [],
                songno: 0,
                songList: [{
                    songNo: 1,
                    songName: 'Waitting',
                    select: true
                }],
                songListAll: [],
                musicLoading:true,
            },
            albumShow: false
        }
    },
    methods: {
        albumBtnClick() {
            this.albumShow = true
        },
        albumClick(albumNo) {
            this.albumShow = false
            this.deviceProperty.songList = []
            for (var song of this.deviceProperty.songListAll) {
                if (song.albumNo == albumNo) {
                    this.deviceProperty.songList.push(song)
                }
            }

        },
        albumBtnClickBack() {
            this.albumShow = false
        },
        time_change(val) {
            musicApi.time_change(val, this.device, this.deviceProperty)
        },
        vol_change(val) {
            musicApi.vol_change(val, this.device, this.deviceProperty)
        },
        pre() {
            musicApi.pre(this.device, this.deviceProperty)
            var len = this.deviceProperty.songList.length
            for (var key in this.deviceProperty.songList) {
                if (this.deviceProperty.songList[key].select) {
                    this.deviceProperty.songList[key].select = false
                    if (key == 0) {
                        this.deviceProperty.music_name = this.deviceProperty.songList[len - 1].songName
                        this.deviceProperty.songList[len - 1].select = true
                        return
                    } else {
                        this.deviceProperty.music_name = this.deviceProperty.songList[key - 1].songName
                        this.deviceProperty.songList[key - 1].select = true
                        return
                    }

                }
            }
        },
        next() {
            musicApi.next(this.device, this.deviceProperty)
            var len = this.deviceProperty.songList.length
            for (var key in this.deviceProperty.songList) {
                key = parseInt(key)
                if (this.deviceProperty.songList[key].select) {
                    this.deviceProperty.songList[key].select = false
                    if (key == len - 1) {
                        this.deviceProperty.music_name = this.deviceProperty.songList[0].songName
                        this.deviceProperty.songList[0].select = true
                        return
                    } else {
                        this.deviceProperty.music_name = this.deviceProperty.songList[key + 1].songName
                        this.deviceProperty.songList[key + 1].select = true
                        return
                    }

                }
            }
        },
        play() {
            this.deviceProperty.on_off = true
            musicApi.play(this.device, this.deviceProperty)
        },
        pause() {
            this.deviceProperty.on_off = false
            musicApi.pause(this.device, this.deviceProperty)
        },
        random() {
            this.deviceProperty.mode = 'single'
            musicApi.random(this.device, this.deviceProperty)
        },
        single() {
            this.deviceProperty.mode = 'allmusic'
            musicApi.single(this.device, this.deviceProperty)
        },
        allmusic() {
            this.deviceProperty.mode = 'random'
            musicApi.allmusic(this.device, this.deviceProperty)
        },
        selectSong(song) {
            this.deviceProperty.music_name = song.songName
            for (var obj of this.deviceProperty.songList) {
                obj.select = false
            }
            song.select = true
            this.deviceProperty.on_off = true
            musicApi.selectSong(this.device, this.deviceProperty, song)
        }
    },
    mounted() {
        console.log('music')
        musicApi.readStatus(this.device, this.deviceProperty)
        
        
    },
    computed: {

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
            // device.songList = []
            return device
        }
    }
}
</script>