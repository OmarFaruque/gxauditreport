<template>
    <div>
        <el-container v-loading="loading" v-if="usear_login && gx_display_message == ''">
            <el-header class="mt-3">
                <el-row type="flex" justify="center">
                    <div class="p-img">
                        <el-image v-if="client_inf[0]" class="mw-100"
                        :src="client_inf[0].logourl"
                        fit="fill"></el-image>
                        </div>
                </el-row>
            </el-header>
            <el-main class="mt-1">
                <el-row class="mb-3" :gutter="20" >
                    <el-col :span="12" :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                                <h2 class="mt-3 card-title">Information:</h2>
                                <el-card class="border-0 pt-0 br-10px p-30px">
                                    <ul class="m-0 p-0 lists-style-none information-list">
                                        <li><strong>Client: </strong>{{ client_inf[0] ? client_inf[0].name : '' }}</li>
                                        <li><strong>Location: </strong>{{ client_inf[0] ? client_inf[0].location : '' }}</li>
                                        <li><strong>ID: </strong>{{ client_inf[0] ? client_inf[0].gx_id : '' }}</li>
                                        <li><strong>Type: </strong>{{ client_inf[0] ? client_inf[0].type : '' }}</li>
                                        <li><strong>Amount of Onsite Staff: </strong>{{ client_inf[0] ? client_inf[0].staff : '' }}</li>
                                        <li><strong>Total Number of Touch Points: </strong>{{ client_inf[0] ? client_inf[0].touch_points : '' }}</li>
                                        <li><strong>Total Number of Sectors: </strong>{{ client_inf[0] ? client_inf[0].sector_number : '' }}</li>
                                    </ul>
                                </el-card>
                         
                    </el-col>
                    <el-col :xs="24" :sm="24" :md="24" :lg="12" :span="12" :xl="12">
                        <h2 class="mt-3 card-title">Guest Experience Score(GXS):</h2>
                       <el-card class="br-10px p-30px border-0">
                        <el-row :gutter="20">
                            <el-col :xs="24" :span="12" class="mt-xs-1">
                                <div class="grid-content">
                                    <el-card shadow="never" class="border-0 color-white score-card p-30px">
                                        <el-row type="flex" justify="space-between" class="score-head mb-10px">
                                            
                                            <span v-if="client_inf[0] && client_inf[0].date" class="mt-0 color3 audit-date"><strong>{{ client_inf[0].date | moment("DD/MM/YYYY") }}</strong></span>
                                            <span v-else class="mt-0 color3 audit-date" style="color:transparent;"><strong>00/00/0000</strong></span>
                                            <span v-if="different.serial == 1" class="color-green audit-point">+{{ different.diff }}</span>
                                            <span v-if="different_minus.serial == 1" class="color-red audit-point">{{ different_minus.diff }}</span>
                                        </el-row>
                                        
                                        <h3 class="mt-0 mb-0 score color1">{{ average1 }}</h3>
                                        
                                    </el-card>
                                    <el-select @change="dateChangeEvent($event)" class="w-100 mt-1 audit1 audit border-0" v-model="start_date" placeholder="Select a Date">
                                        <el-option
                                            v-for="date in available_dates"
                                            :key="date.value"
                                            :label="date.label"
                                            :value="date.value">
                                        </el-option>
                                    </el-select>
                                </div>
                            </el-col>
                            <el-col :xs="24" :span="12" class="mt-xs-2">
                                <el-card shadow="never" class="border-0 color-white score-card p-30px">
                                    <el-row type="flex" justify="space-between" class="score-head mb-10px">
                                        <span v-if="client_inf[1] && client_inf[1].date" class="mt-0 color3 audit-date"><strong>{{  client_inf[1].date | moment("DD/MM/YYYY") }}</strong></span>
                                        <span v-else class="mt-0 color3 audit-date" style="color:transparent;"><strong>00/00/0000</strong></span>
                                        <span v-if="different.serial == 2" class="color-green audit-point"> +{{ different.diff }}</span>
                                        <span v-if="different_minus.serial == 2" class="color-red audit-point"> {{ different_minus.diff }}</span>
                                    </el-row>
                                    <h3 class="mt-0 mb-0 score color2">{{ average2 }}</h3>
                                </el-card>
                                <el-select @change="dateChangeEvent($event)" class="w-100 mt-1 audit2 audit" v-model="end_date" placeholder="Select a Date">
                                    <el-option
                                        v-for="date in available_dates"
                                        :key="date.value"
                                        :label="date.label"
                                        :value="date.value">
                                    </el-option>
                                </el-select>
                            </el-col>
                        </el-row>
                       </el-card>
                    </el-col>
                </el-row>
                <el-row class="mt-3">
                        <div class="chartWrap">
                            <h2 class="mb-1 card-title"><strong>Guest Journey:</strong></h2>
                            <el-card class="br-10px p-30px border-0">
                                
                                <div id="chart">
                                    <apexchart v-if="chartOptions.xaxis.categories.length" type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                                    <h5 class="text-center" v-else>No data are available for display</h5>
                                </div>
                            </el-card>
                        </div>
                    
                </el-row>
                <el-row  class="mt-3">
                        <h2 class="mb-1 card-title"><strong>Social Reviews:</strong></h2>
                        <el-card class="br-10px plr-30px ptb-20px border-0">
                        <div class="socialWrap">
                            <el-row v-if="Object.keys(socials).length" class="item-center mb-1 g-3" :gutter="15">
                                <el-col v-for="(n, index) in socials" :key="index" :xs="12" :sm="12" :md="6" :span="6">
                                    <el-card shadow="never" class="border-0 br-10px p-15px social-card mt-1">
                                        <el-row type="flex" justify="center" class="item-center">
                                            <el-col :span="12">
                                                <el-image v-if="client_inf[0]" class="mw-100 justify-center d-flex"
                                                :src="$publicAssets(`images/${index}.png`)"
                                                fit="fill"></el-image>
                                            </el-col>
                                            <el-col :span="12">
                                                <div class="socialscore">
                                                    <span>{{ n[0] }}</span>
                                                    <span>{{ n[1] }}</span>
                                                </div>
                                            </el-col>
                                        </el-row>
                                        <!-- <h4 class="mt-0 mb-0 score"><strong>{{ client_inf[0] ? client_inf[0].fb_score : 0 }}</strong></h4> -->
                                    </el-card>
                                </el-col>
                            </el-row>
                            <el-row v-else>
                                <h5 class="text-center">No data are available for display</h5>
                            </el-row>
                            
                           
                        </div>
                    </el-card>
                </el-row>
                <el-row class="mt-3">
                        <h2 class="mb-1 card-title"><strong>Reports:</strong></h2>
                        <el-card class="br-10px border-0 reports">
                            <el-row type="flex" v-if="client_inf.length && client_inf[0].excel" class="item-center mb-d-block">
                                <el-col :span="10" :xs="24" class="p-10px">
                                    <strong>DATE:</strong> {{ start_date | moment("Do MMMM, YYYY") }}
                                </el-col>
                                <el-col :span="8" :xs="12" class="p-10px">
                                    <strong v-if="client_inf[0]">ID #:</strong> {{ client_inf[0] ? client_inf[0].gx_id : '' }}
                                </el-col>
                                <el-col :span="6" :xs="12">
                                    <el-row type="flex" justify="end">
                                        <el-button class="d-flex item-center download-btn" @click="downloadExcel()">
                                        <span>Download Reports &nbsp;</span>
                                        <el-image v-if="client_inf[0]" class="justify-center"
                                                :src="$publicAssets(`images/download-icon.png`)"
                                                fit="fill">
                                        </el-image>
                                    </el-button>
                                    </el-row>
                                </el-col>
                            </el-row>
                            <el-row v-else>
                                <h5 class="text-center">No data are available for display</h5>
                            </el-row>
                        </el-card>
                </el-row>
            </el-main>
      </el-container>
      <el-container v-else>
        <el-main>
            <el-row type="flex" justify="center">
                <el-col :span="24">
                    <el-card shadow="always" class="text-center">
                        <h4 v-if="gx_display_message">{{ gx_display_message }}</h4>
                        <h4 v-else>Please log in to view your available data.</h4>
                    </el-card>
                </el-col>
            </el-row>
        </el-main>
      </el-container>
    </div>
</template>
<script>
import FetchWP from '../utils/fetchWP'
import VueApexCharts from 'vue-apexcharts'
export default {
    name: 'ScoreBoard', 
    components: {
          apexchart: VueApexCharts,
    },
    data() {
        return {
            loading: true,
            average1: 0, 
            average2: 0,
            gx_display_message: '',
            socials: {},
            available_dates: [],
            usear_login: window.gx_object.user_id > 0 ? true : false,
            different: {serial:0},
            different_minus: {serial:0},
            series: [{
                name: 'Net Profit',
                data: []
            }, {
                name: 'Revenue',
                data: []
            }],
            chartOptions: {
                chart: {
                    toolbar: {
                        show: false
                    },
                    type: 'bar',
                    height: 450
                },
                legend: {
                    show: false
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 5,
                        endingShape: 'rounded',
                        columnWidth: '70%',
                        borderRadiusApplication: 'end',
                        rangeBarOverlap: true,
                        dataLabels: {
                          position: 'top', // top, center, bottom
                          maxItems: 100,
                        },
                    },
                },
                
                dataLabels: {
                    enabled: false, 
                    formatter: function (val) {
                        return val + "%";
                    },
                    offsetX: 0,
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ['#18186E']
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['#fff']
                },
                xaxis: {
                    categories: [],
                },
                yaxis: {
                    tickAmount: 4,
                    min: 0,
                    max: 100,
                    labels: {
                        minWidth: 0,
                        maxWidth: 100,
                    }
                },
                fill: {
                    opacity: 1, 
                    colors: ['#529CFD', '#18186E']
                },
                tooltip: {
                enabled: false,
                shared: false,
                intersect: false,
                y: {
                    formatter: function (val) {
                            return val
                        }
                    }
                },
                responsive: [
                {
                    breakpoint: 767,
                    options: {
                        plotOptions: {
                            bar: {
                                columnWidth: '70%'
                            }
                        },
                        dataLabels: {
                            formatter: function (val) {
                                return val;
                            },
                            style: {
                                fontSize: '10px',
                            }
                        },
                    }
                }
            ]
            },

          
            client_inf: [{
                name: '',
                location: '',
                gx_id: '',
                type: '',
                staff: 0,
                touch_points: 0,
                sector_number: 0,
                logourl: '',
                logoid: '',
                user_id: '',
                date: '',
                items: []    
            },{}], 
            start_date: '',
            end_date: '',
            fetchWP: new FetchWP({
                    restURL: window.gx_object.root,
                    restNonce: window.gx_object.api_nonce,

            }),
        }
    }, 
    mounted(){
        if(this.usear_login){
            this.getData({id: window.gx_object.user_id})
        }else{
            this.fetchWP.get(`update_settings`)
            .then( (response) => { 
                this.gx_display_message = response.gx_display_message
            })
        }
    }, 
    computed: {
       
    },
    methods: {
        dateChangeEvent(){
            let data = {
                id: window.gx_object.user_id, 
                start_date: this.start_date, 
                end_date: this.end_date
            }
            this.getData(data)
        },
        getData(data){
            this.fetchWP.post(`frontend_config`, data)
        .then((response) => { 
            this.loading = false;
            this.socials = response.socials
            this.available_dates = response.available_dates.length ? response.available_dates : []

                // Show error if data less then 1
                if(response.results.length <= 0){
                    this.gx_display_message = 'No data are available for display';
                }

                if(response.results.length > 0 && response.results[0].date){
                    this.start_date = response.results[0].date
                }
                if(response.results.length > 1 && response.results[1].date){
                    this.end_date = response.results[1].date
                }
                
                let temCat = [], 
                temScore1 = [], 
                temScore2 = [];
                
                response.results.map((v, k) => {
                    response.results[k].items = JSON.parse(v.items)

                    response.results[k].items.map((i, m) => {
                        temCat = [...temCat, ...[i.name]]
                        if(k==0){
                            temScore1 = [...temScore1, ...[i.score]]
                        }else{
                            temScore2 = [...temScore2, ...[i.score]]
                        }
                    })  
                })

                
                this.average1 = temScore1.length ? (temScore1.reduce((a, b) => a + b) / temScore1.length).toFixed(1) : 0;
                this.average2 = temScore2.length > 0 ? (temScore2.reduce((a, b) => a + b) / temScore2.length).toFixed(1) : 0;
                if(+this.average1 > +this.average2){
                    this.different = {serial: 1, diff: (this.average1 - this.average2).toFixed(2)}
                    this.different_minus = {serial: 2, diff: (this.average2 - this.average1).toFixed(2)}
                }else{
                    this.different = {serial: 2, diff: (this.average2 - this.average1).toFixed(2)}
                    this.different_minus = {serial: 1, diff: (this.average1 - this.average2).toFixed(2)}
                }

                this.client_inf = response.results 
                
                let tempSeries = [{name: 'Item 1', data: temScore1},{name: 'Item 2', data: temScore2}];
                
                this.series = tempSeries;
                this.chartOptions = {
                chart: {
                    toolbar: {
                        show: false
                    },
                    type: 'bar',
                    height: 450
                },
                legend: {
                    show: false
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 5,
                        endingShape: 'rounded',
                        columnWidth: '70%',
                        borderRadiusApplication: 'end',
                        rangeBarOverlap: true,
                        dataLabels: {
                          position: 'top', // top, center, bottom
                          maxItems: 100,
                        },
                    },
                },
                
                dataLabels: {
                    enabled: false, 
                    formatter: function (val) {
                        return val + "%";
                    },
                    offsetX: 0,
                    offsetY: -20,
                    style: {
                        fontSize: '10px',
                        colors: ['#18186E']
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['#fff']
                },
                xaxis: {
                    categories: temCat,
                },
                yaxis: {
                    tickAmount: 4,
                    min: 0,
                    max: 100,
                    labels: {
                        minWidth: 0,
                        maxWidth: 100,
                    }
                },
                fill: {
                    opacity: 1, 
                    colors: ['#529CFD', '#18186E']
                },
                tooltip: {
                enabled: false,
                shared: false,
                intersect: false,
                y: {
                    formatter: function (val) {
                            return val
                        }
                    }
                },
                responsive: [
                {
                    breakpoint: 767,
                    options: {
                        plotOptions: {
                            bar: {
                                columnWidth: '70%'
                            }
                        },
                        dataLabels: {
                            formatter: function (val) {
                                return val;
                            },
                            style: {
                                fontSize: '10px',
                            }
                        },
                    }
                }
            ]
            }
        })
        },
        downloadExcel(){
            const link = document.createElement('a')
            link.href = this.client_inf[0].excel
            link.download = `audit_${this.client_inf[0].id}`
            link.click()
        }
    }
}
</script>

