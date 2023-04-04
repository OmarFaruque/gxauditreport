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
                            <code>&lt;?php echo do_shortcode('[gx_score_board]'); ?&gt;</code>
                        </el-card>
                    </el-col>
                </el-row>
                <el-row class="mb-3">
                    <el-col :span="12">
                        <el-card shadow="always">
                            <h4>Shortcode in content</h4>
                            <code>[gx_score_board]</code>
                        </el-card>
                    </el-col>
                </el-row>
                <el-row>
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
                this.gx_display_message = response.gx_display_message
            })
    },
    methods: {
        setDisplayMessage(){
            this.fetchWP.post(`update_settings`, {gx_display_message: this.gx_display_message})
            .then( (response) => { 
                // console.log('this is response: ', response)
            })
        }
    }
}
</script>

