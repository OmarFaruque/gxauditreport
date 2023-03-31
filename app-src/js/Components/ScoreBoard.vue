<template>
    <div>
        <el-container>
            <el-header>
                <el-row>
                    <h2><strong>Guest Experience Score(GXS):</strong></h2>
                </el-row>
            </el-header>
            <el-main class="mt-3">
                <el-row class="mb-3" :gutter="20" >
                    <el-col :span="10">
                        <el-row :gutter="20">
                            <el-col :span="12">
                                <div class="grid-content">
                                    <el-card shadow="never" class="border-0 bg-blue color-white score-card">
                                        <h4 class="mb-1 mt-0"><strong>Audit #:</strong> {{ client_inf[0] ? client_inf[0].id : 0 }}</h4>
                                        <h1 class="mt-0 mb-0 score"><strong>{{ average1 }}</strong></h1>
                                        <h5 v-if="different.serial == 1" class="mt-1 mb-0 color-green"><i class="el-icon-caret-top"></i> +{{ different.diff }}</h5>
                                    </el-card>
                                    <el-date-picker
                                        v-model="start_date"
                                        type="date"
                                        placeholder="Pick a day"
                                        class="w-100 mt-1 max-100"
                                        @change="dateChangeEvent()"
                                        :picker-options="pickerOptions">
                                    </el-date-picker>
                                </div>
                            </el-col>
                            <el-col :span="12">
                                <el-card shadow="never" class="border-0 bg-yellow color-white score-card">
                                        <h4 class="mb-1 mt-0">  <strong >Audit #:</strong> {{ client_inf[1] ? client_inf[1].id : 0 }}</h4>
                                        <h1 class="mt-0 mb-0 score"><strong>{{ average2 }}</strong></h1>
                                        <h5 v-if="different.serial==2" class="mt-1 mb-0 color-green"><i class="el-icon-caret-top"></i> +{{ different.diff }}</h5>
                                    </el-card>
                                    <el-date-picker
                                        v-model="end_date"
                                        type="date"
                                        placeholder="Pick a day"
                                        class="w-100 mt-1 max-100"
                                        @change="dateChangeEvent()"
                                        :picker-options="pickerOptions">
                                    </el-date-picker>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :span="14">
                        <el-row>
                            <el-col :span="12">
                                <el-card shadow="naver" class="border-0 pt-0">
                                    <h4 class="mt-0 mb-1">Information:</h4>
                                    <ul class="m-0 p-0 lists-style-none">
                                        <li>Client: <strong>{{ client_inf[0] ? client_inf[0].name : '' }}</strong></li>
                                        <li>Location: <strong>{{ client_inf[0] ? client_inf[0].location : '' }}</strong></li>
                                        <li>ID: <strong>{{ client_inf[0] ? client_inf[0].gx_id : '' }}</strong></li>
                                        <li>Type: <strong>{{ client_inf[0] ? client_inf[0].type : '' }}</strong></li>
                                        <li>Amount of Onsite Staff: <strong>{{ client_inf[0] ? client_inf[0].staff : '' }}</strong></li>
                                        <li>Total Number of Touch Points: <strong>{{ client_inf[0] ? client_inf[0].touch_points : '' }}</strong></li>
                                        <li>Total Number of Sectors: <strong>{{ client_inf[0] ? client_inf[0].sector_number : '' }}</strong></li>
                                    </ul>
                                </el-card>
                            </el-col>
                            <el-col :span="12">
                                <div class="p-img">
                                    <el-image v-if="client_inf[0]" class="mw-100 border-rounded"
                                    :src="client_inf[0].logourl"
                                    fit="fill"></el-image>
                                </div>
                            </el-col>
                        </el-row>
                    </el-col>
                </el-row>
                <el-row :gutter="0" class="mt-3">
                    <el-col :span="16">
                        <div class="chartWrap">
                            <h3 class="mb-1"><strong>Guest Journey:</strong></h3>
                            <div id="chart">
                                <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                            </div>
                        </div>
                    </el-col>
                    <el-col :span="8">
                        <h3 class="mb-1"><strong>Review Platforms:</strong></h3>
                        <div class="socialWrap">
                            <el-row type="flex" justify="center" class="item-center mb-1" :gutter="20">
                                <el-col :span="8"><div v-bind:style="{ 'background-image': 'url(' + $publicAssets('images/social-logo.png') + ')' }" class="bg-social-img"></div></el-col>
                                <el-col :span="8">
                                    <el-card shadow="never" class="border-0 bg-blue color-white">
                                        <h4 class="mt-0 mb-0 score"><strong>{{ average1 }}</strong></h4>
                                    </el-card>
                                </el-col>
                                <el-col :span="8">
                                    <el-card shadow="never" class="border-0 bg-yellow color-white">
                                        <h4 class="mt-0 mb-0 score"><strong>{{ average2 }}</strong></h4>
                                    </el-card>
                                </el-col>
                            </el-row>
                            <el-row type="flex" justify="center" class="item-center mb-1" :gutter="20">
                                <el-col :span="8"><div v-bind:style="{ 'background-image': 'url(' + $publicAssets('images/social-logo.png') + ')' }" class="bg-social-img"></div></el-col>
                                <el-col :span="8">
                                    <el-card shadow="never" class="border-0 bg-blue color-white">
                                        <h4 class="mt-0 mb-0 score"><strong>{{ average1 }}</strong></h4>
                                    </el-card>
                                </el-col>
                                <el-col :span="8">
                                    <el-card shadow="never" class="border-0 bg-yellow color-white">
                                        <h4 class="mt-0 mb-0 score"><strong>{{ average2 }}</strong></h4>
                                    </el-card>
                                </el-col>
                            </el-row>
                            <el-row type="flex" justify="center" class="item-center" :gutter="20">
                                <el-col :span="8"><div v-bind:style="{ 'background-image': 'url(' + $publicAssets('images/social-logo.png') + ')' }" class="bg-social-img"></div></el-col>
                                <el-col :span="8">
                                    <el-card shadow="never" class="border-0 bg-blue color-white">
                                        <h4 class="mt-0 mb-0 score"><strong>{{ average1 }}</strong></h4>
                                    </el-card>
                                </el-col>
                                <el-col :span="8">
                                    <el-card shadow="never" class="border-0 bg-yellow color-white">
                                        <h4 class="mt-0 mb-0 score"><strong>{{ average2 }}</strong></h4>
                                    </el-card>
                                </el-col>
                            </el-row>
                        </div>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="20">
                        <h4 class="mb-1"><strong>Reports:</strong></h4>
                        <el-card shadow="never" class="bg-blue border-rounded text-white text-uppercase">
                            <el-row>
                                <el-col :span="9">
                                    <strong>DATE:</strong> {{ start_date | moment("Do MMMM, YYYY") }}
                                </el-col>
                                <el-col :span="7">
                                    <strong v-if="client_inf[0]">Audit #:</strong> {{ client_inf[0] ? client_inf[0].id : '' }}
                                </el-col>
                                <el-col :span="7">
                                    <strong v-if="client_inf[0]">ID #:</strong> {{ client_inf[0] ? client_inf[0].gx_id : '' }}
                                </el-col>
                                <el-col :span="1">
                                    <el-button v-if="client_inf.length > 0" class="download-btn" @click="downloadExcel()" icon="el-icon-download" circle></el-button>
                                </el-col>
                            </el-row>
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
            average1: 0, 
            average2: 0,
            different: {serial:0},
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
                    height: 350
                },
                legend: {
                        show: false
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '80%',
                        endingShape: 'rounded',
                        borderRadius: 0,
                        dataLabels: {
                          position: 'top', // top, center, bottom
                        },
                    },
                },
                dataLabels: {
                    enabled: true, 
                    formatter: function (val) {
                        return val + "%";
                    },
                    offsetY: -23,
                    style: {
                        fontSize: '10px',
                        colors: ["#999"]
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: [],
                },
                yaxis: {
                title: false
                },
                fill: {
                    opacity: 1, 
                    colors: ['#0172e2', '#fdb345']
                },
                tooltip: {
                y: {
                    formatter: function (val) {
                    return "$ " + val + " thousands"
                    }
                }
                }
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
                date: new Date(),
                items: []    
            },{}], 
            start_date: new Date(),
            end_date: new Date(),
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() > Date.now();
                },
                
                shortcuts: [{
                    text: 'Today',
                    onClick(picker) {
                    picker.$emit('pick', new Date());
                    }
                }, {
                    text: 'Yesterday',
                    onClick(picker) {
                    const date = new Date();
                    date.setTime(date.getTime() - 3600 * 1000 * 24);
                    picker.$emit('pick', date);
                    }
                }, {
                    text: 'A week ago',
                    onClick(picker) {
                    const date = new Date();
                    date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                    picker.$emit('pick', date);
                    }
                }]
            },
            fetchWP: new FetchWP({
                    restURL: window.gx_object.root,
                    restNonce: window.gx_object.api_nonce,

            }),
        }
    }, 
    mounted(){
        this.getData({id: window.gx_object.user_id})
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
            
            if(response.results.length > 0 && response.results[0].date){
                this.start_date = new Date(response.results[0].date)
            }
            if(response.results.length > 1 && response.results[1].date){
                this.end_date = new Date(response.results[1].date)
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
                this.different = {serial: 1, diff: this.average1 - this.average2}
            }else{
                this.different = {serial: 2, diff: this.average2 - this.average1}
            }
            

            this.client_inf = response.results 
            
            let tempSeries = [{name: 'Item 1', data: temScore1},{name: 'Item 2', data: temScore2}];
            
            this.series = tempSeries;
            this.chartOptions = {
                xaxis: {
                    categories: temCat
                }
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

