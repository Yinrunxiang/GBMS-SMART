<template>
    <div class="container-out" style="height:100%">
        <div v-show="showHome" class="container-in">
            <div class="container-home">
                <div class="build">
                    <a class="build-img">
                        <img src="../../assets/images/build.jpg"><img>
                    </a>
                    <p class="p-title">Build</p>
                </div>
                <div class="float">
                    <div class="float-centent" float="12" @click="floatClick">Floor 12</div>
                    <div class="float-centent" float="11" @click="floatClick">Floor 11</div>
                    <div class="float-centent" float="10" @click="floatClick">Floor 10</div>
                    <div class="float-centent" float="9" @click="floatClick">Floor 9</div>
                    <div class="float-centent" float="8" @click="floatClick">Floor 8</div>
                    <div class="float-centent" float="7" @click="floatClick">Floor 7</div>
                    <div class="float-centent" float="6" @click="floatClick">Floor 6</div>
                    <div class="float-centent" float="5" @click="floatClick">Floor 5</div>
                    <div class="float-centent" float="4" @click="floatClick">Floor 4</div>
                    <div class="float-centent" float="3" @click="floatClick">Floor 3</div>
                    <div class="float-centent" float="2" @click="floatClick">Floor 2</div>
                    <div class="float-centent" float="1" @click="floatClick">Floor 1</div>
                    <p class="p-title">Floor</p>
                </div>
            </div>
            <!-- <div class="container-float" :span="24">
                    <div>
                        <div class="float-row" :span="24">
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                        </div>
                        <div class="float-row" :span="24">
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                        </div>
                        <div class="float-aisle" :span="24">Aisle</div>
                        <div class="float-row row">
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                        </div>
                        <div class="float-row" :span="24">
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                            <div class="float-room" :span="6">ROOM</div>
                        </div>
                    </div>
                </div>
                <div class="container-room" :span="24">
                    <div class="parlor  room" :span="24">Parlor</div>
                    <div class="livingroom room" :span="16">Living room</div>
                    <div class="bathroom room"  :span="8">Bathroom</div>
                </div> -->
        </div>
        <div v-show="showImage" class="mode">
            <div class="backGroundImga">
                <div class="room1" @click="roomClick('Boss Office')"></div>
                <div class="room2" @click="roomClick('Boss Office')"></div>
                <div class="room3" @click="roomClick('Boss Office')"></div>
                <div class="room4" @click="roomClick('Boss Office')"></div>
                <div class="room5" @click="roomClick('Boss Office')"></div>
                <div class="room6" @click="roomClick('Boss Office')"></div>
                <div class="room7" @click="roomClick('Boss Office')"></div>
                <div class="room8" @click="roomClick('Boss Office')"></div>
                <div class="room9" @click="roomClick('Boss Office')"></div>
                <div class="room10" @click="roomClick('Boss Office')"></div>
                <div class="room11" @click="roomClick('Boss Office')"></div>
                <div class="room12" @click="roomClick('Boss Office')"></div>
                <div class="room13" @click="roomClick('Boss Office')"></div>
            </div>
        </div>
        <div v-show="showTypeList" style="background-color: #fff">
            <deviceList :typeList="typeList"></deviceList>
        </div>
    </div>
</template>





<script>
import deviceList from '../Common/device/deviceList'
export default {
    data() {
        return {
            showHome:true,
            showImage: false,
            typeList:[],
            showTypeList:false
        }
    },
    methods: {
        floatClick() {
            this.showImage = true
            this.showHome = false
            this.showTypeList = false
        },
        roomClick(val) {
            console.log(this.countryArr)
            this.showImage = false
            this.showHome = false
            this.showTypeList = true
            for (var country of this.countryArr) {
                for (var address of country.addressList) {
                    if(val == address.name){
                        this.typeList = address.typeList
                        console.log(this.typeList)
                    }
                }
            }
        }
    },
    created() {
        console.log("report")
    },
    components: {
        deviceList
    },
    computed: {
        countryArr() {
            return this.$store.state.countryArr
        },
    }
}
</script>


<style>
.container-out {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: #ccc;
}

.container-in {
    position: relative;
    width: 800px;
    height: 550px;
    padding: 0;
    margin: 10px auto;
    /* border: 1px solid #000;
    border-radius: 10px; */
}

.build {
    width: 200px;
    height: 300px;
    margin: 0 30px 0 100px;
}

.build-img {
    display: inline-block;
    width: 100px;
    height: 300px;
    margin: 30px auto;
}

.p-title {
    text-align: center;
    font-size: 36px;
}

.float {
    position: absolute;
    right: 50px;
    top: 30px;
    width: 200px;
    height: 300px;
    margin: 0 30px 0 100px;
}

.float-centent {
    width: 200px;
    height: 30px;
    margin: 3px auto;
    background-color: rgb(88, 183, 255);
    border: 1px solid #fff;
    border-radius: 5px;
    text-align: center;
    color: #fff;
    font-size: 14px
}

.container-float {
    text-align: center;
    color: #fff;
    font-size: 36px;
}

.float-room {
    height: 130px;
    background-color: rgb(88, 183, 255);
    border: 1px solid #fff;
}

.float-aisle {
    height: 80px;
    background-color: rgb(88, 183, 255);
    border: 1px solid #fff;
}

.container-room {
    text-align: center;
    color: #fff;
    font-size: 36px;
}

.container-room .room {
    background-color: rgb(88, 183, 255);
    border: 1px solid #fff;
}

.parlor {
    height: 400px;
}

.livingroom,
.bathroom {
    height: 200px
}


.backGroundImga {
    position: relative;
    width: 1031px;
    height: 609px;
    margin: 5px auto;
    background-image: url("../../assets/images/hotal.jpg");
    background-repeat: no-repeat;
    background-size: 100% 100%;
    -moz-background-size: 100% 100%;
}

.backGroundImga>div:hover {
    border: 2px solid #20A0FF;
}

.room1 {
    position: absolute;
    left: 10px;
    top: 10px;
    width: 108px;
    height: 226px;
}

.room2 {
    position: absolute;
    left: 123px;
    top: 10px;
    width: 264px;
    height: 226px;
}

.room3 {
    position: absolute;
    left: 393px;
    top: 10px;
    width: 118px;
    height: 226px;
}

.room4 {
    position: absolute;
    left: 519px;
    top: 10px;
    width: 246px;
    height: 226px;
}

.room5 {
    position: absolute;
    left: 772px;
    top: 10px;
    width: 144px;
    height: 226px;
}

.room6 {
    position: absolute;
    left: 922px;
    top: 10px;
    width: 100px;
    height: 226px;
}

.room7 {
    position: absolute;
    left: 10px;
    top: 326px;
    width: 250px;
    height: 231px;
}

.room8 {
    position: absolute;
    left: 266px;
    top: 326px;
    width: 120px;
    height: 275px;
}

.room9 {
    position: absolute;
    left: 393px;
    top: 326px;
    width: 120px;
    height: 275px;
}

.room10 {
    position: absolute;
    left: 520px;
    top: 326px;
    width: 120px;
    height: 275px;
}

.room11 {
    position: absolute;
    left: 645px;
    top: 326px;
    width: 120px;
    height: 275px;
}

.room12 {
    position: absolute;
    left: 770px;
    top: 326px;
    width: 120px;
    height: 275px;
}

.room13 {
    position: absolute;
    left: 897px;
    top: 326px;
    width: 120px;
    height: 275px;
}
</style>