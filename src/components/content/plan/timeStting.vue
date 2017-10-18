<template>
    <el-dialog class="time-setting" ref="dialog" :visible="open" :before-close="handleClose" custom-class="w-500" title="Time Contral Center">
        <div>
            <div class="day-item" w-100p>
                <day :day="'Sun'" :open="runtime.sun.status" @change="dayChange"></day>
                <day :day="'Mon'" :open="runtime.mon.status" @change="dayChange"></day>
                <day :day="'Tues'" :open="runtime.tues.status" @change="dayChange"></day>
                <day :day="'Wed'" :open="runtime.web.status" @change="dayChange"></day>
                <day :day="'Thur'" :open="runtime.thur.status" @change="dayChange"></day>
                <day :day="'Fri'" :open="runtime.fri.status" @change="dayChange"></day>
                <day :day="'Sat'" :open="runtime.sat.status" @change="dayChange"></day>
            </div>
            <div class="m-t-15">
                <el-time-select class="m-l-20" placeholder="Start Time" @change="startTimeChange()" :picker-options="{
                                                      start: '00:00',
                                                      step: '00:10',
                                                      end: '24:00'
                                                    }">
                </el-time-select>
                <el-time-select class="m-l-20" placeholder="End Time" @change="endTimeChange()" :picker-options="{
                                              start: '00:00',
                                              step: '00:10',
                                              end: '24:00',
                                             
                                            }">
                </el-time-select>
            </div>
        </div>
        <div class="p-t-20 p-b-20">
            <el-button type="primary" class="fl " :disabled="disable" @click="submit()">Submit</el-button>
        </div>
    </el-dialog>
</template>
<style>
.time-setting .day-item {
    height: 35px;
    background-color: #eef1f6;
}

.time-setting .day {
    display: inline-block;
    padding: 0 6px;
    width: 50px;
    height: 35px;
    line-height: 30px;
    text-align: center;
}

.time-setting .day.active {
    color: #20a0ff;
    border-bottom: 3px solid #20a0ff;
}
</style>
<script>
import day from './day'
import http from '../../../assets/js/http'

export default {
    data() {
        return {
            disable: false,
            myopen: this.open,
            runtime: {},
            form: {
                old_pwd: '',
                new_pwd: ''
            },
            rules: {
                old_pwd: [
                    { required: true, message: 'Please input the old password', trigger: 'blur' },
                    { min: 3, max: 12, message: 'The length is 3 to 12 characters', trigger: 'blur' }
                ],
                new_pwd: [
                    { required: true, message: 'Please input the new password', trigger: 'blur' },
                    { min: 3, max: 12, message: 'The length is 3 to 12 characters', trigger: 'blur' }
                ]
            }
        }
    },
    methods: {
        // open() {
        //   console.log(this.$refs.dialog)
        //   this.$refs.dialog.open()
        // },
        // close() {
        //   this.$refs.dialog.close()
        // },
        dayChange(val) {

        },
        startTimeChange() { },
        endTimeChange() { },
        handleClose() {
            this.myopen = false
        },
        submit() {

            this.$refs.form.validate((pass) => {
                if (pass) {

                    this.disable = !this.disable
                    var password = Lockr.get('password')
                    if (this.form.old_pwd != password) {
                        _g.toastMsg('error', 'Password is not correct.')
                        return
                    }
                    this.form.username = Lockr.get('username')
                    const data = {
                        params: this.form
                    }
                    this.apiGet('account/account.php?action=change', data).then((res) => {
                        if (res[0]) {
                            _g.toastMsg('success', 'Change success')
                            Lockr.rm('password')
                            setTimeout(() => {
                                router.replace('/')
                            }, 1500)
                        }
                        else {
                            _g.toastMsg('error', 'Change failed')
                            this.disable = !this.disable
                        }
                    })
                }
            })
        },
        getRunTime() {
            const data = {
                params: {
                    action: "getRunTime",
                    device_id: this.device.id
                }
            }
            this.apiGet("device/index.php", data).then(res => {
                var data = res[0]
                var runtime = {
                    sun: {
                        up: data.sun_up,
                        down: data.sun_down,
                        status:data.sun_status,
                    },
                    mon: {
                        up: data.mon_up,
                        down: data.mon_down,
                        status:data.mon_status,
                    },
                    tues: {
                        up: data.tues_up,
                        down: data.tues_down,
                        status:data.tues_status,
                    },
                    web: {
                        up: data.web_up,
                        down: data.web_down,
                        status:data.web_status,
                    },
                    thur: {
                        up: data.thur_up,
                        down: data.thur_down,
                        status:data.thur_status,
                    },
                    fri: {
                        up: data.fri_up,
                        down: data.fri_down,
                        status:data.fri_status,
                    },
                    sat: {
                        up: data.sat_up,
                        down: data.sat_down,
                        status:data.sat_status,
                    },
                }
                console.log(runtime)
                this.runtime = runtime

            });
        },
    },
    created() {
        console.log('changePwd')
        this.getRunTime()

        // this.form.auth_key = Lockr.get('authKey')
    },
    props: ['open', 'device'],
    watch: {
        open(val) {
            this.myopen = val;
        },
        myopen(val) {
            this.$emit("change", val);
        }
    },
    components: {
        day,
    },
    mixins: [http]
}
</script>