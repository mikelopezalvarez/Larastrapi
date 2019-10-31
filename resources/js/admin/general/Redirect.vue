<template>
    <div>

     
    
    </div>
</template>

<script>
    export default {
        name: 'redirect',
        data : function(){
          return {
            
            selected: '',
            
              
            
          }
        },
        methods: {
          
          
          redirect(){
             // alert(this.$router.params.id)
              this.$router.push({name: this.$route.params.name});
            //alert();
          }
          
        },
        created(){
            this.redirect();
        },
        mounted() {


            //this.redirect();


           
        }
    }
</script>