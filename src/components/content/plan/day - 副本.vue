<template>
    <div  class="day time-setting-day" :class="{ 'active': myopen }" @click="dayClick">{{day}}
    </div>
</template>
<style>

.time-setting-day {
    display: inline-block;
    padding: 0 6px;
    width: 50px;
    height: 35px;
    line-height: 30px;
    text-align: center;
}

.time-setting-day.active {
    color: #20a0ff;
    border-bottom: 3px solid #20a0ff;
}
</style>
<script>

export default {
    data() {
        return {
            myopen:this.open
        }
    },
    methods: {
        dayClick() {
            // if(this.myopen == 1){
            //     this.myopen  = 0
            // }else{
            //     this.myopen  = 1
            // }

            this.myopen = !this.myopen
        },
    },
    created() {
    },
    watch: {
        open(val) {
            this.myopen = val;
        },
        myopen(val) {
            var day = this.day.toLowerCase()
            this.$emit("change", day,val);
        }
    },
    props:['day','open'],
}
</script>