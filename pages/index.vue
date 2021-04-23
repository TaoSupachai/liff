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
      tel: "",
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
            // do something about response
            this.$store.dispatch("setEmpData", response.data.data);

            this.dialog = true;
            this.dialogMsg = "ระบบได้ทำการส่งรหัส OTP ไปยังโทรศัพท์ของท่านแล้ว";

            //sentotp
            //this.$axios.post('~/assets/otp/otpSend.php', {tel:'0641979362'}).then((response) => {
            this.tel = response.data.data.employee.contact.Phone_Number;

            const formData = new FormData();
            formData.append(
              "tel",
              response.data.data.employee.contact.Phone_Number
            );
            this.$axios
              .post("https://line.cpcrop.com/api/otpSend.php", formData)
              .then((response) => {
                // this.otpresponse = response.data.otpresponse;
                // console.log("formData ==> ", formData);
                // console.log("otpresponse: ", response.data);
                this.$store.dispatch("setOtpresponse", {
                  otpresponse: response.data.otpresponse,
                  tel: this.tel,
                });
                this.$router.push("/otp");
              })
              .catch((error) => {
                console.log("error otpSend ==> ", error);
                this.dialog = true;
                this.dialogMsg = "ผิดพลาด";
              });
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


