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
              {{ app.name }}
               
            </p>
            <div class="text--primary">
              {{ app.descrip }}            
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

            <div class="text-center">
             <v-btn
              color="green accent-4"
              class="ma-2" tile outlined
              @click="saveData"
            >
              SAVE CONFIGURATION
            </v-btn>
            </div>

          </v-card-text>
          <v-card-actions>
           
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
                          <template v-if="table.new">
                            <v-text-field
                                v-model="table.name"
                                label="Table Name *new"
                                placeholder="Placeholder"
                                class="mb-0 pa-0 "
                                outlined
                                dense
                                hide-details
                              ></v-text-field>
                          </template>
                          <template v-else>
                            <v-text-field
                                v-model="table.name"
                                label="Table Name *old"
                                placeholder="Placeholder"
                                class="mb-0 pa-0"
                                outlined
                                dense
                                hide-details
                              ></v-text-field>
                          </template>
                          
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
                             Drop Table
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


                              
                              <v-select
                                v-model="item.type"
                                :items="fieldTypes"
                                item-value="type"
                                label="Solo field"
                                dense
                                solo
                                hide-details
                              ></v-select>

                              <v-btn text icon color="green">
                                <v-icon>call_split</v-icon>
                              </v-btn>
                              


                            </v-list-item-icon>


                            <v-list-item-content>
                           
                                  <v-col cols="12" sm="12" md="10" class="pa-0">
                                     <template v-if="item.new">
                                      <v-text-field 
                                      
                                          v-model="item.name"
                                          :rules="nameRules"
                                          md="6"
                                          label="Field Name *new"
                                          class="ma-0 pa-0"
                                          required
                                          dense 
                                          hide-details
                                        ></v-text-field>
                                     </template>
                                     <templat v-else>
                                         <v-text-field 
                                      
                                          v-model="item.name"
                                          :rules="nameRules"
                                          md="6"
                                          label="Field Name *old"
                                          class="ma-0 pa-0"
                                          required
                                          dense 
                                          hide-details
                                        ></v-text-field>
                                     </templat>
                                  </v-col>

                                  <v-col cols="12" sm="12" md="2" class="pa-0">
                                     <p class="text-right pb-0">

                                       <v-btn-toggle
                                          v-model="icon"
                                          borderless
                                        >
                                          <v-btn  small value="left" color="red lighten-4" @click="removeField(i, e)">
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
        props: {
          id: Number,
        },
        data : function(){
          return {
            menu: false,
            switch1: true,
            droppedTables: [],
            newTables: [],
            changedTables: [],
            droppedFields: [],
            renamedTables: [],
            renamedFields: [],
            newFields: [],
            appInfo: [],
            beforeApp: {

            },
            app: {
               name: "Mi Primera App",
               active: true,
               security: {
                 active: false,
                 token: 'HIoidw98sw5548hgy7gouiiwqhUHGFUYhsd',
               },
               tables: [
                 
                  {
                    name: 'Productos',
                    fields: [
                      {
                        type: 'string',
                        name: 'Nombre',
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
                  },
                  {
                    name: 'Clientes',
                    fields: [
                      {
                        type: 'string',
                        name: 'Nombre',
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
              { text: 'Date Time', type: 'dateTime', icon: 'date_range' },
              { text: 'Relations', type: 'bigInteger', icon: 'call_split' },
            ],
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
                // Storage configuration
                self.beforeApp = JSON.parse(JSON.stringify(res.data));

            })
            .catch(function (error) {
                console.log(error);
            });

           

            // Get app information
            axios.post('/app/getInfoById', {
                id: this.id,
            })
            .then(function (res) {
                
                self.appInfo = res.data;

            })
            .catch(function (error) {
                console.log(error);
            });



          },
          addTable(){
            //Prepare Obj
            let obj = {
                  new: true,
                  old_name: '',
                  name: '',
                  fields: [
                    {
                      new: true,
                      old_name: '',
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

            if(!this.app.tables[i].new){
              // Storage dropped tables
              this.droppedTables.push(this.app.tables[i]);
            }

            // Drop table of mean object
            this.app.tables.splice(i, 1) 
            this.menu = false

          },
          addField(i){
            let obj = {
                        new: true, 
                        old_name: '',
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
                if(!this.app.tables[t].fields[f].new){
                   this.droppedFields.push(
                     {
                       tableName: this.app.tables[t].name,
                       field: JSON.parse(JSON.stringify(this.app.tables[t].fields[f]))
                     }
                   );
                }
               
                this.app.tables[t].fields.splice(f, 1) 
                this.menu2 = false;


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
          },
          saveData(){

            var self = this;  
            // Get Renamed Tables
            for (var i = 0; i < self.app.tables.length; i++) {
              if(!self.app.tables[i].new){
                if(self.app.tables[i].name != self.app.tables[i].old_name){
                    self.renamedTables.push({
                        oldName: self.app.tables[i].old_name,
                        newName: self.app.tables[i].name
                    });
                }
              }
            }

            // Get Renamed Fields
            for (var i = 0; i < self.app.tables.length; i++) {
              if(!self.app.tables[i].new){

                for (var e = 0; e < self.app.tables[i].fields.length; e++) {
                  if(self.app.tables[i].fields[e].name != self.app.tables[i].fields[e].old_name){
                    self.renamedFields.push({
                        tableName : self.app.tables[i].name,
                        oldName   : self.app.tables[i].fields[e].old_name,
                        newName   : self.app.tables[i].fields[e].name
                    });
                  }
                }

              }
            }

            // // Get Modified Fields Types
            // for (var i = 0; i < self.app.tables.length; i++) {
            //   if(!self.app.tables[i].new){

            //     for (var e = 0; e < self.app.tables[i].fields.length; e++) {
            //       if(self.app.tables[i].fields[e].name != self.app.tables[i].fields[e].old_name){
            //         self.renamedFields.push({
            //             tablename : self.app.tables[i].name,
            //             oldName   : self.app.tables[i].fields[e].old_name,
            //             newName   : self.app.tables[i].fields[e].name
            //         });
            //       }
            //     }

            //   }
            // }

            // Get new tables to create 
            for (var i = 0; i < self.app.tables.length; i++) {
              if(self.app.tables[i].new){
                self.newTables.push(JSON.parse(JSON.stringify(self.app.tables[i])));
              }
            }

            // Get changed tables
            for (var i = 0; i < self.app.tables.length; i++) {
              if(!self.app.tables[i].new){
                //alert(self.app.tables[i].new);
                self.changedTables.push(JSON.parse(JSON.stringify(self.app.tables[i])));
              }
            }

            console.log(self.renamedTables);

           
            
            var json = JSON.parse(JSON.stringify(self.app));
            //json.push(self.app);

            

            //Formatted json to storage as structure column
            for (var i = 0; i < json.tables.length; i++) {
                  json.tables[i].new = false;
                  json.tables[i].old_name = json.tables[i].name;

                  for (var e = 0; e < json.tables[i].fields.length; e++) {
                    json.tables[i].fields[e].new = false;
                    json.tables[i].fields[e].old_name = json.tables[i].fields[e].name ;
                  }

            }

            console.log(json)
            console.log(self.app)


            // Create App
            axios.post('/testing', {
                data: self.appInfo,
                app: self.app,
                beforeApp: self.beforeApp,
                json: json,
                renamedTables: self.renamedTables,
                droppedTables: self.droppedTables,
                newTables: self.newTables,
                changedTables: self.changedTables,
                renamedFields: self.renamedFields,
                droppedFields: self.droppedFields,
                //newFields: self.newFields,
                id: self.id
            })
            .then(function (res) {

               self.getApp();
                
                if(res.data.success){

                }

            })
            .catch(function (error) {
                console.log(error);
            });
            

          }
        },
        mounted() {

            //const self = this;

            //self.getApp();

        },
        created(){

          this.getApp();

          

          

        }
    }
</script>