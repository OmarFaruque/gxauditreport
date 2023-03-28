<template>
    <div>
        <el-container>
            <el-header>
                <el-row>
                    <h3>Guest Experience Score(GXS):</h3>
                </el-row>
            </el-header>
            <el-main class="mt-3">
                <el-row class="mb-3" :gutter="20">
                    <el-col :span="10">
                        <el-row :gutter="20">
                            <el-col :span="12">
                                <div class="grid-content">
                                    <el-card shadow="never" class="border-0 bg-blue color-white">
                                        <h4 class="mb-1 mt-0">{{ start_date | moment("Do MMMM, YYYY") }}</h4>
                                        <h1 class="mt-0 mb-0 score"><strong>45.2</strong></h1>
                                        <h5 class="mt-1 mb-0 color-green"><i class="el-icon-caret-top"></i> +2.4</h5>
                                    </el-card>
                                    <el-date-picker
                                        v-model="start_date"
                                        type="date"
                                        placeholder="Pick a day"
                                        class="w-100 mt-1 max-100"
                                        :picker-options="pickerOptions">
                                    </el-date-picker>
                                </div>
                            </el-col>
                            <el-col :span="12">
                                <el-card shadow="never" class="border-0 bg-yellow color-white">
                                        <h4 class="mb-1 mt-0">{{ end_date | moment("Do MMMM, YYYY") }}</h4>
                                        <h1 class="mt-0 mb-0 score"><strong>45.2</strong></h1>
                                        <h5 class="mt-1 mb-0 color-green"><i class="el-icon-caret-top"></i> +2.4</h5>
                                    </el-card>
                                    <el-date-picker
                                        v-model="end_date"
                                        type="date"
                                        placeholder="Pick a day"
                                        class="w-100 mt-1 max-100"
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
                                        <li>Client: <strong>{{ client_inf[0].name }}</strong></li>
                                        <li>Location: <strong>{{ client_inf[0].location }}</strong></li>
                                        <li>ID: <strong>{{ client_inf[0].gx_id }}</strong></li>
                                        <li>Type: <strong>{{ client_inf[0].type }}</strong></li>
                                        <li>Amount of Onsite Staff: <strong>{{ client_inf[0].staff }}</strong></li>
                                        <li>Total Number of Touch Points: <strong>{{ client_inf[0].touch_points }}</strong></li>
                                        <li>Total Number of Sectors: <strong>{{ client_inf[0].sector_number }}</strong></li>
                                    </ul>
                                </el-card>
                            </el-col>
                            <el-col :span="12">
                                <div class="p-img">
                                    <el-image class="mw-100 border-rounded"
                                    :src="client_inf[0].logourl"
                                    fit="fill"></el-image>
                                </div>
                            </el-col>
                        </el-row>
                    </el-col>
                </el-row>
                <el-row class="mt-3">
                    <el-col :span="16">
                        <div class="chartWrap">
                            <div id="chart">
                                <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                            </div>
                        </div>
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
                title: {
                    text: '$ (thousands omar)'
                }
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
            }], 
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
        this.fetchWP.post(`frontend_config`, {id: window.gx_object.user_id})
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

            
            this.client_inf = response.results 
            
            let tempSeries = [{name: 'Item 1', data: temScore1},{name: 'Item 2', data: temScore2}];
            
            this.series = tempSeries;
            this.chartOptions = {
                xaxis: {
                    categories: temCat
                }
            } 

            
            console.log('rootitems: ', response.results)
        })
    }, 
    methods: {

    }
}
</script>

