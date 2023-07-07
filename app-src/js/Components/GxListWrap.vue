<template>
    <div>
        <el-table
            :data="lists"
            stripe
            style="width: 100%; margin-bottom: 0; display: grid; justify-content: center;">
            <el-table-column
                label="Position"
                width="80">
                <template slot-scope="scope">
                    <span>{{ scope.$index + 1 }}</span>
                </template>
            </el-table-column>
            <el-table-column
                prop="location"
                label="Outlets"
                width="180">
            </el-table-column>
            <el-table-column
                prop="date"
                label="Date"
                width="150">
                <template slot-scope="scope">
                    <span v-if="scope.row.date != '0000-00-00 00:00:00'">{{ scope.row.date | moment("Do MMMM, YYYY") }}</span>
                </template>
            </el-table-column>
            <el-table-column
                label="GXS"
                prop="gxs"
                width="70">
            </el-table-column>
            
            <el-table-column v-for="(item, index) in lists[0].items" :key="index"
                :label="item.name"
                width="100">
                <template slot-scope="scope">
                    <span>{{ lists[scope.$index].items[index].score }}</span>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
import FetchWP from '../utils/fetchWP'
export default {

    name: 'GxListsWrap', 
    data(){
        return {
            fetchWP: new FetchWP({
                    restURL: window.gx_object.root,
                    restNonce: window.gx_object.api_nonce,

            }), 
            lists: [{items:[]}]
        }
    },
    mounted(){
        this.fetchWP.post(`getusergx`, {user_id: window.gx_object.user_id})
        .then((response) => { 
            response.gx_lists.map((v, k) => {
                response.gx_lists[k].items = JSON.parse(v.items)
                response.gx_lists[k].socials = JSON.parse(v.socials)
                let gxValueArray = response.gx_lists[k].items.map((t, m) => {
                    return t.score
                })
                response.gx_lists[k].gxs = (gxValueArray.reduce( (a, b) => a + b, 0 ) / gxValueArray.length).toFixed(1)   
            })
            
            if(response.gx_lists.length) this.lists = response.gx_lists 
        })
        .catch(error => {
            console.log('error is: ', error)
        })
    }
}

</script>