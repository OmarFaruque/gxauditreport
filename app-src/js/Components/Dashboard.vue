<template>
    <div>
        <!-- New Entry modal-->
        <el-dialog  title="Guest Experience Score(GXS)" :visible.sync="modalStatus">
        <el-form v-loading="loading" v-if="gx_lists[editItem]" :model="gx_lists[editItem]">
            <el-row type="flex" :gutter="20">
                <el-col :span="12">
                    <h4 class="mt-0">GXS Information</h4>
                    <el-form-item label="User" :label-width="formLabelInlineW">
                        <el-select @change="getOnlyUserDetails($event)" class="w-100" v-model="gx_lists[editItem].user_id" placeholder="Select">
                            <el-option
                                v-for="item in wp_users"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id.toString()">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Date" :label-width="formLabelInlineW">
                        <el-date-picker
                            :localTime="false"
                            v-model="gx_lists[editItem].date"
                            type="date"
                            placeholder="Pick a day"
                            class="w-100 max-100"
                            :picker-options="pickerOptions">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="Excel" :label-width="formLabelInlineW">
                        <el-row type="flex" class="item-center">
                            <el-button @click="excelFileUpload()" class="upload-demo excel-upload">
                                <i v-if="gx_lists[editItem].excel" class="el-icon-document-remove"></i>
                                <i v-else class="el-icon-upload"></i>
                            </el-button>
                            <i v-if="gx_lists[editItem].excel" @click="excelRemove()" class="el-icon-circle-close ml-1 remove-excel"></i>
                        </el-row>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <h4 class="mt-0">Social Reviews</h4>
                    <el-row v-for="social in gx_lists[editItem].socials" :key="social.value" type="flex" :gutter="10" justify="center">
                        <el-col>
                            <el-form-item>
                                <el-select class="w-100" v-model="social.label" placeholder="Select">
                                    <el-option
                                        v-for="item in socialOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col>
                            <el-form-item>
                                <el-input-number class="w-100" v-model="social.value" placeholder="FB Score"></el-input-number>
                            </el-form-item>
                        </el-col> 
                    </el-row>
                    <el-row type="flex" justify="end" class="mb-1">
                        <el-button type="primary" icon="el-icon-plus" circle @click.native.prevent="addNewSocial()" >
                    </el-button>
                </el-row>
                </el-col>
            </el-row>


            <el-collapse v-if="gx_lists[editItem].user_id" v-model="activePersonalInformation" >
                <el-collapse-item title="User Details" name="1">
                    <el-row style="margin-right: 1px;">
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
                    
                    <el-row style="margin-right: 1px;">
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
                    <el-row style="margin-right: 1px;">
                        <el-col :span="12">
                            <el-form-item label="Onsite Staff" :label-width="formLabelWidth">
                                <el-input-number class="w-100" v-model="gx_lists[editItem].staff" autocomplete="off"></el-input-number>
                            </el-form-item>
                             <el-form-item label="Sectors Numbers" :label-width="formLabelWidth">
                                <el-input-number class="w-100" v-model="gx_lists[editItem].sector_number" autocomplete="off"></el-input-number>
                            </el-form-item>
                            <el-form-item label="Touch Points" :label-width="formLabelWidth">
                                <el-input-number class="w-100" v-model="gx_lists[editItem].touch_points" autocomplete="off"></el-input-number>
                            </el-form-item>
                        </el-col>
                        <el-col :span="12">
                           <el-form-item label="Logo" :label-width="formLabelWidth">
                                <el-button
                                    class="avatar-uploader logo-upload" @click="wpMediaUpload()">
                                    <img v-if="gx_lists[editItem].logourl" :src="gx_lists[editItem].logourl" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon mw-100px mh-100px"></i>
                                </el-button>
                            </el-form-item> 
                        </el-col>
                    </el-row>
 
                </el-collapse-item>
            </el-collapse>

            <el-row>
                <el-table stripe :data="gx_lists[editItem].items" style="width: 100%">
                    <el-table-column  label="Name">
                        <template slot-scope="scope">
                           <el-input v-model="gx_lists[editItem].items[scope.$index].name" />
                        </template>
                    </el-table-column>
                    <el-table-column label="Score" width="200">
                        <template slot-scope="scope">
                           <el-input-number class="w-100" v-model="gx_lists[editItem].items[scope.$index].score" :min="0" :max="100" :step="1"></el-input-number>
                        </template>
                    </el-table-column>
                    
                    <el-table-column width="100" label="Operations">
                        <template slot-scope="scope">
                           <!-- <el-button type="primary" icon="el-icon-edit"></el-button> -->
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
            <el-button v-if="!loading" type="primary" @click="submitForm()">Submit</el-button>
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
                            prop="user_id"
                            label="User"
                            width="180">
                            <template slot-id="id" slot-scope="scope">
                                <span v-if="wp_users.findIndex(x => x.id == scope.row.user_id) > -1">
                                    {{ wp_users[wp_users.findIndex(x => x.id == scope.row.user_id)].name   }}
                                </span>
                                
                            </template>
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
                            label="Date">
                            <template slot-scope="scope">
                                <span v-if="scope.row.date != '0000-00-00 00:00:00'">{{ scope.row.date | moment("Do MMMM, YYYY") }}</span>
                            </template>
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
            activePersonalInformation: [],
            loading: false,
            socialOptions:[
                {value:'facebook', label: 'Facebook'},
                {value:'twitter', label: 'Twitter'},
                {value:'instagram', label: 'Instagram'}, 
                {value: 'linkedin', label: 'LinkedIn'}, 
                {value: 'telegram', label: 'Telegram'}, 
                {value: 'google', label: 'Google'}, 
                {value: 'whatsapp', label: 'WhatsApp'},
                {value: 'tik-tok', label: 'TikTok'},
                {value: 'tripadvisor', label: 'TripAdvisor'}, 
                {value: 'foodpanda', label: 'Foodpanda'},
                {value: 'lineman', label: 'Lineman'}, 
                {value: 'robinhood', label: 'Robinhood'},
                {value: 'grabfood', label: 'Grabfood'},
                {value: 'shopee-food', label: 'Shopee food'}
            ],
            formLabelWidth: '140px',
            formLabelInlineW: '100px',
            fetchWP: new FetchWP({
                    restURL: window.gx_object.root,
                    restNonce: window.gx_object.api_nonce,

            }),
            wp_users:[],
            gx_lists:[],
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
                response.gx_lists[k].socials = JSON.parse(v.socials)
            })
            
            if(response.gx_lists.length) this.gx_lists = response.gx_lists 
            
        })
        .catch(error => {
            console.log('error is: ', error)
        })
       
    },
    methods: {
        addNewSocial(){
            let self = this
            this.gx_lists[this.editItem].socials = [...self.gx_lists[this.editItem].socials, {value:'', label: ''}]
            // this.gx_lists[this.editItem].socials = [...this.gx_lists[this.editItem].socials, ...[{value:'', label: ''}]]
        },
        getOnlyUserDetails(v){
            this.fetchWP.post(`get_user_details`, {id: v})
            .then( (response) => {
                this.gx_lists[this.editItem].name           = response.users.name
                this.gx_lists[this.editItem].location       = response.users.location
                this.gx_lists[this.editItem].gx_id          = response.users.gx_id
                this.gx_lists[this.editItem].type           = response.users.type
                this.gx_lists[this.editItem].staff          = response.users.staff
                this.gx_lists[this.editItem].logoid         = response.users.logoid
                this.gx_lists[this.editItem].logourl        = response.users.logourl
                this.gx_lists[this.editItem].sector_number  = response.users.sector_number
                this.gx_lists[this.editItem].touch_points   = response.users.touch_points
                this.gx_lists[this.editItem].items          = response.items ? response.items : []
                // this.gx_lists[this.editItem].socials        = response.socials ? response.socials : []
            })
        },
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
                items: [],
                socials:[],
                excel: ''    
            }
          this.modalStatus = this.modalStatus ? false : true
          this.gx_lists = [...this.gx_lists, ...[newItem]]
          this.editItem = this.gx_lists.length - 1
        },
        handlePictureCardPreview () {

        }, 
        handleDownload () {

        }, 
        excelFileUpload(){
            var self = this;
            wp.media.editor.send.attachment = function (props, attachment) {
                self.gx_lists[self.editItem].excel = attachment.url
            };
            wp.media.editor.open();
        }, 
        excelRemove(){
            var self = this;
            self.gx_lists[self.editItem].excel = ''
        },
        wpMediaUpload () {
            var self = this;
            wp.media.editor.send.attachment = function (props, attachment) {
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
            this.loading = true
            this.fetchWP.post(`new_entry`, {data: this.gx_lists[this.editItem]})
            .then( (response) => { 
                this.loading = false
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

