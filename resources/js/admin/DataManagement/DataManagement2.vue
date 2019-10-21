<template>


  <div class="pa-5">

    <!--
    <v-alert icon="mdi-shield-lock-outline" prominent text type="info">
      <strong>Donec quam felis, ultricies.</strong> Sed in libero ut nibh placerat accumsan.. Curabitur blandit mollis lacus. Curabitur blandit mollis lacus.
    </v-alert>
    -->

    <template v-if="exist == 0">
        <div class="text-center">
        <v-progress-circular
            :size="70"
            :width="7"
            color="primary"
            indeterminate
            ></v-progress-circular>
        </div>
    </template>

    <template v-if="exist == 1">
       
        <AppManagement :id="selected"></AppManagement>
    </template>

    <template v-if="exist == 2">
        <AppCreator></AppCreator>
    </template>
  
    
  </div>
</template>

<script>
    import { mapState } from 'vuex'
    import AppCreator from './components/AppCreator'
    import AppManagement from './components/AppManagement'
    export default {
        components: {
            AppCreator,
            AppManagement
        },
        // inject: ['theme'],
        data : function(){
          return {
            selected: 0,
            app: [],
            exist: 0,
            
          }
        },
        methods: {
         
          
          getApp(){
            var self = this;  
            // Create App
            axios.get('/app/get', {
                data: this.app,
            })
            .then(function (res) {
                
                self.selected = self.appSelected;
                self.app = res.data;
                

                if(self.app.length > 0){
                    self.exist = 1;
                }else{
                    self.exist = 2;
                }


            })
            .catch(function (error) {
                console.log(error);
            });
            

          }
        },
        computed: {
            ...mapState([
                'appSelected',
            ]),
        },
        mounted() {

            const self = this;

            self.getApp();
        }
    }
</script>