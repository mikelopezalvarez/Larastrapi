<template>


  <div class="pa-5">

    
    <!-- <v-alert icon="mdi-shield-lock-outline" prominent text type="info">
      <strong>Donec quam felis, ultricies.</strong> Sed in libero ut nibh placerat accumsan.. Curabitur blandit mollis lacus. Curabitur blandit mollis lacus.
    </v-alert>
    -->

    <CardTable></CardTable>
    <CardTable></CardTable>
    <CardTable></CardTable>

    
  </div>
</template>

<script>
    import { mapState } from 'vuex'
    import CardTable from './CardTable'
    export default {
       components: {
            CardTable,
        },
        // inject: ['theme'],
        data : function(){
          return {
            id: null,
            app: [],
            tables: [],
            
          }
        },
        methods: {
         
          
          getApp(){

            var self = this;  
            // Get application object
            axios.post('/app/getObjectById', {
                id: this.id,
            })
            .then(function (res) {
                
                // Get configuration
                self.app = res.data;
                self.tables = res.data.tables;
                

            })
            .catch(function (error) {
                console.log(error);
            });

          }


        },
        
        computed: {
            ...mapState([
                'appSelected'
            ]),

        },
        mounted() {

            this.getApp();

            
           
        },
        created(){
            this.id = this.appSelected;
        }

        
    }
</script>