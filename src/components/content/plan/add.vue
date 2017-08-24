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
            <el-form-item label="Subnet ID">
                <el-input v-model.trim="form.subnetid" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Device ID">
                <el-input v-model.trim="form.deviceid" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Channel">
                <el-input v-model.trim="form.channel" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Country">
                <el-select v-model="form.country" filterable placeholder="Select Address" class="h-40 w-200">
                    <el-option v-for="item in addressOptions" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Address">
                <el-input v-model="form.address" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="IP Address">
                <el-input v-model="form.ip" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Port">
                <el-input v-model="form.port" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Mac Address">
                <el-input v-model="form.mac" class="h-40 w-200"></el-input>
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
                devicetype: '',
                subnetid: '',
                deviceid: '',
                channel: '',
                country: '',
                address: '',
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
            addressOptions: [],
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
        getAddressOptions() {
            var option = [
                ["AO", "Angola"],
                ["AF", "Afghanistan"],
                ["AL", "Albania"],
                ["DZ", "Algeria"],
                ["AD", "Andorra"],
                ["AI", "Anguilla"],
                ["AG", "Barbuda Antigua"],
                ["AR", "Argentina"],
                ["AM", "Armenia"],
                ["AU", "Australia"],
                ["AT", "Austria"],
                ["AZ", "Azerbaijan"],
                ["BS", "Bahamas"],
                ["BH", "Bahrain"],
                ["BD", "Bangladesh"],
                ["BB", "Barbados"],
                ["BY", "Belarus"],
                ["BE", "Belgium"],
                ["BZ", "Belize"],
                ["BJ", "Benin"],
                ["BM", "Bermuda Is."],
                ["BO", "Bolivia"],
                ["BW", "Botswana"],
                ["BR", "Brazil"],
                ["BN", "Brunei"],
                ["BG", "Bulgaria"],
                ["BF", "Burkina-faso"],
                ["MM", "Burma"],
                ["BI", "Burundi"],
                ["CM", "Cameroon"],
                ["CA", "Canada"],
                ["CF", "Central African Republic"],
                ["TD", "Chad"],
                ["CL", "Chile"],
                ["CN", "China"],
                ["CO", "Colombia"],
                ["CG", "Congo"],
                ["CK", "Cook Is."],
                ["CR", "Costa Rica"],
                ["CU", "Cuba"],
                ["CY", "Cyprus"],
                ["CZ", "Czech Republic"],
                ["DK", "Denmark"],
                ["DJ", "Djibouti"],
                ["DO", "Dominica Rep."],
                ["EC", "Ecuador"],
                ["EG", "Egypt"],
                ["SV", "EI Salvador"],
                ["EE", "Estonia"],
                ["ET", "Ethiopia"],
                ["FJ", "Fiji"],
                ["FI", "Finland"],
                ["FR", "France"],
                ["GF", "French Guiana"],
                ["GA", "Gabon"],
                ["GM", "Gambia"],
                ["GE", "Georgia"],
                ["DE", "Germany"],
                ["GH", "Ghana"],
                ["GI", "Gibraltar"],
                ["GR", "Greece"],
                ["GD", "Grenada"],
                ["GU", "Guam"],
                ["GT", "Guatemala"],
                ["GN", "Guinea"],
                ["GY", "Guyana"],
                ["HT", "Haiti"],
                ["HN", "Honduras"],
                ["HK", "Hongkong"],
                ["HU", "Hungary"],
                ["IS", "Iceland"],
                ["IN", "India"],
                ["ID", "Indonesia"],
                ["IR", "Iran"],
                ["IQ", "Iraq"],
                ["IE", "Ireland"],
                ["IL", "Israel"],
                ["IT", "Italy"],
                ["JM", "Jamaica"],
                ["JP", "Japan"],
                ["JO", "Jordan"],
                ["KH", "Kampuchea (Cambodia )"],
                ["KZ", "Kazakstan"],
                ["KE", "Kenya"],
                ["KR", "Korea"],
                ["KW", "Kuwait"],
                ["KG", "Kyrgyzstan"],
                ["LA", "Laos"],
                ["LV", "Latvia"],
                ["LB", "Lebanon"],
                ["LS", "Lesotho"],
                ["LR", "Liberia"],
                ["LY", "Libya"],
                ["LI", "Liechtenstein"],
                ["LT", "Lithuania"],
                ["LU", "Luxembourg"],
                ["MO", "Macao"],
                ["MG", "Madagascar"],
                ["MW", "Malawi"],
                ["MY", "Malaysia"],
                ["MV", "Maldives"],
                ["ML", "Mali"],
                ["MT", "Malta"],
                ["MU", "Mauritius"],
                ["MX", "Mexico"],
                ["MD", "Moldova"],
                ["MC", "Monaco"],
                ["MN", "Mongolia"],
                ["MS", "Montserrat Is."],
                ["MA", "Morocco"],
                ["MZ", "Mozambique"],
                ["NA", "Namibia"],
                ["NR", "Nauru"],
                ["NP", "Nepal"],
                ["NL", "Netherlands"],
                ["NZ", "New Zealand"],
                ["NI", "Nicaragua"],
                ["NE", "Niger"],
                ["NG", "Nigeria"],
                ["KP", "North Korea"],
                ["NO", "Norway"],
                ["OM", "Oman"],
                ["PK", "Pakistan"],
                ["PA", "Panama"],
                ["PG", "Papua New Cuinea"],
                ["PY", "Paraguay"],
                ["PE", "Peru"],
                ["PH", "Philippines"],
                ["PL", "Poland"],
                ["PF", "French Polynesia"],
                ["PT", "Portugal"],
                ["PR", "Puerto Rico"],
                ["QA", "Qatar"],
                ["RO", "Romania"],
                ["RU", "Russia"],
                ["LC", "Saint Lueia"],
                ["VC", "Saint Vincent"],
                ["SM", "San Marino"],
                ["ST", "Sao Tome and Principe"],
                ["SA", "Saudi Arabia"],
                ["SN", "Senegal"],
                ["SC", "Seychelles"],
                ["SL", "Sierra Leone"],
                ["SG", "Singapore"],
                ["SK", "Slovakia"],
                ["SI", "Slovenia"],
                ["SB", "Solomon Is."],
                ["SO", "Somali"],
                ["ZA", "South Africa"],
                ["ES", "Spain"],
                ["LK", "Sri Lanka"],
                ["SD", "Sudan"],
                ["SR", "Suriname"],
                ["SZ", "Swaziland"],
                ["SE", "Sweden"],
                ["CH", "Switzerland"],
                ["SY", "Syria"],
                ["TW", "Taiwan"],
                ["TJ", "Tajikstan"],
                ["TZ", "Tanzania"],
                ["TH", "Thailand"],
                ["TG", "Togo"],
                ["TO", "Tonga"],
                ["TT", "Trinidad and Tobago"],
                ["TN", "Tunisia"],
                ["TR", "Turkey"],
                ["TM", "Turkmenistan"],
                ["UG", "Uganda"],
                ["UA", "Ukraine"],
                ["AE", "United Arab Emirates"],
                ["GB", "United Kiongdom"],
                ["US", "United States of America"],
                ["UY", "Uruguay"],
                ["UZ", "Uzbekistan"],
                ["VE", "Venezuela"],
                ["VN", "Vietnam"],
                ["YE", "Yemen"],
                ["YU", "Yugoslavia"],
                ["ZW", "Zimbabwe"],
                ["ZR", "Zaire"],
                ["ZM", "Zambia"]
            ];
            var address = []
            for (var item of option) {
                var addressObj = {}
                addressObj.label = item[1]
                addressObj.value = item[1]
                address.push(addressObj)
            }
            return address
        }
    },
    created() {
        
    },
    mounted() {
        this.addressOptions = this.getAddressOptions()
    },
    components: {
    },
    mixins: [http, fomrMixin]
}
</script>