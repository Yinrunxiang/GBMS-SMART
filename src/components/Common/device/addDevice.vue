<template>
    <div class="m-l-50 m-t-30 w-500">
        <el-form ref="form" :model="form" label-width="110px">
            <el-form-item label="Device Name">
                <el-input v-model.trim="form.device" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Device Type">
                <el-select v-model="form.devicetype" filterable placeholder="Select Type" class="h-40 w-200">
                    <el-option v-for="item in deviceTypeOptions" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Breed Type">
                <el-select v-model="form.breed" filterable placeholder="Select Breed" class="h-40 w-200">
                    <el-option v-for="item in breedData" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Subnet ID">
                <el-input v-model.trim="form.subnetid" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Device ID">
                <el-input v-model.trim="form.deviceid" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Channel">
                <el-input v-model.trim="form.channel" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Address">
                <el-select v-model="form.address" filterable placeholder="Select Address" class="h-40 w-200">
                    <el-option v-for="item in address" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="add('form')" :loading="isLoading">Commit</el-button>
                <el-button @click="goback()">Back</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import http from '../../../assets/js/http'
import fomrMixin from '../../../assets/js/form_com'

export default {
    data() {
        return {
            isLoading: false,
            form: {
                device: '',
                devicetype: 'ac',
                subnetid: '',
                deviceid: '',
                channel: '',
                country: '',
                address: '',
                breed: '',
                ip: '',
                port: '',
                mac: '',
                status: 'enabled',
                on_off: false,
                starttime: '',
                endtime: '',

            },

            deviceTypeOptions: [
                { label: 'AC', value: 'ac' },
                { label: 'Light', value: 'light' },
                { label: 'LED', value: 'led' },
            ],
        }
    },
    methods: {
        add(form) {
            console.log(this.form)
            this.isLoading = !this.isLoading
            const data = {
                params: this.form
            }
            this.apiGet('device/index.php?action=insert', data).then((res) => {
                // _g.clearVuex('setRules')
                if (res[0]) {
                    var devices = this.$store.state.devices
                    devices.push(this.form)
                    this.$store.dispatch('setDevices', devices)
                    _g.toastMsg('success', res[1])
                    setTimeout(() => {
                        this.goback()
                    }, 500)
                } else {
                    _g.toastMsg('error', res[1])
                }

            })
        },
        getBreedList(breeds) {
            var breedArr = []
            for (var item of breeds) {
                var breedObj = {}
                breedObj.label = item.breed
                breedObj.value = item.breed
                breedArr.push(breedObj)
            }
            return breedArr
        },
    },
    created() {
        console.log("plan add")
    },
    mounted() {
    },
    components: {
    },
    computed: {
        breedData() {
            switch (this.form.devicetype) {
                case "ac":
                    var breeds = this.getBreedList(this.$store.state.ac_breed)
                    return breeds
                    break
                case "light":
                    // return this.$store.state.light_breed
                    var breeds = this.getBreedList(this.$store.state.light_breed)
                    return breeds
                    break
                case "led":
                    // return this.$store.state.led_breed
                    var breeds = this.getBreedList(this.$store.state.led_breed)
                    return breeds
                    break
            }
        },
        address() {
            var address = []
            for (var item of this.$store.state.address) {
                if (item.status == 'enabled') {
                    var addressObj = {}
                    addressObj.label = item.address
                    addressObj.value = item.address
                    addressObj.address = item
                    address.push(addressObj)
                }
            }
            return address
        }
    },
    mixins: [http, fomrMixin]
}
</script>