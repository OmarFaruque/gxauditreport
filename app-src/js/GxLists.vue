
<template>
    <div id="gxLists">
        <div class="gx-lists-wrap">
           <gxlistswrap/>
        </div>
      
    </div>
</template>

<script>
import GxListWrap from './Components/GxListWrap.vue';
  export default {
      name: 'GxLists',
      components:{
        'gxlistswrap' : GxListWrap
      },
      data() {
          return {
              topMenus: []
          }
      },
      methods: {
          
      },
      mounted() {
          
      }
  }
</script>
