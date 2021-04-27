<template>
  <div>
    <v-app-bar flat>
      <v-toolbar-title>Login</v-toolbar-title>
    </v-app-bar>
    <v-container> </v-container>
  </div>
</template>

<script>
const REGEX_PHONE = /^[0]([0-9]{9})*$/;
const REGEX_NUMBER = /^[0-9]*$/;
export default {  
  mounted() {
    liff.init({
      liffId: "1655755694-EJPdZr1P",
    });
    liff.ready.then(() => {
      if (liff.isLoggedIn()) {
        liff.getProfile().then((profile) => {
          this.$store.dispatch("setLine", profile);
          this.isDone();
        });
      } else {
        liff.login();
      }
    });
  },
  data() {
    return {
      phone: "",
      show1: false,
      rules: {
        required: (value) => !!value || "Required.",
      },
      dialog: false,
      dialogMsg: "",
      phoneValidated: false,
      phoneRules: [(value) => this.phoneValidator(value)],
      app: null,
      qparam: null,
    };
  },
  methods: {
    isDone() {
      this.app = this.$route.query.app;
      this.$axios
        .get(
          `https://cropliff-default-rtdb.firebaseio.com/members/${this.$store.getters.getLine.userId}/profile.json`
        )
        .then((res) => {
          if (res.data != null) {
            this.app = this.$route.query.app;
            this.$store.dispatch("setEmpData", res.data);
            this.openApp();
          } else {
            this.$router.push("/register");
          }
        });
    },
    openApp() {
      this.$axios
        .get(
          `https://cropliff-default-rtdb.firebaseio.com/dashboards/${this.app}.json`
        )
        .then((res) => {
          if (res.data != null) {
            switch (res.data.param) {
              case "username":
                this.qparam = this.$store.getters.getEmpData.Username;
                break;
              case "usernameBase64":
                this.qparam = btoa(this.$store.getters.getEmpData.Username);
                break;
              case "iden":
                this.qparam = this.$store.getters.getEmpData.Identification_no;
                break;
              case "idenBase64":
                this.qparam = btoa(
                  this.$store.getters.getEmpData.Identification_no
                );
                break;
            }
            const uri = `${res.data.uri}${this.qparam}`;
            window.location.replace(uri);
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    phoneValidator(value) {
      this.phoneValidated = false;
      if (value == "") {
        return "required";
      }
      if (REGEX_PHONE.test(value) && value.length == 10) {
        this.phoneValidated = true;
        return true;
      }
      return "please input phone number";
    },
    onlyNumber(event, max) {
      if (event.target.value.length == 0) {
        if (event.key != 0) {
          return event.preventDefault();
        }
      } else {
        if (!REGEX_NUMBER.test(event.key) || event.target.value.length == max) {
          return event.preventDefault();
        }
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.v-application .primary {
  background-color: #069c54 !important;
  border-color: #069c54 !important;
}
</style>


