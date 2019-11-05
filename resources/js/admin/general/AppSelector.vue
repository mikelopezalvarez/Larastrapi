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
            @change="changeApp"
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
            change: 0,
            selected: '',
            apps: [],
              
            
          }
        },
        methods: {
            // //Mutations represent methods or functions
            // ...mapMutations([
            //     'setApp',
            // ]),
        
          getApps(){
            const self = this;
            // Fetch all apps
            axios.get('/app/get', {
                data: '',
            })
            .then(function (res) {

                self.apps = res.data;

              
                
               // self.selectApp();


            })
            .catch(function (error) {
                console.log(error);
            });
          },

          selectApp(){

                const self = this;
                $.each(self.apps, function(key, value) {
                    if(value.id == self.$route.params.id){
                        self.selected = {
                            name: value.name,
                            id: value.id
                        };
                    }
                });
              
          },

          setApp(id){

            const self = this;
            // Set App
            axios.post('/general/setApp', {
                id: id,
            })
            .then(function (res) {
               
               location.reload(true);

            })
            .catch(function (error) {
                console.log(error);
            });

          },
          changeApp(val){

            var self = this;  

              self.$swal({
                title: 'Are you sure you want change the App?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4caf50',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
              }).then((result) => {
                if (result.value) {
                   self.setApp(val);
                }
              })
              

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

              
          },
          getSessionApp(){
            
            const self = this;
            // Fetch all apps
            axios.get('/general/getApp', {
                data: '',
            })
            .then(function (res) {
               
               if(res.data.success){
                    self.selected = res.data.id;
               }

            })
            .catch(function (error) {
                console.log(error);
            });

          }
          
        },
        // watch: {
        //     // Pending to change dropdown app
        //     selected: function (val) {
        //         this.changeApp(val);
        //     },
        // },
        created(){

            const self = this;

            self.getApps();

            //this.selected = this.$route.params.id;


            self.getSessionApp();


        },
        mounted() {

           
           
            //self.getLastModified();

           
        }
    }
</script>