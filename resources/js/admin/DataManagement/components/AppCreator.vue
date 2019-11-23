<template>


  <div class="pa-5">
    <v-row class="" justify="center">
      <v-col sm="12" md="8">
        <v-card class="pa-3" >
          
          <v-card-text>
            <p class="title text--primary "> CREATE NEW APPLICATION </p>

            <v-text-field v-model="app.name" :counter="10" label="Name" required outlined dense hide-details class="mb-3" ></v-text-field>
            <v-textarea v-model="app.descrip" name="input-7-1" label="Description" value="" outlined dense hide-details class="mb-3" ></v-textarea>
            <v-select v-model="app.users" :items="states" label="Select Users" multiple chips outlined dense class="mb-3" hint="Select the users to access this application" persistent-hint ></v-select>
            <v-switch v-model="app.public" color="success" :label="`Public`" ></v-switch>
            <v-switch v-model="app.security.active" color="red" :label="`Security`" ></v-switch>
            <template v-if="app.security.active == true">
              
              
              <!-- <p class="">
                <strong>Token:</strong> <br />
                {{ app.security.token }}
                
              </p> -->
              <v-text-field 
              v-model="app.security.token" 
              label="Token" outlined dense 
              inverted
              class="mb-3 mt-3" readonly
              append-icon="refresh"
              @click:append="refreshToken"
              ></v-text-field>
              <!-- <p class="">
                <v-btn
                    @click="refreshToken"
                    text
                    color="red"
                  >
                    Refresh
                </v-btn>
              </p> -->
            </template>

             <p class="text-center">
                   
                   <!-- <v-btn class="ma-2" tile outlined color="success" @click.prevent="createApp">
                        Create and Save Application
                    </v-btn> -->
                    <v-btn dark color="deep-purple darken-1" depressed @click.prevent="createApp">Create Application</v-btn>

               </p>

          </v-card-text>
          <v-card-actions>
              
            
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
            
            app: {
               name: '',
               descrip: '',
               public: true, 
               active: true,
               security: {
                 active: false,
                 token: '',
               },
               relations: [],
               tables: [],
               users: []
            },
            
          }
        },
        methods: {

          //Mutations represent methods or functions
            ...mapMutations([
                'setApp',
            ]),
         
          // When click refresh token
          refreshToken(){
            let length = 32;
            let result           = '';
            let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            this.app.security.token = result;
          },
          createApp(){

            const self = this;

            // Create App
            axios.post('/createApp', {
                data: this.app,
            })
            .then(function (res) {
                
                if(res.data.success){

                  self.setApp(res.data.id);
                  //self.$router.go();
                  self.$router.push({name: "apps_list"});

                }

            })
            .catch(function (error) {
                console.log(error);
            });
            

          }
        },
        mounted() {

            const self = this;

            self.refreshToken();
        }
    }
</script>