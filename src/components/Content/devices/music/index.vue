<template>
    <el-col :span="24">
        <div v-loading="device.deviceProperty.musicLoading"  class="music" style="width:300px;height:550px;text-align: center;">
          <div v-show="albumShow" class="album">
                <div class="fa fa-reply album-btn-back btn-hand" @click="albumBtnClickBack"></div>
                <el-menu class="album-list">
                    <el-menu-item-group>
                        <span slot="title">Album</span>
                        <el-menu-item class="album-list-li" :index="album.albumNo" v-for="(album,key) in device.deviceProperty.albumlist" :key="key" @click="albumClick(album.albumNo)">
                            {{album.albumName}}</el-menu-item>
                    </el-menu-item-group>
                </el-menu>
            </div>
          <div class="music-nav">
            <div class="fa fa-bars album-btn btn-hand" @click="albumBtnClick"></div>
            <el-dropdown @command="sourceChange" class="source_list">
                <span class="el-dropdown-link">
                    {{sourceList[parseInt(device.deviceProperty.source) -1]}}<i class="el-icon-arrow-down el-icon--right"></i>
                </span>
                <el-dropdown-menu  slot="dropdown">
                    <el-dropdown-item command = "01">SD Card</el-dropdown-item>
                    <el-dropdown-item command = "02">FTP</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
                <i class="el-icon-refresh" @click="refresh"></i>
            </div>
            <div class="music-title">
            <p>{{device.deviceProperty.albumNow}}</p>
            <p>{{device.deviceProperty.songNow}}</p>
            </div>
            <div class="music-content" :span="24">
                <div class="music-content-top">
                    <div v-show="!device.deviceProperty.on_off" class=" content-icon mid" @click="play()">
                      <i class="fa fa-play btn-hand" style="margin-left:3px;"></i>
                    </div>
                    <div v-show="device.deviceProperty.on_off" class=" content-icon mid" @click="pause()">
                      <i class="fa fa-pause btn-hand"></i>
                    </div>
                    <div class="fa fa-step-backward btn-hand content-icon left" @click="pre()"></div>
                    <div class="fa fa-step-forward btn-hand content-icon right" @click="next()"></div>
                </div>
                <div class="music-content-bottom">
                    <div v-show="device.deviceProperty.mode == 'random'" class="fa fa-random btn-hand content-icon fl" style="margin-left:16px" @click="random()"></div>
                    <div v-show="device.deviceProperty.mode == 'single'" class="fa fa-exchange  btn-hand content-icon fl" style="margin-left:16px" @click="single()"></div>
                    <div v-show="device.deviceProperty.mode == 'allmusic'" class="fa fa-navicon  btn-hand content-icon fl" style="margin-left:16px" @click="allmusic()"></div>

                    <el-col :span="15" class="fl" >
                        <template>
                            <div>
                                <el-slider v-model="device.deviceProperty.vol" :min='0' :max='100' :step="1" @change="vol_change">
                                </el-slider>
                            </div>
                        </template>
                    </el-col>
                    <div class="fa fa-volume-up btn-hand content-icon fl"></div>
                </div>
            </div>
            <div class="model-item">
                <div></div>
            </div>
            <div class="music-list">
                <ul style="margin:0;padding:0;">
                    <li @dblclick="selectSong(song)" :class="song.select?'select':''" v-for="(song,key) in device.deviceProperty.songList" :key="key">
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
  /* border: 1px solid #d1dbe5;
  border-radius: 4px; */
  overflow: hidden;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12), 0 0 6px 0 rgba(0, 0, 0, 0.04);
  /* background-color: #666666; */
  color: #888;
}
.music .music-nav {
  position: relative;
  width: 100%;
  height: 35px;
}
.music .music-title p {
  margin: 10px 0 0 0;
}
.music .el-dropdown-link {
  cursor: pointer;
  color: #888;
}
.music .el-icon-refresh {
  position: absolute;
  right: 22px;
  top: 15px;
  font-size: 22px;
}
.music .el-icon-refresh:hover {
  color: #20a0ff;
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
.music .source_list {
  position: absolute;
  right: 42px;
  top: 18px;
  min-width: 95px;
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
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12), 0 0 6px 0 rgba(0, 0, 0, 0.04);
  z-index: 88899;
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
  overflow-x: hidden;
  overflow-y: auto;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12), 0 0 6px 0 rgba(0, 0, 0, 0.04);
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
  left: 128px;
  font-size:18px;
  border: 2px solid #999;
  border-radius: 38px;
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
  background-color: rgba(255, 255, 255, 0.3);
}

.music .model-item div {
  margin: 0 auto;
  width: 50px;
  height: 8px;
  border-bottom: 2px solid #ccc;
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
  background-color: #eee;
}

.music-list li.select {
  color: #20a0ff;
  background-color: #eee;
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
import musicApi from "./api";
export default {
  data() {
    return {
      albumShow: false,
      sourceList: ["SD Card", "FTP"]
    };
  },
  methods: {
    refresh() {
      Lockr.rm("music_" + this.device.id + "_" + this.device.deviceProperty.source);
      this.device.deviceProperty.albumlist = [];
      this.device.deviceProperty.songList = [];
      this.device.deviceProperty.songListAll = [];
      this.device.deviceProperty.musicLoading = true;
      console.log("music_test");
      musicApi.readSong(this.device);
    },
    sourceChange(command) {
      this.device.deviceProperty.source = command;
      musicApi.source_change(command,this.device);
      this.device.deviceProperty.albumlist = [];
      this.device.deviceProperty.songList = [];
      this.device.deviceProperty.songListAll = [];
      this.device.deviceProperty.musicLoading = true;
      var music = Lockr.get(
        "music_" + this.device.id + "_" + this.device.deviceProperty.source
      );
      if (music) {
        this.device.deviceProperty.albumlist = music.albumlist;
        this.device.deviceProperty.songList = music.songList;
        this.device.deviceProperty.songListAll = music.songList;
        this.device.deviceProperty.musicLoading = false;
      } else {
        musicApi.readSong(this.device);
      }
    },
    albumBtnClick() {
      this.albumShow = true;
    },
    albumClick(albumNo) {
      this.albumShow = false;
      this.device.deviceProperty.songList = [];
      for (var song of this.device.deviceProperty.songListAll) {
        if (song.albumNo == albumNo) {
          this.device.deviceProperty.songList.push(song);
        }
      }
    },
    albumBtnClickBack() {
      this.albumShow = false;
    },
    time_change(val) {
      musicApi.time_change(val, this.device);
    },
    vol_change(val) {
      musicApi.vol_change(val, this.device);
    },
    pre() {
      musicApi.pre(this.device);
    },
    next() {
      musicApi.next(this.device);
    },
    play() {
      this.device.deviceProperty.on_off = true;
      musicApi.play(this.device);
    },
    pause() {
      this.device.deviceProperty.on_off = false;
      musicApi.pause(this.device);
    },
    random() {
      this.device.deviceProperty.mode = "single";
      musicApi.random(this.device);
    },
    single() {
      this.device.deviceProperty.mode = "allmusic";
      musicApi.single(this.device);
    },
    allmusic() {
      this.device.deviceProperty.mode = "random";
      musicApi.allmusic(this.device);
    },
    selectSong(song) {
      this.device.deviceProperty.music_name = song.songName;
      for (var obj of this.device.deviceProperty.songList) {
        obj.select = false;
      }
      song.select = true;
      this.device.deviceProperty.on_off = true;
      musicApi.selectSong(this.device, song);
    },
    initMusic(device) {
      var music = Lockr.get(
        "music_" + device.id + "_" + this.device.deviceProperty.source
      );
      if (music) {
        this.device.deviceProperty.albumlist = music.albumlist;
        this.device.deviceProperty.songList = music.songList;
        this.device.deviceProperty.songListAll = music.songList;
        this.device.deviceProperty.musicLoading = false;
      } else {
        musicApi.readSong(device);
      }
    }
  },
  created() {
    console.log("music");
    musicApi.readStatus(this.device);
    this.initMusic(this.device)
  },
  destroyed(){
    musicApi.closeSocket();
  },
  computed: {},
  components: {},
  computed: {
    device() {
      var device = this.$store.state.device;
      return device;
    }
  }
};
</script>