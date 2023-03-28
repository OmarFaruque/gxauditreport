<template>
    <div id="gx_scoreboard_app">
        <div class="gx_scoreboard_wrap">
            <scoreboard />
        </div>
    </div>
</template>

<script>
  import ScoreBoard from './Components/ScoreBoard.vue';
  export default {
      name: 'PublicApp',
      components:{
        'scoreboard' : ScoreBoard
      },
      data() {
          return {
              topMenus: []
          }
      },
      methods: {
          
      },
      mounted() {
        //   this.setTopmenu();
      }
  }
</script>
