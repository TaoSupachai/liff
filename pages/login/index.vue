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
              outlined
              v-model="form.username"
              label="Username"
              :rules="[rules.required]"
            ></v-text-field>
            <v-text-field
              outlined
              v-model="form.password"
              :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              :rules="[rules.required]"
              :type="show1 ? 'text' : 'password'"
              name="input-10-1"
              label="Password"
              @click:append="show1 = !show1"
            ></v-text-field>
            <div class="text-center">
              <v-btn
                rounded
                color="primary"
                class="w-100 mt-8"
                large
                dark
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
export default {
  data() {
    return {
      form: {
        username: "",
        password: "",
      },
      show1: false,
      rules: {
        required: (value) => !!value || "Required.",
      },
      dialog: false,
      dialogMsg: "",
    };
  },
  methods: {
    validate() {
      let validated = true;
      const validatorField = ["username", "password"];
      validatorField.forEach((feild) => {
        if (this.form[feild] == "") {
          validated = false;
          this.dialog = true;
          this.dialogMsg = "กรุณากรอกข้อมูลให้ครบถ้วน";
        }
      });
      return validated;
    },
    submit() {
      if (this.validate()) {
        this.$axios
          .post(`https://kpi.cpcrop.com/server/api/auth/login`, {
            Username: this.form.username,
            Password: this.form.password,
          })
          .then((response) => {
            console.log("response: ", response.data.data.employee);
            // do something about response
            this.$store.dispatch("setEmpData", response.data.data.employee);
            
            //sentotp
            this.$axios.post('~/assets/otp/otpSend.php', {tel: this.response.data.data.employee.contact.Phone_Number}).then((response) => {
                        // this.otpresponse = response.data.otpresponse;
                        this.$store.dispatch("setOtpresponse", response.data.otpresponse);
                        this.dialog = true;
                        this.dialogMsg = "ระบบได้ทำการส่งรหัส OTP ไปยังโทรศัพท์ของท่านแล้ว"; 
                    }).catch((error) => {
                        console.log("error ==> ", error);
                    });

            this.$router.push('/otp')

          })
          .catch((err) => {
            console.error(err);
            this.dialog = true;
            this.dialogMsg = "Username หรือ Password ไม่ถูกต้อง";
          });
      }
    },
    handleOnComplete(value) {
      console.log("OTP completed: ", value);
    },
    handleOnChange(value) {
      console.log("OTP changed: ", value);
    },
    handleClearInput() {
      this.$refs.otpInput.clearInput();
    },
    // clear() {
    //   this.$v.$reset();
    //   this.name = "";
    //   this.email = "";
    //   this.select = null;
    //   this.checkbox = false;
    // },
  },
};
</script>

<style lang="scss">
.otp-input {
  width: 40px;
  height: 40px;
  padding: 3px;
  margin: 0 5px;
  font-size: 20px;
  border-radius: 4px;
  border: 1px solid rgba(0, 0, 0, 0.3);
  text-align: center;
  &.error {
    border: 1px solid red !important;
  }
}
.otp-input::-webkit-inner-spin-button,
.otp-input::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>


