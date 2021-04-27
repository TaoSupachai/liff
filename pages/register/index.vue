<template>
  <div>
    <v-app-bar flat>
      <v-toolbar-title>Login</v-toolbar-title>
    </v-app-bar>
    <v-container>
      <v-row justify="center" align="center">
        <v-col cols="12" sm="8" md="6">
          <div class="text-center my-5">
            <img src="~/assets/logo.png" alt="" width="200px" />
          </div>
        </v-col>
      </v-row>
      <v-row justify="center" align="center">
        <v-col cols="12" sm="8" md="6">
          <form autocomplete="off">
            <v-text-field
              v-model="phone"
              outlined
              dense
              :rules="phoneRules"
              @keypress="onlyNumber($event, 10)"
              label="Phone"
            ></v-text-field>
            <div class="text-center">
              <v-btn
                rounded
                class="w-100 mt-8"
                large
                color="primary"
                @click="submit"
              >
                Login
              </v-btn>
              <!-- <v-btn @click="clear"> clear </v-btn> -->
            </div>
          </form>
        </v-col>
      </v-row>
    </v-container>
    <v-dialog v-model="dialog" persistent max-width="290">
      <v-card>
        <v-card-title></v-card-title>
        <v-card-text v-html="dialogMsg"></v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="dialog = false">
            Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
const REGEX_PHONE = /^[0]([0-9]{9})*$/;
const REGEX_NUMBER = /^[0-9]*$/;
export default {
  mounted(){
    liff.init({
      liffId: '1655755694-EJPdZr1P'
    }).then(() => {
      if(liff.isLoggedIn()){
        liff.getProfile().then(profile => {
          this.$store.dispatch('setLine', profile);
          this.isDone();
        })
      }else{
        liff.login();
      }
    })
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
    };
  },
  methods: {
    isDone() {
      this.$axios
        .get(
          `https://cropliff-default-rtdb.firebaseio.com/members/${this.$store.getters.getLine.userId}/profile.json`
        )
        .then((res) => {
          if (res.data != null) {
            this.$router.push("/register/done");
          }
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
    submit() {
      if (this.phoneValidated) {
        const Data = new FormData();
        Data.append("tel", this.phone);
        this.$axios
          .post(`https://line.cpcrop.com/api/fetchEmp.php`, Data)
          .then((response) => {
            if (response.data.status) {
              this.$store.dispatch("setEmpData", response.data.data);

              this.dialog = true;
              this.dialogMsg = "ระบบได้ทำการส่งรหัส OTP ไปยังโทรศัพท์ของท่านแล้ว";

              this.$axios
              .post("https://line.cpcrop.com/api/otpSend.php", Data)
              .then((response) => {
                this.$store.dispatch("setOtpresponse", {
                  otpresponse: response.data.otpresponse,
                  tel: this.phone,
                });
                this.$router.push("/register/otpstep");
              })
              .catch((error) => {
                console.log("error otpSend ==> ", error);
                this.dialog = true;
                this.dialogMsg = "ไม่สามารถส่ง OTP ได้";
              });
            } else {
              this.dialog = true;
              this.dialogMsg = response.data.data;
            }            
          })
          .catch((err) => {
            console.error(err);
            this.dialog = true;
            this.dialogMsg = "API ตรวจสอบ Phonenumber ผิดพลาด";
          });
      }
    },
    handleClearInput() {
      this.$refs.otpInput.clearInput();
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


