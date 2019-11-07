<template>


  <div class="pa-5">

    <!--
    <v-alert icon="mdi-shield-lock-outline" prominent text type="info">
      <strong>Donec quam felis, ultricies.</strong> Sed in libero ut nibh placerat accumsan.. Curabitur blandit mollis lacus. Curabitur blandit mollis lacus.
    </v-alert>-->
    <v-row class="">
      
      
      
      <v-col md="3" v-for="item in apps" v-bind:data="item"
           v-bind:key="item.name">

          <v-card 
            class="mx-auto"
            max-width="344"
        >
            <v-card-text>
           
            <p class="display-1 text--primary">
                {{ item.name }}
            </p>
            <p>{{ item.app_description }}</p>
            </v-card-text>
            <v-card-actions>
            <v-btn
                text
                color="deep-purple accent-4"
                @click="selectApp(item)"
            >
                Go to App 
            </v-btn>
            </v-card-actions>
        </v-card>
       
      </v-col>






      
    </v-row>
    
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
    export default {
        // inject: ['theme'],
        data : function(){
          return {
            name: '',
            //apps: [],
           
            
          }
        },
        methods: {

            //Mutations represent methods or functions
            ...mapMutations([
                'getApps',
            ]),

            selectApp(i){
                const self = this;
                // Set App
                axios.post('/general/setApp', {
                    id: i.id,
                })
                .then(function (res) {
                
                    self.$router.push({name: "app_management"});

                })
                .catch(function (error) {
                    console.log(error);
                });
            }
         
        },
        mounted() {

            const self = this;

            self.getApps();
        },
        created(){
            // If not found apps, redirect to create app
            if(this.apps.length <= 0){
                this.$router.push({name: "app_create"});
            }
        },
        computed: {
          ...mapState([
              'apps'
          ]),

        }
    }
</script>