<template>
    <div class="m-l-50 m-t-30 w-500">
        <el-form ref="form" :model="form" label-width="150px">
            <el-form-item label="Mode Name">
                <el-input v-model.trim="form.breed" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Auto Watts">
                <el-input v-model.trim="form.mode_auto" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Fan Watts">
                <el-input v-model.trim="form.fan" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Cool Watts">
                <el-input v-model.trim="form.cool" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Heat Watts">
                <el-input v-model.trim="form.heat" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Auto Wind Watts">
                <el-input v-model.trim="form.wind_auto" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Low Wind Watts">
                <el-input v-model.trim="form.low" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Medium Wind Watts">
                <el-input v-model="form.medium" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="High Wind Watts">
                <el-input v-model="form.high" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Run Time(h)">
                <el-input v-model="form.run_time" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="add('form')" :loading="isLoading">Save</el-button>
                <el-button @click="goback()">Cancel</el-button>
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
                breed: '',
                mode_atuo: '',
                fan: '',
                cool: '',
                heat: '',
                wind_auto: '',
                low: '',
                medium: '',
                high: '',
                run_time:0,
                status: 'enabled',
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
            this.apiGet('device/ac_breed.php?action=insert', data).then((res) => {
                // _g.clearVuex('setRules')
                if (res[0] == true) {
                    var ac_breed = this.$store.state.ac_breed
                    ac_breed.push(this.form)
                    // this.$store.dispatch('setAcBreed', ac_breed)
                    _g.toastMsg('success', res[1])
                    setTimeout(() => {
                        this.goback()
                    }, 500)
                } else {
                    _g.toastMsg('error', res[1])
                }
                this.isLoading = false
            })
        },
    },
    created() {
        console.log('ac_breed add')
    },
    mounted() {
    },
    components: {
    },
    mixins: [http,fomrMixin]
}
</script>