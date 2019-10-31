<template>
    <div>

        <v-select
            v-model="selected"
            :items="apps"
            item-text="name"
            item-value="id"
            label="Select App"
            outlined
            dense
            hide-details
            >
        </v-select>

  
    
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex'
    export default {
        name: 'app-selector',
        data : function(){
          return {
            
            selected: '',
            apps: [],
              
            
          }
        },
        methods: {
            //Mutations represent methods or functions
            ...mapMutations([
                'setApp',
            ]),
        
          getApps(){
            const self = this;
            // Fetch all apps
            axios.get('/app/get', {
                data: '',
            })
            .then(function (res) {

                self.apps = res.data;

            })
            .catch(function (error) {
                console.log(error);
            });
          },

          changeApp(val){
              //console.log(this.$router.currentRoute.fullPath);
              this.setApp(val);
              this.$router.push({name:"redirect", params: { name: 'data_management' } });
              //this.$router.replace(this.$router.currentRoute)

          },
          // Method to get always the last app modified
          getLastModified(){
              const self = this;
            // Fetch all apps
            axios.get('/app/getLastModified', {
                data: '',
            })
            .then(function (res) {
               
               if(res.data){
                    self.setApp(res.data.id);
                    self.selected = res.data.id;
                    this.$router.push({name: "app_management"});
               }else{
                   this.$router.push({name: "create_app"});
               }

            })
            .catch(function (error) {
                console.log(error);
            });

              
          }
          
        },
        watch: {
            // Pending to change dropdown app
            selected: function (val) {
                this.changeApp(val);
            },
        },
        mounted() {

            const self = this;

            self.getApps();

            self.getLastModified();

           
        }
    }
</script>