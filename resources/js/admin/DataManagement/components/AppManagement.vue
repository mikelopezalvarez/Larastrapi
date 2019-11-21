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
              {{ appInfo.name }}

              {{ id }}
               
            </p>
            <div class="text--primary">
              {{ appInfo.app_description }}            
            </div>
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
            

            <v-btn depressed @click.prevent="saveConfirm">Save Configuration</v-btn>
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
                <v-subheader class="pb-md-4">
                  
                  <v-btn  small value="left" color="green lighten-4" @click="addTable">
                    <span class="hidden-sm-and-down">Add Collection</span>
                    <v-icon right>grid_on</v-icon>
                  </v-btn>
                  <v-btn v-if="app.tables.length > 1" small value="left" color="blue lighten-4" @click="relationDialog = true">
                    <span class="hidden-sm-and-down">Add Relationship</span>
                    <v-icon right>call_split</v-icon>
                  </v-btn>
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
                                label="Collection Name *new"
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
                                label="Collection Name *old"
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
                         <v-btn small
                              color="blue lighten-4"
                              v-on="on"
                              borderless
                              @click="getApiTable(i)"
                              
                            >
                             API
                             <v-icon right>link</v-icon> 
                            </v-btn>

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


    <v-dialog
      v-model="getApiDialog"
      width="700"
    >
      <template v-slot:activator="{ on }">
       
      </template>

      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          API - {{txtApiTable}}
        </v-card-title>

        <v-card-text>

          <v-text-field
            class="pt-4"
            label="Get"
            v-model="txtApiGet"
            readonly
          ></v-text-field>

          <v-text-field
            label="Find"
            v-model="txtApiFind"
            readonly
          ></v-text-field>


         <v-text-field
            label="Create"
            v-model="txtApiCreate"
            readonly
          ></v-text-field>

         <v-text-field
         v-model="txtApiUpdate"
         readonly
            label="Update"
          ></v-text-field>

          <v-text-field
            label="Delete"
            v-model="txtApiDelete"
            readonly
          ></v-text-field>

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="getApiDialog = false"
          >
            Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>







    <v-dialog v-model="relationDialog" persistent max-width="900px">
      <!-- <template v-slot:activator="{ on }">
        <v-btn color="primary" dark v-on="on">Open Dialog</v-btn>
      </template> --> 
      <v-card>
        <v-card-title>
          <span class="headline">Collection Relationship</span>
        </v-card-title>
        <v-card-text>
          
          <v-container>
            <v-row>
             
             
             
              <v-col cols="12" sm="3">
                 <v-autocomplete
                  :items="app.tables"
                  item-value="name"
                  item-text="name"
                  label="Choose the Collection"
                  dense 
                  hide-details
                  v-model="table1"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" sm="2">
                 <v-autocomplete
                  :items="relationTypeItems"
                  item-value="value"
                  item-text="text"
                  label="Type"
                  dense 
                  hide-details
                  v-model="relationship"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" sm="3">
                 <v-autocomplete
                  :items="app.tables"
                  item-value="name"
                  item-text="name"
                  label="Choose the Collection"
                  dense 
                  hide-details
                  v-model="table2"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" sm="2">
                 <v-autocomplete
                  :items="fields"
                  label="Field"
                  dense 
                  hide-details
                  v-model="field"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" sm="2">
                <v-btn @click="addRelation" value="left" color="green lighten-4" style="width: 100%;">
                    <span class="hidden-sm-and-down">Add</span>
                </v-btn>
              </v-col>
             
            </v-row>

            <v-divider></v-divider>	

            <template v-if="app.relations.length > 0">
              <v-row>
                <v-col cols="12" sm="12">
                  <v-simple-table>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-left">Table 1</th>
                          <th class="text-left">Type</th>
                          <th class="text-left">Table 2</th>
                          <th class="text-left">Field</th>
                          <th class="text-left"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="item in app.relations" :key="item.table1">
                          <td width="20%">{{ item.table1 }}</td>
                          <td v-if="item.relationship == 1" width="30%">One to One</td>
                          <td v-if="item.relationship == 2" width="30%">One to Many</td>
                          <td width="20%">{{ item.table2 }}</td>
                          <td width="20%">{{ item.field }}</td>
                          <td width="10%">
                            <v-btn @click="removeRelation(item)" value="left" color="red lighten-4">
                                <span class="hidden-sm-and-down">Remove</span>
                            </v-btn>
                          </td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-col>
              </v-row>
            </template>





          </v-container>
          
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="relationDialog = false">Close</v-btn>
          <!-- <v-btn color="blue darken-1" text @click="relationDialog = false">Save</v-btn> -->
        </v-card-actions>
      </v-card>
    </v-dialog>
    
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
    

    export default {

        name : 'app_management',
        data : function(){
          return {
            id: null,
            appId: null,
            icon: '',
            menu: false,
            getApiDialog: false,
            switch1: true,
            txtApiTable: '',
            txtApiGet: '',
            txtApiFind: '',
            txtApiCreate: '',
            txtApiUpdate: '',
            txtApiDelete: '',
            droppedTables: [],
            newTables: [],
            changedTables: [],
            droppedFields: [],
            renamedTables: [],
            renamedFields: [],
            newFields: [],
            appInfo: [],
            relationDialog: false,
            table1:'',
            relationship: '',
            table2: '',
            fields: [],
            field: '',
            relationTypeItems: [
              { text: 'One to One', value: 1 },
              { text: 'One to Many', value: 2 },
            ],
            beforeApp: {

            },
            app: {
               name: "",
               active: true,
               security: {
                 active: false,
                 token: 'HIoidw98sw5548hgy7gouiiwqhUHGFUYhsd',
               },
               relations: [],
               tables: []
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
              { text: 'Relation', type: 'relation', icon: 'call_split' },
            ],
          }
        },
        methods: {

          getApp(){

            var self = this;  
            // Get application object
            axios.post('/app/getObjectById', {
                id: self.appId,
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
                id: self.appId,
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
                  name: 'New Collection',
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

          saveConfirm(){
             var self = this;  

              self.$swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4caf50',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
              }).then((result) => {
                if (result.value) {
                  self.saveData();
                }
              })
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
                id: self.appId
            })
            .then(function (res) {

               self.getApp();
                
                if(res.data.success){

                }

            })
            .catch(function (error) {
                console.log(error);
            });
            

          },


          addRelation(){

            var self = this;  

            var err = 0;

            //No empty fields
            if(self.table1 != "" || self.table2 != "" || self.relationship != "" || self.field != ""){
              //No same collections
              if(self.table1 != self.table2){

                var qty = self.app.relations.length;
                if(qty > 0){
                  for (var i = 0; i < qty; i++){
                    if(self.app.relations[i].table1 == self.table1 && self.app.relations[i].table2 == self.table2 && self.app.relations[i].relationship == self.relationship){
                      err = err+1;
                    }
                  }
                }
                console.log(qty)
                if(err < 1){
                  //populat object
                  let obj = {
                    table1: self.table1,
                    relationship: self.relationship,
                    table2: self.table2,
                    field: self.field
                  };

                  //Pushing object
                  self.app.relations.push(obj);

                  //Empty fields
                  self.table1 = '';
                  self.relationship = '';
                  self.table2 = '';
                  self.field = '';
                }
              }else{
                alert("Don't must the same collections relations");
              }
            }else{
              alert("All fields are required");
            }

          },

          removeRelation(i){
            
            this.app.relations.splice(i, 1) 

          },

          getApiTable(i){
            
            this.getApiDialog = true;

            var link = '/api/' + this.appInfo.alias + '/' + this.app.tables[i].name + '/';

            this.txtApiTable = this.app.tables[i].name;

            this.txtApiGet = link + 'get';
            this.txtApiFind = link + 'find';
            this.txtApiCreate = link + 'create';
            this.txtApiUpdate = link + 'update';
            this.txtApiDelete = link + 'delete';

          },

          getSessionApp(){
            
            const self = this;
            // Fetch all apps
            axios.get('/general/getApp', {
                data: '',
            })
            .then(function (res) {
               
               if(res.data.success){
                    self.appId = res.data.id;
                    self.getApp();
               }else{
                    self.$router.push({name: "apps_list"});
               }

            })
            .catch(function (error) {
                console.log(error);
            });

          }, 
          populateRelationFieldDropdown(val){
            const self = this;
            var table;
            //Empty fields
            self.fields = [];

            //Find the table
            $.each(self.app.tables, function(key, value) {

              if(value["name"] == val){
                table = value;
              }

            });


            //Populate the fields
            $.each(table.fields, function(key, value) {
              
              if(value["type"] == 'integer'){
                self.fields.push(value["name"]);
              }
            
            });

            // if(self.fields){
            //   alert("The table don't has a integer field");
            // }

          }
         
        },

        watch: {
          table2: function(val){
            this.populateRelationFieldDropdown(val);
          },

          relationDialog: function(val){
            if(!val){
              this.table1 = '';
              this.relationship = '';
              this.table2 = '';
              this.field = '';
            }
          }
            // id: function () {
            //     this.getApp();
            // },
        },
        mounted() {

           const self = this;
            
            //this.id = this.appSelected;
           // this.getSessionApp();

          // self.id = self.$router.params;

          //console.log(this.$route.params.id)
          //this.appId = this.$route.params.id;

          //this.getApp();

          this.getSessionApp();


          

        },
        created(){

          //alert(this.id)

        

        },
        computed: {
          // ...mapState([
          //     'appSelected'
          // ]),

      }
    }
</script>