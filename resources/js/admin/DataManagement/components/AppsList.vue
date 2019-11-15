<template>
 <!--
    <v-alert icon="mdi-shield-lock-outline" prominent text type="info">
      <strong>Donec quam felis, ultricies.</strong> Sed in libero ut nibh placerat accumsan.. Curabitur blandit mollis lacus. Curabitur blandit mollis lacus.
    </v-alert>-->

  <div class="pa-5">

       <v-card>
 
<v-container>
        <v-row>
            <v-col cols="12">
                <p class="title pl-4 pt-0  text--primary ">
                    APPLICATIONS LIST
              
                </p>
                <!--<v-divider></v-divider>-->
                
                
                <v-row class="">

                   
                     

                    <template v-if="appInd">
                     <v-col cols="4" v-for="item in apps" v-bind:data="item"
                        v-bind:key="item.name">

                        <v-card 
                            class="mx-auto"
                            outlined
                        >
                            <v-card-text>
                        
                            <p class="display-1 text--primary">
                                {{ item.name }}
                            </p>
                            <p>{{ item.app_description }}</p>

                            
                            </v-card-text>
                            <v-card-actions>

                                <v-btn depressed small @click="selectApp(item)">Edit Collections</v-btn>
                                <v-btn depressed small @click="selectApp(item)">Content Manager</v-btn>
                        
                            </v-card-actions>
                        </v-card>
                    
                    </v-col>
                    </template>
                    <template v-else>
                        <v-col cols="12">
                            <v-alert icon="view_carousel" prominent text type="info">
                                <strong>You don't have applications created yet,</strong> to create applications click <v-btn  to="/app/create"  depressed small>Here</v-btn>
                            </v-alert>
                         </v-col>

                    </template>
                     








                </v-row>
            </v-col>
        </v-row>
</v-container>


            <v-btn
              absolute
              dark
              fab
              bottom
              right
              color="deep-purple lighten-3"
              to="/app/create"
            >
              <v-icon>mdi-plus</v-icon>
            </v-btn>



  </v-card>

   

    
    
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
            existInd: true,
                    
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
            
        },
        watch: {
          
        },
        computed: {
          ...mapState([
              'apps',
              'appInd'
          ]),

        }
    }
</script>