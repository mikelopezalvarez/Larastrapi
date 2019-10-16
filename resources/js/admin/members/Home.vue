<template>


  <div class="pa-5">

    <!--
    <v-alert icon="mdi-shield-lock-outline" prominent text type="info">
      <strong>Donec quam felis, ultricies.</strong> Sed in libero ut nibh placerat accumsan.. Curabitur blandit mollis lacus. Curabitur blandit mollis lacus.
    </v-alert>-->
    <v-row class="">
      <v-col md="3">
        <v-card class="pa-3" outlined tile>
          
          <v-card-text>
            <!--<div>Word of the Day</div>-->
            <p class="display-1 text--primary">
              My First App
            </p>
            <div class="text--primary">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit, quam. Nisi dolor exercitationem nostrum temporibus, repellat earum rerum, veritatis provident voluptatibus ad ea, delectus maiores non et corrupti unde recusandae?
            </div>
            <p>Status</p>
            <v-switch
              v-model="app.active"
              color="success"
              :label="`Public`"
            ></v-switch>

            <v-switch
              v-model="app.security.active"
              color="red"
              :label="`Security`"
            ></v-switch>

            <template v-if="app.security.active == true">
              <p class="text-center">
                <strong>Token:</strong> <br />
                {{ app.security.token }}
                
              </p>
              <p class="text-center">
                <v-btn
                    @click="refreshToken"
                    text
                    color="red"
                  >
                    Refresh
                  </v-btn>
              </p>
            </template>

          </v-card-text>
          <v-card-actions>
            <v-btn
              text
              color="deep-purple accent-4"
            >
              Duplicate Structure
            </v-btn>
          </v-card-actions>



        </v-card>
      </v-col>
      <v-col md="9">
        <v-card class="pa-3" outlined tile>
         
         





          <v-container fluid>
              <v-row justify="center">
                <v-subheader><v-btn-toggle
                          class="pb-4"
                          v-model="icon"
                          borderless
                        >
                          <v-btn  small value="left" color="green lighten-4" @click="addTable">
                            <span class="hidden-sm-and-down">Add Table</span>

                            <v-icon right>grid_on</v-icon>
                          </v-btn>

                        
                         
                        </v-btn-toggle>
</v-subheader>

                <v-expansion-panels popout>
                  <v-expansion-panel
                    v-for="(table, i) in app.tables"
                    :key="i"
                    hide-actions
                  >
                    <v-expansion-panel-header>
                      <v-row align="center" class="spacer" no-gutters>
                        
                        <v-col cols="4" sm="2"  md="1">
                          <v-avatar size="36px" >
                            <v-icon color="blue">grid_on</v-icon>
                          </v-avatar>
                        </v-col>

                        <v-col class="mb-0 pa-0 hidden-xs-only" sm="5" md="3">
                         <v-text-field
                            v-model="table.name"
                            label="Table Name"
                            placeholder="Placeholder"
                            class="mb-0 pa-0"
                            outlined
                            dense
                          ></v-text-field>
                          
                        </v-col>

                        <v-col class="text-no-wrap" cols="5" sm="3">

                       
                        </v-col>

                        
                      </v-row>
                    </v-expansion-panel-header>

                    <v-expansion-panel-content>
                      <v-divider></v-divider>


                       

                       <p class="text-right pt-4 pb-0">


                         <v-menu 
                          v-model="menu"
                          :close-on-content-click="false"
                          :nudge-width="200"
                          offset-x
                        >
                          <template v-slot:activator="{ on }">
                            <v-btn small
                              color="red lighten-4"
                              v-on="on"
                              borderless
                              
                            >
                             Remove Table
                             <v-icon right>clear</v-icon> 
                            </v-btn>
                          </template>

                          <v-card>
                            <v-card-actions>
                              <v-spacer></v-spacer>
                              <p>Do you want delete this table?</p>
                              <v-divider></v-divider>
                              <v-btn text @click="menu = false">Cancel</v-btn>
                              <v-btn color="red" text @click="removeTable(i)">Yes</v-btn>
                            </v-card-actions>
                          </v-card>

                        </v-menu>

                            

                        </p>
                        

                      



                      <!-- Listing Fields -->
                      <v-list shaped>
                        <v-subheader>Fields</v-subheader>
                        <v-list-item-group v-model="item" color="primary">
                          <v-list-item
                            v-for="(item, e) in table.fields"
                            :key="i"
                          >
                          
                            <v-list-item-icon>


                              

                              <v-menu
                                bottom
                                origin="center center"
                                transition="scale-transition"
                              >
                                <template v-slot:activator="{ on }">
                                  <v-btn
                                    color="primary"
                                    dark
                                    v-on="on"
                                  >
                                  <v-icon v-text="item.icon"></v-icon>
                                  </v-btn>
                                </template>

                                <v-list>
                                  <v-list-item
                                    v-for="(item, u) in fieldTypes"
                                    :key="i"
                                    @click=""
                                  >
                                    <v-list-item-title><v-icon v-text="item.icon"></v-icon></v-list-item-title>
                                  </v-list-item>
                                </v-list>
                              </v-menu>

                              <v-btn text icon color="green">
                                <v-icon>call_split</v-icon>
                              </v-btn>
                              


                            </v-list-item-icon>


                            <v-list-item-content>
                           
                                  <v-col cols="12" sm="12" md="10" class="pa-0">
                                  <v-text-field 
                                  
                                      v-model="firstname"
                                      :rules="nameRules"
                                      md="6"
                                      label="Field Name"
                                      class="ma-0 pa-0"
                                      required
                                    ></v-text-field>
                                  </v-col>

                                  <v-col cols="12" sm="12" md="2" class="pa-0">
                                     <p class="text-right pb-0">

                                       <v-btn-toggle
                                          v-model="icon"
                                          borderless
                                        >
                                          <v-btn  small value="left" color="red lighten-4" @click="removeField(i, u)">
                                            <span class="hidden-sm-and-down">Remove</span>

                                            <v-icon right>clear</v-icon> 
                                          </v-btn>
                                        </v-btn-toggle>
                                      </p>
                        
                                  </v-col>

                                    

                                    

                                


                            </v-list-item-content>
                          </v-list-item>
                        </v-list-item-group>
                      </v-list>

                       <p class="text-left">
                      <v-btn-toggle
                          class="pt-4"
                          v-model="icon"
                          borderless
                        >
                          <v-btn  small value="left" color="green lighten-4" @click="addField(i)">
                            <span class="hidden-sm-and-down">Add Field</span>

                            <v-icon right>playlist_add</v-icon> 
                          </v-btn>

                        
                         
                        </v-btn-toggle>
                       </p>

                       







                    </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-row>
            </v-container>














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
            menu: false,
            switch1: true,
             app: {
               active: true,
               security: {
                 active: false,
                 token: 'HIoidw98sw5548hgy7gouiiwqhUHGFUYhsd',
               },
               tables: [
                  {
                    name: '',
                    fields: [
                      {
                        type: 'string',
                        name: '',
                        relation: {
                          active: false,
                          table: '',
                          field: '',
                          type: '',
                        },
                        icon: 'text_format',

                      }
                    ],
                    users: [],
                    active: true,
                    options: {
                      get: true,
                      add: true,
                      del: true,
                      upd: true
                    }
                  }
               ]
             },
             messages: [
              {
                color: 'blue',
                icon: 'grid_on',
                name: 'Social',
                new: 1,
                total: 3,
                title: 'Twitter',
              },
              {
                color: 'blue',
                icon: 'grid_on',
                name: 'Social',
                new: 1,
                total: 3,
                title: 'Twitter',
              },
              {
                color: 'blue',
                icon: 'grid_on',
                name: 'Promos',
                new: 2,
                total: 4,
                title: 'Shop your way',
                exceprt: 'New deals available, Join Today',
              },
            ],
            lorem: 'Lorem ipsum dolor sit amet, at aliquam vivendum vel, everti delicatissimi cu eos. Dico iuvaret debitis mel an, et cum zril menandri. Eum in consul legimus accusam. Ea dico abhorreant duo, quo illum minimum incorrupte no, nostro voluptaria sea eu. Suas eligendi ius at, at nemore equidem est. Sed in error hendrerit, in consul constituam cum.',
            item: 1,
            items: [
              { text: 'String', icon: 'text_format' },
            ],
            fieldTypes: [
              { text: 'String', type: 'string', icon: 'text_format' },
              { text: 'Integer', type: 'integer', icon: 'looks_one' },
              { text: 'Double', type: 'double', icon: 'attach_money' },
              { text: 'Image', type: 'string', icon: 'image' },
              { text: 'File', type: 'string', icon: 'insert_drive_file' },
              { text: 'Date Time', type: 'datetime', icon: 'date_range' },
              { text: 'Relations', type: 'integer', icon: 'call_split' },
            ],
          }
        },
        methods: {
          addTable(){
            //Prepare Obj
            let obj = {
                  name: '',
                  fields: [
                    {
                      type: 'string',
                      name: '',
                      relation: {
                        active: false,
                        table: '',
                        field: '',
                        type: '',
                      },
                      icon: 'text_format',

                    }
                  ],
                  users: [],
                  active: true,
                  options: {
                    get: true,
                    add: true,
                    del: true,
                    upd: true
                  }
            };
            
            //Push Obj
            this.app.tables.push(obj);
          },

          removeTable(i){
            this.app.tables.splice(i, 1) 
            this.menu = false

          },
          addField(i){
            let obj = {
                        type: 'string',
                        name: '',
                        relation: {
                          active: false,
                          table: '',
                          field: '',
                          type: '',
                        },
                        icon: 'text_format',

                      };

            this.app.tables[i].fields.push(obj)
          },
           removeField(t, f){
            if(this.app.tables[t].fields.length > 1){
                this.app.tables[t].fields.splice(f, 1) 
                this.menu2 = false
            }

          },
          refreshToken(){
            let length = 32;
            let result           = '';
            let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            this.app.security.token = result;
          }
        },
        mounted() {

            const self = this;

            self.$store.commit('setBreadcrumbs',[
                {label:'Dashboard',name:''}
            ]);
        }
    }
</script>