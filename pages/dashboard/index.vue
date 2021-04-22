<template>
  <div>
    <v-app-bar flat>
      <v-toolbar-title>Dashboard</v-toolbar-title>
    </v-app-bar>
    <v-container>
      <v-row justify="center" align="center">
        <v-col
          cols="12"
          sm="8"
          md="6"
          v-for="item in dashboard"
          :key="item.title"
        >
          <v-card class="agenda-card" @click="openApp(item.uri)">
            <v-card-text>
              <v-row>
                <v-col cols="2" class="text-center pt-2 pb-0">
                  <img src="~/assets/logo.png" class="agenda-image" />
                </v-col>
                <v-col cols="8" class="pt-2 pb-0">
                  <v-card-title
                    class="pl-2 pt-0 pb-3 text-primary text-title"
                    >{{ item.title }}</v-card-title
                  >
                  <v-card-subtitle class="pl-2 pt-0 pb-0">{{
                    item.description
                  }}</v-card-subtitle>
                </v-col>
                <v-col cols="2" class="pt-2 pb-0">
                  <v-card-actions>
                    <v-btn icon>
                      <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                  </v-card-actions>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
import dashboard from "~/assets/dashboard.json";
export default {
  asyncData({ params }) {
    return { dashboard };
  },
  methods: {
    openApp(uri) {
      // https://app.hrcpcrop.com/Time/?username=supachai.pue
      const username = this.$store.getters.getEmpData.Username;
      window.location.replace(uri + username);
    },
  },
};
</script>
<style lang="scss" scoped>
.agenda-card {
  color: #4d4d4d;
  .time {
    color: #1a56be;
    font-weight: bold;
  }
  .agenda-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
  }
  & + .agenda-card {
    margin-top: 15px;
  }
  &.card-set {
    border-left: 3px solid #1a56be;
    .v-card__text + .v-card__text {
      padding-top: 0 !important;
    }
  }
  hr {
    width: 100%;
    margin: 15px auto 0;
    height: 1px;
    background: #bdbdbd;
    border: 0;
  }
}
</style>