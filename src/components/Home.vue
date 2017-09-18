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
	overflow-y: auto;
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
		getAddress() {
			const data = {
				params: {
					action: "search"
				}
			}
			this.apiGet("device/address.php", data).then(res => {
				this.$store.dispatch('setAddress', res)
			});
		},
		getFloor() {
			const data = {
				params: {
					action: "search"
				}
			}
			this.apiGet("device/floor.php", data).then(res => {
				this.$store.dispatch('setFloor', res)
			});
		},
		getRoom() {
			const data = {
				params: {
					action: "search"
				}
			}
			this.apiGet("device/room.php", data).then(res => {
				this.$store.dispatch('setRoom', res)
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
				var newRecords = []
				var ac_breeds = this.$store.state.ac_breed
				var light_breeds = this.$store.state.light_breed
				var led_breeds = this.$store.state.led_breed
				for (var record of records) {
					if (record.on_off == "on") {
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
						if(record.watts){
							newRecords.push(record)
						}
						
					}

				}
				this.records = newRecords
				//记录数据处理完成
				//以下是记录数据的使用

				this.$store.dispatch('setRecord', newRecords)
			});
		},
		countryArr(devices) {
			var countryArr = []
			var countryList = []
			//this.devices原始设备数据
			for (var item of devices) {
				//筛选重复国家
				if (countryList.indexOf(item.country) == -1) {
					countryList.push(item.country);
					var mapIportCountryObject = {}
					mapIportCountryObject.name = item.country
					mapIportCountryObject.selected = true
					mapIportCountryObject.addressList = []
					mapIportCountryObject.addressArr = []
					mapIportCountryObject.deviceTypeNumber = {}
					// mapIportCountryObject.deviceList = {}
					countryArr.push(mapIportCountryObject)
				}
				for (var country of countryArr) {
					//筛选重复类型
					if (item.country == country.name) {
						if (country.addressArr.indexOf(item.address) == -1) {
							country.addressArr.push(item.address)
							var addressObject = {}

							addressObject.name = item.address
							addressObject.id = item.addressid
							addressObject.country = item.country
							addressObject.address = item.address
							addressObject.lat = item.lat
							addressObject.lng = item.lng
							addressObject.ip = item.ip
							addressObject.port = item.port
							addressObject.mac = item.mac
							addressObject.floorList = []
							addressObject.floorArr = []
							// addressObject.typeList = []
							// addressObject.typeArr = []
							addressObject.deviceTypeNumber = {}
							country.addressList.push(addressObject)
						}
						//计算各种设备类型的数量
						country.deviceTypeNumber[item.devicetype] ? country.deviceTypeNumber[item.devicetype] += 1 : country.deviceTypeNumber[item.devicetype] = 1
						for (var address of country.addressList) {
							//筛选重复类型
							if (item.address == address.name) {
								if (address.floorArr.indexOf(item.floor) == -1) {
									address.floorArr.push(item.floor)
									var floorObject = {}
									floorObject.name = item.floor
									floorObject.roomList = []
									floorObject.roomArr = []
									floorObject.deviceTypeNumber = {}
									address.floorList.push(floorObject)
								}

								address.deviceTypeNumber[item.devicetype] ? address.deviceTypeNumber[item.devicetype] += 1 : address.deviceTypeNumber[item.devicetype] = 1
								for (var floor of address.floorList) {
									//筛选重复类型
									if (item.floor == floor.name) {
										if (floor.roomArr.indexOf(item.floor) == -1) {
											floor.roomArr.push(item.floor)
											var roomObject = {}
											roomObject.name = item.room
											roomObject.typeList = []
											roomObject.typeArr = []
											roomObject.deviceTypeNumber = {}
											floor.roomList.push(roomObject)
										}
										floor.deviceTypeNumber[item.devicetype] ? floor.deviceTypeNumber[item.devicetype] += 1 : floor.deviceTypeNumber[item.devicetype] = 1
										for (var room of floor.roomList) {
											if (item.room == room.name) {
												if (room.typeArr.indexOf(item.devicetype) == -1) {
													room.typeArr.push(item.devicetype)
													var typeObject = {}
													typeObject.name = item.devicetype
													typeObject.deviceList = []
													room.typeList.push(typeObject)
												}
												room.deviceTypeNumber[item.devicetype] ? room.deviceTypeNumber[item.devicetype] += 1 : room.deviceTypeNumber[item.devicetype] = 1
												for (var type of room.typeList) {
													//筛选重复类型
													if (item.devicetype == type.name) {
														type.deviceList.push(item)
													}
												}
											}
										}
									}

								}

							}
						}
					}
				}
			}
			console.log(countryArr)
			this.$store.dispatch('setCountryArr', countryArr)

			// return countryArr
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
			var devices = []
			var maxid  = res[0].maxid
			this.$store.dispatch('setMaxid', maxid)
			for (var device of res) {
				if (device.status == 'enabled') {
					devices.push(device)
				}
			}
			// this.devices = devices
			this.countryArr(devices)

		});
		this.getAcBreed()
		this.getLightBreed()
		this.getLedBreed()
		this.getAddress()
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
