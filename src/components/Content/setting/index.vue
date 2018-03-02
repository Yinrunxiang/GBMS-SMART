<template>
    <el-row class="panel m-w-1100">
        <el-col :span="24" class="contral-panel-center">
            <!--<el-col :span="4">-->
            <div class="nav" :class='"w-"+addresswidth'>
                <aside class="ovf-hd fl address" :class='"w-"+addresswidth' @mouseenter="enteraddress" @mouseleave="leaveaddress">
                    <div v-for="item in addresslist" @mouseenter="addressChange(item)">
                        <div class="c-light-gray m-t-10">
                            <div class="w-100p h-40 p-l-20 left-menu pointer c-blue">{{item}}
                            </div>
                        </div>
                    </div>
                    <aside class="w-100 ovf-hd fl type h-100p" :class='"w-"+typewidth' v-show="ontype" @mouseenter="entertype" @mouseleave="leavetype">
                        <div v-for="item in typelist" @mouseenter="typeChange(item)">
                            <div class="c-light-gray m-t-10">
                                <div class="w-100p h-40 p-l-20 left-menu pointer c-blue">
                                    <i class="fa" :class="iconstyle(item)"></i>
                                    {{item}}</div>
                            </div>
                        </div>
                        <aside class="w-100 ovf-hd fl device h-100p" v-show="ondevice">
                            <div v-for="item in list">
                                <div class="c-light-gray m-t-10">
                                    <div class="w-100p h-40 p-l-20 left-menu pointer c-blue" @click="deviceChange(item)">
                                        <i class="fa" :class="iconstyle(item.devicetype)"></i> {{item.device}}</div>
                                </div>
                            </div>
                        </aside>
                    </aside>
                </aside>
            </div>
            <section class="contral-panel-c-c">
                <div class="grid-content bg-purple-light">
                    <el-col :span="24">
                        <transition name="fade" mode="out-in" appear>
                            <!-- <light :device = "device" v-show="devicetype == 'light'"></light> -->
                            <router-view :device="device"></router-view>
                        </transition>
                    </el-col>
                </div>
            </section>
        </el-col>
        <!-- <changePwd ref="changePwd"></changePwd> -->
    
    </el-row>
</template>
<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity .5s
}

.fade-enter,
.fade-leave-active {
    opacity: 0
}

.contral-panel-center {
    background: #324057;
    position: absolute;
    top: 0px;
    bottom: 0px;
    overflow: hidden;
}

.contral-panel-c-c {
    background: #f1f2f7;
    position: absolute;
    right: 0px;
    top: 0px;
    bottom: 0px;
    left: 150px;
}

.nav {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    height: 100%;
    background: #324057;
    border-left: 1px solid #42566c;
    z-index: 99
}

.address {
    position: absolute;
    left: 0;
    top: 0;
}

.type {
    position: absolute;
    left: 150px;
    top: 0;
}

.device {
    position: absolute;
    left: 100px;
    top: 0;
}
</style>
<script>
import http from '../../assets/js/http'
import light from './devices/light.vue'
export default {
    data() {
        return {
            addresslist: [],
            alltypelist: [],
            typelist: [],
            alldevicelist: [],
            devicelist: [],
            list: [],
            ontype: false,
            ondevice: false,
            addresswidth: 150,
            typewidth: 100,
            device: {},
            devicetype: "",
            

        }
    },
    methods: {
        //鼠标进入地址列表事件
        enteraddress() {
            this.ontype = true
            this.addresswidth = 250
        },
        //鼠标离开地址列表事件
        leaveaddress() {
            this.ontype = false
            this.addresswidth = 150

        },
        //鼠标进入类型列表事件
        entertype() {
            this.ondevice = true
            this.addresswidth = 350
            this.typewidth = 200
        },
        //鼠标离开类型列表事件
        leavetype() {
            this.ondevice = false
            this.addresswidth = 250
            this.typewidth = 100
        },
        //地址选择事件
        addressChange(address) {
            this.devicelist = []
            this.typelist = []
            for (var item of this.alldevicelist) {
                if (item.address == address)
                    this.devicelist.push(item)
            }
            for (var item of this.devicelist) {
                if (this.typelist.indexOf(item.devicetype) == -1) {
                    this.typelist.push(item.devicetype)
                }
            }
            // console.log('typelist:'+this.typelist+'/n')
        },
        //类型选择事件
        typeChange(devicetype) {
            this.list = []
            for (var item of this.devicelist) {
                if (item.devicetype == devicetype)
                    this.list.push(item)
            }
        },
        //设备选择事件
        deviceChange(item) {
            this.device = item
            this.devicetype = item.devicetype
            let url = '/home/contral/' + item.devicetype
            console.log('typelist:' + url + '/n');
            this.$store.dispatch('setDevice', item)
            if (url != this.$route.path) {
                router.push(url)
            } else {
                _g.shallowRefresh(this.$route.name)
            }
            // router.push(url)

        },
        iconstyle(type) {
            switch (type) {
                case 'light':
                    return 'fa-lightbulb-o'
                    break
                case 'ac':
                    return 'fa-thermometer'
                    break
            }
        }
    },
    created() {
        // _g.openGlobalLoading()
        // const data = {
        //     params: {
        //         action: 'search'
        //     }
        // }
        // this.apiGet('device/index.php', data).then((res) => {
        //     // console.log(res)
        //     for (var item of res) {
        //         if (this.addresslist.indexOf(item.address) == -1) {
        //             this.addresslist.push(item.address);
        //         }
        //         this.alldevicelist.push(item)
        //     }
        //     _g.closeGlobalLoading()
        // })

    },
    mounted(){
        console.log('contral')
        for (var item of this.devices) {
            if (this.addresslist.indexOf(item.address) == -1) {
                this.addresslist.push(item.address);
            }
        }
        this.alldevicelist = this.devices
    },
    components: {
        light
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
    },
    mixins: [http]
}
</script>
