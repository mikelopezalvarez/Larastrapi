<template>
  <!--
    <v-alert icon="mdi-shield-lock-outline" prominent text type="info">
      <strong>Donec quam felis, ultricies.</strong> Sed in libero ut nibh placerat accumsan.. Curabitur blandit mollis lacus. Curabitur blandit mollis lacus.
  </v-alert>-->

  <div class="py-3 px-5">
    <v-card flat color="transparent">
        <v-row>
          <v-col cols="12">
            <h3 class="mb-5 font-weight-light pt-0 grey--text text--darken-2">My Apps</h3>
            <!--<v-divider></v-divider>-->

            <v-row class>
              <template v-if="appInd">
                <v-col cols="3" v-for="item in apps" v-bind:data="item" v-bind:key="item.name">
                  <v-card class="" height="200px">
                    <v-card-text>
                      <p class="title text--primary">{{ item.name }}</p>
                      <p class="app-card--description">{{ item.app_description }}</p>
                    </v-card-text>
                    <v-card-actions>
                      <!-- <v-btn depressed small @click="selectApp(item)" class="blue--text text--lighten-1"><v-icon>mdi-database-edit</v-icon></v-btn> -->
                      <!-- <v-btn depressed small @click="selectApp(item)" class="deep-purple--text text--lighten-1"><v-icon>mdi-table-plus</v-icon></v-btn> -->

                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn depressed small v-on="on" @click="selectApp(item)" class="blue--text text--lighten-1"><v-icon>mdi-database-edit</v-icon></v-btn>
                        </template>
                        <span>Edit Collection</span>
                      </v-tooltip>
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn depressed small v-on="on" @click="selectApp(item)" class="deep-purple--text text--lighten-1 ml-3"><v-icon>mdi-table-plus</v-icon></v-btn>
                        </template>
                        <span>Content Manager</span>
                      </v-tooltip>
                    </v-card-actions>
                  </v-card>
                </v-col>
              </template>
              <template v-else>
                <v-col cols="12">
                  <v-alert icon="view_carousel" prominent text type="info">
                    <strong>You don't have applications created yet,</strong> to create applications click
                    <v-btn to="/app/create" depressed small>Here</v-btn>
                  </v-alert>
                </v-col>
              </template>
            </v-row>
          </v-col>
        </v-row>
        
      <!-- <v-btn absolute dark fab bottom right class="hexagon" color="deep-purple lighten-3" to="/app/create">
        <v-icon>mdi-plus</v-icon>
      </v-btn> -->
    </v-card>
    <router-link to="/app/create">
      <div class="hexagon-container">
        <v-icon class="custom-add">mdi-plus</v-icon>
        <div class="hex"></div>
      </div>
    </router-link>
    
    
  </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";
export default {
  // inject: ['theme'],
  data: function() {
    return {
      name: "",
      //apps: [],
      existInd: true
    };
  },
  methods: {
    //Mutations represent methods or functions
    ...mapMutations(["getApps"]),

    selectApp(i) {
      const self = this;
      // Set App
      axios
        .post("/general/setApp", {
          id: i.id
        })
        .then(function(res) {
          self.$router.push({ name: "app_management" });
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  },
  mounted() {
    const self = this;

    self.getApps();
  },
  created() {},
  watch: {},
  computed: {
    ...mapState(["apps", "appInd"])
  }
};
</script>