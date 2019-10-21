<template>


  <div class="pa-5">

    <!--
    <v-alert icon="mdi-shield-lock-outline" prominent text type="info">
      <strong>Donec quam felis, ultricies.</strong> Sed in libero ut nibh placerat accumsan.. Curabitur blandit mollis lacus. Curabitur blandit mollis lacus.
    </v-alert>-->
    <v-row class="">
      <v-col md="12">
        <v-card class="pa-3" outlined tile>
          
          <v-card-text>
            <!--<div>Word of the Day</div>-->
            <p class="title text--primary ">
              CREATE NEW APPLICATION
              
            </p>

            <v-text-field
                v-model="app.name"
                :counter="10"
                label="Name"
                required
            ></v-text-field>

            <v-textarea
                v-model="app.descrip"
                name="input-7-1"
                label="Description"
                value="The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through."
                ></v-textarea>

            <v-select
                v-model="app.users"
                :items="states"
                label="Select Users"
                multiple
                chips
                hint="Select the users to access this application"
                persistent-hint
                ></v-select>

            
            
            <v-switch
              v-model="app.public"
              color="success"
              :label="`Public`"
            ></v-switch>

            <v-switch
              v-model="app.security.active"
              color="red"
              :label="`Security`"
            ></v-switch>

            <template v-if="app.security.active == true">
              <p class="">
                <strong>Token:</strong> <br />
                {{ app.security.token }}
                
              </p>
              <p class="">
                <v-btn
                    @click="refreshToken"
                    text
                    color="red"
                  >
                    Refresh
                </v-btn>
              </p>
            </template>

             <p class="text-center">
                   
                   <v-btn class="ma-2" tile outlined color="success" @click.prevent="createApp">
                        Create and Save Application
                    </v-btn>

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
               tables: [],
               users: []
            },
            
          }
        },
        methods: {
         
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

            // Create App
            axios.post('/createApp', {
                data: this.app,
            })
            .then(function (res) {
                
                if(res.data.success){

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