<template>
    <div>
        <div v-show="!showDeviceUpdate" class="p-20">
            <div class="m-b-20 ovf-hd">
                <div class="fl">
                    <router-link class="btn-link-large add-btn" to="plan/add">
                        <i class="el-icon-plus"></i>&nbsp;&nbsp;Add device
                    </router-link>
                </div>
                <div class="fl w-300 m-l-30">
                    <el-input placeholder="Please enter the device name" v-model="keywords">
                        <el-button slot="append" icon="search" @click="search()"></el-button>
                    </el-input>
                </div>
            </div>
            <el-table :data="tableData" style="width: 100%" @selection-change="selectItem" @row-dblclick="rowDblclick">
                <el-table-column type="selection" width="50">
                </el-table-column>
                <el-table-column prop="device" label="Device name" width="150">
                </el-table-column>
                <el-table-column label="Device type" prop="devicetype" width="150">
                </el-table-column>
                <el-table-column label="Address" prop="address" width="150">
                </el-table-column>
                <el-table-column label="status" prop="status" width="150">
                </el-table-column>
                <el-table-column label="IP" prop="ip" width="150">
                </el-table-column>
                <el-table-column label="Start Time" prop="start" width="220">
                    <template scope="scope">
                        <el-time-select placeholder="Start Time" @change="startTimeChange(scope.row)" v-model="scope.row.starttime" :picker-options="{
                              start: '08:00',
                              step: '00:10',
                              end: '18:00'
                            }">
                        </el-time-select>
                    </template>
                </el-table-column>
                <el-table-column label="Over Time" prop="over" width="220">
                    <template scope="scope">
                        <el-time-select placeholder="End Time" @change="endTimeChange(scope.row)" v-model="scope.row.endtime" :picker-options="{
                          start: '08:00',
                          step: '00:10',
                          end: '18:00',
                          minTime: scope.row.starttime
                        }">
                        </el-time-select>
                    </template>
                </el-table-column>
            </el-table>
            <div class="pos-rel p-t-20">
                <div>
                    <el-button size="small" type="success" @click="setStatusBtn('enabled')">Enabled</el-button>
                    <el-button size="small" type="warning" @click="setStatusBtn('disabled')">Disabled</el-button>
                    <el-button size="small" type="danger" @click="deleteBtn()">Delete</el-button>
                </div>
                <div class="block pages">
                    <el-pagination @current-change="handleCurrentChange" layout="prev, pager, next" :page-size="limit" :current-page="currentPage" :total="dataCount">
                    </el-pagination>
                </div>
            </div>
        </div>
        <div v-show="showDeviceUpdate">
            <deviceUpdate :device="thisdevice" :notHotel='notHotel' @changeUpdate="changeUpdate"></deviceUpdate>
        </div>
    </div>
</template>

<script>
import btnGroup from '../Common/btn-group.vue'
import deviceUpdate from './plan/update'
import http from '../../assets/js/http'

export default {
    //  currentPage        页码
    //  keywords           关键字
    //  multipleSelection  被选中的数据
    //  limit              每页最大行数
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
        changeUpdate(data){
            this.showDeviceUpdate = data
        },
        //搜索关键字
        search() {
            router.push({ path: this.$route.path, query: { keywords: this.keywords, page: 1 } })
        },
        //获取被选中的数据
        selectItem(val) {
            this.multipleSelection = val
            console.log(this.multipleSelection)
        },
        //换页事件
        handleCurrentChange(page) {
            router.push({ path: this.$route.path, query: { keywords: this.keywords, page: page } })
        },
        rowDblclick(row) {
            this.showDeviceUpdate= true;
            this.thisdevice = row
            console.log(this.thisdevice)
            // let url = '/home/plan/update'
            // this.$store.dispatch('setDevice', row)
            // router.push(url)
        },
        //开始时间改变事件
        startTimeChange(row) {
            const data = {
                params: {
                    selection: row,
                    type: 'start'
                    // status: status
                }
            }
            console.log(row)
            this.apiGet('device/index.php?action=setTime', data).then((res) => {
                if (res[0]) {
                    _g.toastMsg('success', res[1])
                } else {
                    _g.toastMsg('error', res[1])
                }

            })
        },
        //结束时间改变事件
        endTimeChange(row) {
            const data = {
                params: {
                    selection: row,
                    type: 'end'
                    // status: status
                }
            }
            console.log(row)
            this.apiGet('device/index.php?action=setTime', data).then((res) => {
                if (res[0]) {
                    _g.toastMsg('success', res[1])
                } else {
                    _g.toastMsg('error', res[1])
                }

            })
        },
        //保存状态点击事件
        setStatusBtn(status) {
            const data = {
                params: {
                    selections: this.multipleSelection,
                    status: status
                }
            }
            this.apiGet('device/index.php?action=setStatus', data).then((res) => {
                if (res[0]) {
                    for (var selection of this.multipleSelection) {
                        selection.status = status
                    }
                    _g.toastMsg('success', res[1])
                } else {
                    _g.toastMsg('error', res[1])
                }

            })
        },
        //删除按钮事件
        deleteBtn() {
            this.$confirm('Are you sure to delete the selected data?', 'Tips', {
                confirmButtonText: 'Yse',
                cancelButtonText: 'No',
                type: 'warning'
            }).then(() => {
                const data = {
                    params: {
                        selections: this.multipleSelection
                    }
                }
                this.apiGet('device/index.php?action=delete', data).then((res) => {
                    if (res[0]) {

                        var devices = this.$store.state.devices
                        for (var i = 0; i < devices.length; i++) {
                            for (var selection of this.multipleSelection) {
                                if (devices[i].device == selection.device) {
                                    devices.splice(i, 1)
                                }
                            }
                        }
                        this.$store.dispatch('setDevices', devices)
                        _g.toastMsg('success', res[1])
                    } else {
                        _g.toastMsg('error', res[1])
                    }

                })
            }).catch(() => {
                // catch error
            })
        },
        //获取页码
        getCurrentPage() {
            let data = this.$route.query
            if (data) {
                if (data.page) {
                    this.currentPage = parseInt(data.page)
                } else {
                    this.currentPage = 1
                }
            }
        },
        //获取关键值
        getKeywords() {
            let data = this.$route.query
            if (data) {
                if (data.keywords) {
                    this.keywords = data.keywords
                } else {
                    this.keywords = ''
                }
            }
        },
        //初始化时统一加载
        init() {
            this.getKeywords()
            this.getCurrentPage()
            // this.getAllDevices()
        }
    },
    created() {
        console.log('Plan')
        _g.closeGlobalLoading()
        this.init()
    },
    components: {
        btnGroup,
        deviceUpdate,
    },
    computed: {
        //从vuex中获取设备数据
        tableData() {
            return this.$store.state.devices
        },
        //从vuex中获取设备数据条数
        dataCount() {
            return this.$store.state.devices.length
        }
    },
    mixins: [http]
}
</script>