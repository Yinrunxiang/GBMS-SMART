<template>
    <div class="right-page-content light security">
        <!-- <div>{{device.device}}</div> -->
        <div>
            <div style="margin-bottom:15px;">
            <el-dropdown>
                <el-button  style="width:120px;" ><span>{{area}}</span>
                    <i  class="el-icon-arrow-down el-icon--right fr"></i>
                </el-button>
                <el-dropdown-menu slot="dropdown" style="width:120px;">
                    <el-dropdown-item @click="areaClick(area.key)" v-for="area in areaList">{{area.name}}</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
            </div>
            <div>
            <el-button class="security-btn"  @click="sendCommand('02')">Away</el-button>
            <el-button class="security-btn"  @click="sendCommand('03')">Night</el-button>
            </div>
            <div>
            <el-button class="security-btn"  @click="sendCommand('04')">Night-G</el-button>
            <el-button class="security-btn"  @click="sendCommand('05')">Day</el-button>
            </div>
            <div>
            <el-button class="security-btn"  @click="sendCommand('01')">Vacation</el-button>
            <el-button class="security-btn"  @click="sendCommand('06')">Disarm</el-button>
            </div>
            <div>
            <el-button class="security-btn"  @click="sendCommand('04')">Police Panic</el-button>
            <el-button class="security-btn"  @click="sendCommand('08')">Ambulance</el-button>
            </div>
        </div>
        <div>

        </div>
    </div>
</template>
<style>
.right-page-content {
  margin: 0 10px;
  text-align: center;
  width: 300px;
  height: 100%;
  color:#999;
}
.right-page-content .icon {
  display: inline-block;
  width: 200px;
  height: 200px;
  font-size: 200px;
  color: #c0ccda;
  text-align: center;
}
.security-btn {
  width: 120px;
  margin: 5px 15px;
}
</style>
<script>
import securityApi from "../devices/security/security.js";
export default {
  data() {
    return {
      area: "01",
      areaList: [
        {
          name: "01",
          key: "01"
        },
        {
          name: "02",
          key: "02"
        },
        {
          name: "03",
          key: "03"
        },
        {
          name: "04",
          key: "04"
        },
        {
          name: "05",
          key: "05"
        },
        {
          name: "06",
          key: "06"
        },
        {
          name: "07",
          key: "07"
        },
        {
          name: "08",
          key: "08"
        }
      ]
    };
  },
  // props: ['device'],
  methods: {
    areaClick(val) {
        this.area = val
    },
    sendCommand(type) {
      var val = {};
      val.area = this.area
      val.type = type;
      securityApi.send_command(val, this.device);
    }
  },
  mounted() {
    console.log("security");
  },
  components: {},
  computed: {
    device() {
      var device = this.$store.state.device;
      return device;
    }
  }
};
</script>