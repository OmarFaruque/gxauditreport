<template>
    <div>
        <!-- New Entry modal-->
        <el-dialog title="Guest Experience Score(GXS)" :visible.sync="modalStatus">
        <el-form :model="form">
            <el-row>
                 <el-col :span="12">
                    <el-form-item label="Client Name" :label-width="formLabelWidth">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Location" :label-width="formLabelWidth">
                        <el-input v-model="form.location" autocomplete="off"></el-input>
                    </el-form-item>    
                </el-col>
            </el-row>
            
            <el-form-item label="ID" :label-width="formLabelWidth">
                <el-input v-model="form.id" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Type" :label-width="formLabelWidth">
                <el-input v-model="form.type" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Onsite Staff" :label-width="formLabelWidth">
                <el-input v-model="form.staff" type="number" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Touch Points" :label-width="formLabelWidth">
                <el-input v-model="form.touch_points" type="number" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Sectors Numbers" :label-width="formLabelWidth">
                <el-input v-model="form.sector_number" type="number" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Logo" :label-width="formLabelWidth">
                <el-button
                    class="avatar-uploader logo-upload" @click="wpMediaUpload()">
                    <img v-if="form.logoUrl" :src="form.logoUrl" class="avatar">
                    <i v-else class="el-icon-plus avatar-uploader-icon mw-100px mh-100px"></i>
                </el-button>
            </el-form-item>


            <el-form-item label="Items" :label-width="formLabelWidth">
                <el-table>
                    <el-table-column>
                        label="Name"
                    </el-table-column>
                    <el-table-column>
                        label="Score"
                    </el-table-column>
                    <el-table-column>
                        label="Month"
                    </el-table-column>
                </el-table>
                
                
                
                <el-row v-for="item in form.items"> 
                    <el-col :span="8">
                        <div class="grid-content bg-purple">
                            <el-input placeholder="Item Name..." v-model="item.name" autocomplete="off"></el-input>
                        </div>
                    </el-col>
                    <el-col :span="8"><div class="grid-content bg-purple-light">

                    </div></el-col>
                    <el-col :span="8"><div class="grid-content bg-purple"></div>3</el-col>
                </el-row>
            </el-form-item>

            <el-form-item label="Zones" :label-width="formLabelWidth">
                <el-select v-model="form.region" placeholder="Please select a zone">
                    <el-option label="Zone No.1" value="shanghai"></el-option>
                    <el-option label="Zone No.2" value="beijing"></el-option>
                </el-select>
            </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
            <el-button @click="onOpenNewEntry()">Cancel</el-button>
            <el-button type="primary" @click="onOpenNewEntry(false)">Confirm</el-button>
        </span>
        </el-dialog>
        <!-- Modal End -->
        
        <!-- example content  start -->
        <el-container>
            <el-header>
                <h1>GX Audit Score Lists</h1>
            </el-header>
            <el-main>
                <el-row>
                    <!-- <el-col>
                        <img width="300px" :src="$publicAssets('images/logo.png')" alt="vue">
                    </el-col> -->
                    <el-col>
                        <el-button type="primary" plain size="small" @click="onOpenNewEntry()" >Add Entry &nbsp;<i class="el-icon-right"></i></el-button>
                    </el-col>
                </el-row>  
                <el-row>
                    
                </el-row>
            </el-main>
        </el-container>
        <!-- example content end -->
    </div>
</template>
<script>
import { reactive } from 'vue'
import MtcWordpressMediaLibrary from '@mtcmedia/vue-wordpress-media-library'


export default {
    name: 'Dashboard',
    components: {
        'mtc-wordpress-media-library' : MtcWordpressMediaLibrary
    },
    data(){
        return {
            vueJs: 'https://vuejs.org/', 
            formLabelWidth: '140px',
            modalStatus: false,
            form: reactive({
                name: '',
                location: '',
                id: '',
                type: '',
                staff: 0,
                touch_points: 0,
                sector_number: 0,
                logoUrl: '',
                logoId: '',
                items: [ {name:''} ]
                
                })
        }
    },
    methods: {
        onOpenNewEntry() {
          this.modalStatus = this.modalStatus ? false : true
        },
        handlePictureCardPreview () {

        }, 
        handleDownload () {

        }, 
        wpMediaUpload () {
            var self = this;
            wp.media.editor.send.attachment = function (props, attachment) {
                self.form.logoUrl = attachment.url
                self.form.logoId = attachment.id
            };
            wp.media.editor.open();
        }
    }
}
</script>

