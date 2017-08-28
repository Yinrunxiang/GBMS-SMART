<template>
	<el-row class="panel m-w-1280">
		<el-col :span="24" class="panel-top">
			<el-col :span="4">
				<template v-if="logo_type == '1'">
					<!-- <img :src="img" class="logo"> -->
				</template>
				<template v-else>
					<span class="p-l-20">SMART GBMS</span>
				</template>
			</el-col>
			<!-- <el-col :span="4" :offset="16" class="pos-rel">
																		<el-dropdown @command="handleMenu" class="user-menu">
																      <span class="el-dropdown-link c-gra" style="cursor: default">
																        Admin&nbsp;&nbsp;<i class="fa fa-user" aria-hidden="true"></i>
																      </span>
																      <el-dropdown-menu slot="dropdown">
																        <el-dropdown-item command="changePwd">修改密码</el-dropdown-item>
																        <el-dropdown-item command="logout">退出</el-dropdown-item>
																      </el-dropdown-menu>
																    </el-dropdown>
																	</el-col> -->
		</el-col>
		<el-col :span="24" class="panel-center">
			<!--<el-col :span="4">-->
			<aside class="w-180 ovf-hd">
				<leftMenu :menuData="menuData" ref="leftMenu"></leftMenu>
			</aside>
			<section class="panel-c-c" :class="{'hide-leftMenu': hasChildMenu}">
				<div class="grid-content bg-purple-light">
					<el-col :span="24">
						<transition name="fade" mode="out-in" appear>
							<router-view></router-view>
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

.panel {
	position: absolute;
	top: 0px;
	bottom: 0px;
	padding: 0;
	width: 100%;
}

.panel-top {
	height: 60px;
	line-height: 60px;
	background: #1F2D3D;
	color: #c0ccda;
}

.panel-center {
	background: #324057;
	position: absolute;
	top: 60px;
	bottom: 0px;
	overflow: hidden;
}

.panel-c-c {
	background: #f1f2f7;
	position: absolute;
	right: 0px;
	top: 0px;
	bottom: 0px;
	left: 180px;
	overflow-y: scroll;
	padding: 0px;
}

.logout {
	background: url(../assets/images/logout_36.png);
	background-size: contain;
	width: 20px;
	height: 20px;
	float: left;
}

.logo {
	width: 150px;
	float: left;
	margin: 10px 10px 10px 18px;
}

.tip-logout {
	float: right;
	margin-right: 20px;
	padding-top: 5px;
	cursor: pointer;
}

.admin {
	color: #c0ccda;
	text-align: center;
}

.hide-leftMenu {
	left: 0px;
}
</style>
<script>
import leftMenu from './Common/leftMenu.vue'
// import changePwd from './Account/changePwd.vue'
import http from '../assets/js/http'

export default {
	data() {
		return {
			username: '',
			topMenu: [],
			childMenu: [],
			menuData: [
				{
					title: 'Global',
					url: '/home/global',
					name: 'global'
				},
				{
					title: 'Contral',
					url: '/home/contral',
					name: 'contral'
				},
				{
					title: 'plan',
					url: '/home/plan',
					name: 'plan'
				},
				{
					title: 'report',
					url: '/home/report',
					name: 'report'
				}
			],
			hasChildMenu: false,
			menu: null,
			module: null,
			img: '',
			title: '',
			logo_type: null,
			records: [],
		}
	},
	methods: {
		handleMenu(val) {
			switch (val) {
				case 'logout':
					this.logout()
					break
				case 'changePwd':
					this.changePwd()
					break
			}
		},
		getAcBreed() {
			const data = {
				params: {
					action: "search"
				}
			}
			this.apiGet("device/ac_breed.php", data).then(res => {
				this.$store.dispatch('setAcBreed', res)
			});
		},
		getLightBreed() {
			const data = {
				params: {
					action: "search"
				}
			}
			this.apiGet("device/light_breed.php", data).then(res => {
				this.$store.dispatch('setLightBreed', res)
			});
		},
		getLedBreed() {
			const data = {
				params: {
					action: "search"
				}
			}
			this.apiGet("device/led_breed.php", data).then(res => {
				this.$store.dispatch('setLedBreed', res)
			});
		},
		getRecord() {
			const data = {
				params: {
					action: "getrecord"
				}
			}
			this.apiGet("device/index.php", data).then(res => {
				var records = res
				var ac_breeds = this.$store.state.ac_breed
				var light_breeds = this.$store.state.light_breed
				var led_breeds = this.$store.state.led_breed
				for (var record of records) {
					if (record.on_off) {
						switch (record.devicetype) {
							case "ac":
								for (var ac_breed of ac_breeds) {
									if (record.breed == ac_breed.breed) {
										record.watts = parseInt(ac_breed[record.mode]) + parseInt(ac_breed[record.grade])
									}
								}
								break
							case "light":
								for (var light_breed of light_breeds) {
									if (record.breed == light_breed.breed) {
										record.watts = parseInt(light_breed.watts)
									}
								}
								break
							case "led":
								for (var led_breed of led_breeds) {
									if (record.breed == led_breed.breed) {
										record.watts = parseInt(led_breed.watts)
									}
								}
								break
						}
					}

				}
				console.log(records)
				this.records = records
				//记录数据处理完成
				//以下是记录数据的使用
				
				this.$store.dispatch('setRecord', records)
			});
		},
	},
	created() {
		
	},
	mounted() {
		const data = {
			params: {
				action: "search"
			}
		}
		this.apiGet("device/index.php", data).then(res => {
			this.$store.dispatch('setDevices', res)
		});
		this.getAcBreed()
		this.getLightBreed()
		this.getLedBreed()
		this.getRecord()
	},
	components: {
		leftMenu
	},
	computed: {
		devices() {
			var devices = []
			for (var device of this.$store.state.devices) {
				if (device.status == 'enabled') {
					devices.push(device)
				}
			}
			return devices
		},
	},
	mixins: [http]
}
</script>
