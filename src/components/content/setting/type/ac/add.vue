<template>
    <div class="m-l-50 m-t-30 w-500">
        <el-form ref="form" :model="form" label-width="150px">
            <el-form-item label="Mode Name">
                <el-input v-model.trim="form.c_mode" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Auto Watts">
                <el-input v-model.trim="form.c_auto" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Fan Watts">
                <el-input v-model.trim="form.c_fan" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Cool Watts">
                <el-input v-model.trim="form.c_cool" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Heat Watts">
                <el-input v-model.trim="form.c_heat" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Low Wind Watts">
                <el-input v-model.trim="form.c_wind_low" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Medium Wind Watts">
                <el-input v-model="form.c_wind_medium" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="High Wind Watts">
                <el-input v-model="form.c_wind_high" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="add('form')" :loading="isLoading">Commit</el-button>
                <el-button @click="goback()">Back</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import http from '../../../../../assets/js/http'
import fomrMixin from '../../../../../assets/js/form_com'

export default {
    data() {
        return {
            isLoading: false,
            form: {
                c_mode: '',
                c_atuo: '',
                c_fan: '',
                c_cool: '',
                c_heat: '',
                c_wind_low: '',
                c_wind_medium: '',
                c_wind_high: '',
                c_status: 'enabled',
            },
        }
    },
    methods: {
        add(form) {
            console.log(this.form)
            this.isLoading = !this.isLoading
            const data = {
                params: this.form
            }
            this.apiGet('php/ac_mode.php?action=insert', data).then((res) => {
                // _g.clearVuex('setRules')
                if (res[0]) {
                    var ac_mode = this.$store.state.ac_mode
                    ac_mode.push(this.form)
                    this.$store.dispatch('setAcMode', ac_mode)
                    _g.toastMsg('success', res[1])
                    setTimeout(() => {
                        this.goback()
                    }, 500)
                } else {
                    _g.toastMsg('error', res[1])
                }

            })
        },
    },
    created() {
        
    },
    mounted() {
    },
    components: {
    },
    mixins: [http,fomrMixin]
}
</script>