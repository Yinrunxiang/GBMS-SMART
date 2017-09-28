<template>
    <div class="p-20">
        <div class="m-b-20 ovf-hd">
            <div class="fl">
                <router-link class="btn-link-large add-btn" to="light/add">
                    <i class="el-icon-plus"></i>&nbsp;&nbsp;Add Mode
                </router-link>
            </div>
            <div class="fl w-300 m-l-30">
                <el-input placeholder="Please enter the model" v-model="keywords">
                    <el-button slot="append" icon="search" @click="search()"></el-button>
                </el-input>
            </div>
        </div>
        <el-table :data="tableData" style="width: 100%" @selection-change="selectItem" @row-dblclick="rowDblclick">
            <el-table-column type="selection" width="50">
            </el-table-column>
            <el-table-column label="Breed(W)" prop="breed" width="150">
            </el-table-column>
            <el-table-column label="Watts" prop="watts" width="150">
            </el-table-column>
            <el-table-column label="Run Time(h)" prop="run_time" width="200">
            </el-table-column>
            <el-table-column label="Status" prop="status">
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
</template>

<script>
import http from '../../../../../assets/js/http'

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
            multipleSelection: [],
            limit: 15,
        }
    },
    methods: {
        //搜索关键字
        search() {
            router.push({ path: this.$route.path, query: { keywords: this.keywords, page: 1 } })
        },
        rowDblclick(row){
            var url = 'light/update'
             router.push({ path: url, query:row })
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

        //保存状态点击事件
        setStatusBtn(status) {
            const data = {
                params: {
                    selections: this.multipleSelection,
                    status: status
                }
            }
            this.apiGet('device/light_breed.php?action=setStatus', data).then((res) => {
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
                this.apiGet('device/light_breed.php?action=delete', data).then((res) => {
                    if (res[0]) {

                        var light_breed = this.$store.state.light_breed
                        for (var i = 0; i < light_breed.length; i++) {
                            for (var selection of this.multipleSelection) {
                                if (light_breed[i].breed == selection.breed) {
                                    light_breed.splice(i, 1)
                                }
                            }
                        }
                        this.$store.dispatch('setLightBreed', light_breed)
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
        console.log('ac')
        this.init()
    },
    components: {
        
    },
    computed: {
        //从vuex中获取设备数据
        tableData() {
            console.log(this.$store.state.light_breed)
            return this.$store.state.light_breed
        },
        //从vuex中获取设备数据条数
        dataCount() {
            return this.$store.state.light_breed.length
        }
    },
    mixins: [http]
}
</script>