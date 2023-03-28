<template>
    <div>
        <!-- New Entry modal-->
        <el-dialog title="Guest Experience Score(GXS)" :visible.sync="modalStatus">
        <el-form :model="gx_lists[editItem]">
            <el-row>
                 <el-col :span="12">
                    <el-form-item label="Client Name" :label-width="formLabelWidth">
                        <el-input v-model="gx_lists[editItem].name" autocomplete="off"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Location" :label-width="formLabelWidth">
                        <el-input v-model="gx_lists[editItem].location" autocomplete="off"></el-input>
                    </el-form-item>    
                </el-col>
            </el-row>
            
            <el-row>
                 <el-col :span="12">
                    <el-form-item label="ID" :label-width="formLabelWidth">
                        <el-input v-model="gx_lists[editItem].gx_id" autocomplete="off"></el-input>
                    </el-form-item>
                 </el-col>
                 <el-col :span="12">
                    <el-form-item label="Type" :label-width="formLabelWidth">
                        <el-input v-model="gx_lists[editItem].type" autocomplete="off"></el-input>
                    </el-form-item>
                 </el-col>
            </el-row>
            <el-row>
                 <el-col :span="12">
                    <el-form-item label="Onsite Staff" :label-width="formLabelWidth">
                        <el-input-number class="w-100" v-model="gx_lists[editItem].staff" autocomplete="off"></el-input-number>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Touch Points" :label-width="formLabelWidth">
                        <el-input-number class="w-100" v-model="gx_lists[editItem].touch_points" autocomplete="off"></el-input-number>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                 <el-col :span="12">
                    <el-form-item label="Sectors Numbers" :label-width="formLabelWidth">
                        <el-input-number class="w-100" v-model="gx_lists[editItem].sector_number" autocomplete="off"></el-input-number>
                        
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Date" :label-width="formLabelWidth">
                        <el-date-picker
                            v-model="gx_lists[editItem].date"
                            type="date"
                            placeholder="Pick a day"
                            class="w-100 max-100"
                            :picker-options="pickerOptions">
                        </el-date-picker>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
               <el-col :span="12">
                    <el-form-item label="Logo" :label-width="formLabelWidth">
                        <el-button
                            class="avatar-uploader logo-upload" @click="wpMediaUpload()">
                            <img v-if="gx_lists[editItem].logourl" :src="gx_lists[editItem].logourl" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon mw-100px mh-100px"></i>
                        </el-button>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="User" :label-width="formLabelWidth">
                        <el-select class="w-100" v-model="gx_lists[editItem].user_id" placeholder="Select">
                            <el-option
                            v-for="item in wp_users"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row>
                <el-table stripe :data="gx_lists[editItem].items">
                    <el-table-column label="Name">
                        <template slot-scope="scope">
                           <el-input v-if="gx_lists[editItem].items[scope.$index].mode" v-model="gx_lists[editItem].items[scope.$index].name" />
                           <el-span v-else>{{ gx_lists[editItem].items[scope.$index].name }}</el-span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Score">
                        <template slot-scope="scope">
                           <el-input-number class="w-100" v-if="gx_lists[editItem].items[scope.$index].mode" v-model="gx_lists[editItem].items[scope.$index].score" :min="0" :max="100" :step="1"></el-input-number>
                           <el-span v-else>{{ gx_lists[editItem].items[scope.$index].score }}</el-span>
                        </template>
                    </el-table-column>
                    
                    <el-table-column label="Operations">
                        <template slot-scope="scope">
                           <el-button type="primary" icon="el-icon-edit"></el-button>
                           <el-button @click="remoteItem(scope.$index)" type="danger" icon="el-icon-delete"></el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-row>
            <el-row type="flex" justify="end" class="mt-1">
                <el-button type="primary" icon="el-icon-plus" circle @click.native.prevent="addNewItem()" >
                </el-button>
            </el-row>
        </el-form>
        <span slot="footer" class="dialog-footer">
            <el-button type="primary" @click="submitForm()">Submit</el-button>
        </span>
        </el-dialog>
        <!-- Modal End -->
        
        <!-- example content  start -->
        <el-container>
            <el-header>
                <el-row>
                    <el-col :span="12">
                        <h1>GX Audit Score Lists</h1>
                    </el-col>
                    <el-col type="flex" class="item-center" justify="end" :span="12">
                        <el-row type="flex" justify="end" class="mt-1 item-center" >
                            <el-button  type="primary" plain size="small" @click="onOpenNewEntry()" >Add Entry &nbsp;<i class="el-icon-right"></i></el-button>
                        </el-row>
                    </el-col>
                </el-row>
            </el-header>
            <el-main>
                

                <el-row>
                    <el-table
                        :data="gx_lists"
                        stripe
                        style="width: 100%">
                        <el-table-column
                            label="Logo"
                            width="180">
                            <template slot-scope="scope">
                                <!-- <p>logo {{ scope.row.logourl }}</p> -->
                                <img class="list-logo" v-if="scope.row.logourl" :src="scope.row.logourl" />
                                <el-image v-else>
                                    <div slot="error" class="image-slot">
                                        <i class="el-icon-picture-outline"></i>
                                    </div>
                                </el-image>
                            </template>
                        </el-table-column>
                        <el-table-column
                            prop="name"
                            label="Name"
                            width="180">
                        </el-table-column>
                        
                        <el-table-column
                            prop="location"
                            label="Location">
                        </el-table-column>
                        <el-table-column
                            prop="type"
                            label="Type">
                        </el-table-column>
                        <el-table-column
                            prop="touch_points"
                            label="Touch Point">
                        </el-table-column>
                        <el-table-column
                            label="Operations" 
                            prop="id">
                            <template slot-id="id" slot-scope="scope">
                                <el-button size="small" @click="editScore(scope.$index)" type="primary" icon="el-icon-edit"></el-button>
                                <el-button size="small" @click="remoteEnterItem(scope.$index, gx_lists[scope.$index].id)" type="danger" icon="el-icon-delete"></el-button>
                            </template>      
                        </el-table-column>
                    </el-table>      
                </el-row>
            </el-main>
        </el-container>
        <!-- example content end -->
    </div>
</template>
<script>

import FetchWP from '../utils/fetchWP'
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
            fetchWP: new FetchWP({
                    restURL: window.gx_object.root,
                    restNonce: window.gx_object.api_nonce,

            }),
            wp_users:[],
            gx_lists:[{
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
            editItem: 0,
            modalStatus: false,
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
            }
        }
    },
    mounted(){
        this.fetchWP.get(`config`)
        .then((response) => { 
            this.wp_users = response.wp_users 
            response.gx_lists.map((v, k) => {
                response.gx_lists[k].items = JSON.parse(v.items)
            })
            this.gx_lists = response.gx_lists 
        })
    },
    methods: {
        onOpenNewEntry() {
            let newItem = {
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
                items: []    
            }
          this.modalStatus = this.modalStatus ? false : true
          this.gx_lists = [...this.gx_lists, ...[newItem]]
          this.editItem = this.gx_lists.length - 1
        },
        handlePictureCardPreview () {

        }, 
        handleDownload () {

        }, 
        wpMediaUpload () {
            var self = this;
            wp.media.editor.send.attachment = function (props, attachment) {
                // console.log('self: ', self.gx_lists, 'this edit item: ', self.editItem)
                self.gx_lists[self.editItem].logourl = attachment.url
                self.gx_lists[self.editItem].logoid = attachment.id
            };
            wp.media.editor.open();
        }, 
        addNewItem () {
            let self = this
            this.gx_lists[this.editItem].items = [...self.gx_lists[this.editItem].items, {name:'', score:0, mode:1, date: Date.now() }]
        }, 
        remoteItem(index){
            this.gx_lists[this.editItem].items.splice(index, 1)
        }, 
        submitForm(){
            this.fetchWP.post(`new_entry`, {data: this.gx_lists[this.editItem]})
            .then( (response) => { 
                // console.log('entry successfully: ', response) 
                this.modalStatus = this.modalStatus ? false : true
            })
        },
        remoteEnterItem(index, id){
            this.fetchWP.post(`remove_by_id`, {id: id})
            .then((response) => 
                { 
                    if(response.msg == 'success'){
                        this.gx_lists.splice(index, 1)
                    }
                }
            )
            
        }, 
        editScore(index){
            this.editItem = index
            this.modalStatus = this.modalStatus ? false : true
        }
    }
}
</script>

