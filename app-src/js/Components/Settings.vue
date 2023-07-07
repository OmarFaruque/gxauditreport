<template>
    <div>
        <el-container>
            <el-header>
                <el-row>
                    <h1>Settings</h1>
                </el-row>
            </el-header>
            <el-main>
                <el-row class="mb-3">
                    <el-col :span="12">
                        <el-card shadow="always">
                            <h4>Shortcode in php</h4>
                            <p><span>Score Board: <code>&lt;?php echo do_shortcode('[gx_score_board]'); ?&gt;</code></span></p>
                            <p><span>Score Lists: <code>&lt;?php echo do_shortcode('[gx_score_lists]'); ?&gt;</code></span></p>
                        </el-card>
                    </el-col>
                </el-row>
                <el-row class="mb-3">
                    <el-col :span="12">
                        <el-card shadow="always">
                            <h4>Shortcode in content</h4>
                            <p><span>Score Board: <code>[gx_score_board]</code></span></p>
                            <p><span>Score Lists: <code>[gx_score_lists]</code></span></p>
                        </el-card>
                    </el-col>
                </el-row>
                <el-row class="mb-3">
                    <el-col :span="12">
                        <el-card shadow="always">
                            <h4>Set a message to show the user if the user isn't a logged-in user</h4>
                            <el-input v-model="gx_display_message"></el-input>
                            <el-row type="flex" justify="end" class="mt-1">
                                <el-button type="primary" @click="setDisplayMessage()">Save</el-button>
                            </el-row>
                        </el-card>
                    </el-col>
                </el-row>

                <!-- Import Export Secton -->
                <!-- <el-row>
                    <el-col :span="12">
                        <el-card shadow="always">
                            <h4>Import / Export Score</h4>
                            
                            <el-row type="flex" :gutter="20">
                                <el-col>
                                    <el-button @click="downloadDataAsJSON()" type="primary" icon="el-icon-download">Download</el-button>
                                    <br/><span>Download all score as json &nbsp;</span>
                                </el-col>

                                <el-col>
                                    <el-upload
                                        class="upload-demo"
                                        ref="upload"
                                        action="https://jsonplaceholder.typicode.com/posts/"
                                        :on-success="handleChange"
                                        :auto-upload="true">
                                        <el-button icon="el-icon-upload" slot="trigger" type="primary">Upload file</el-button>
                                    </el-upload>
                                    <span>Upload score from Json file &nbsp;</span>
                                </el-col>
                                
                            </el-row>
                            
                        </el-card>
                    </el-col>
                </el-row> -->
            </el-main>
      </el-container>
    </div>
</template>
<script>
import FetchWP from '../utils/fetchWP'
export default {
    name: 'Settings', 
    data(){
        return{
            gx_display_message: '',
            fetchWP: new FetchWP({
                restURL: window.gx_object.root,
                restNonce: window.gx_object.api_nonce,
            }),
        }
    }, 
    mounted(){
        this.fetchWP.get(`update_settings`)
            .then( (response) => { 
                this.gx_display_message = response.gx_display_message ? response.gx_display_message : ''
            })
    },
    methods: {
        setDisplayMessage(){
            this.fetchWP.post(`update_settings`, {gx_display_message: this.gx_display_message})
            .then( (response) => { 
                // console.log('this is response: ', response)
            })
        },

        downloadDataAsJSON(){
            this.fetchWP.get(`config`)
                .then((response) => { 
                    var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(response.gx_lists));
                    var dlAnchorElem = document.createElement('a');
                    dlAnchorElem.setAttribute("href",     dataStr     );
                    dlAnchorElem.setAttribute("download", "score.json");
                    dlAnchorElem.click();
                })
                .catch(error => {
                    console.log('error is: ', error)
                })
        }, 
        handleChange(res, file) {
            
            let reader = new FileReader();

            reader.readAsText(file.raw);

            reader.onload = function() {
                let data = JSON.parse(reader.result);
                this.fetchWP.post(`import_score`)
                .then((response) => {
                    let fssf = ''
                })
                .catch(error => {
                    console.log('error')
                })
            };

          
        }
    }
}
</script>

