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
          <h2 class="text-center py-3 mb-8"><b>ยืนยันตัวตนด้วย OTP</b></h2>
          <form autocomplete="off">
            <v-otp-input
              style="
                display: flex;
                align-items: center;
                justify-content: center;
              "
              v-model="otp"
              ref="otpInput"
              input-classes="otp-input"
              separator="-"
              :num-inputs="6"
              :should-auto-focus="true"
              :is-input-num="true"
              @on-change="handleOnChange"
              @on-complete="handleOnComplete"
            />
            <div class="d-grid gap-2 col-8 mx-auto mt-4">
              <v-btn
                rounded
                color="primary"
                class="w-100 mt-8 mb-5"
                large
                dark
                @click="otpValOnClick()"
              >
                ยืนยัน OTP
              </v-btn>
              <div class="d-flex justify-center">
                <v-btn
                  small
                  depressed
                  rounded
                  class="mr-3"
                  @click="handleClearInput()"
                  >Clear Input
                </v-btn>
                <v-btn small depressed rounded @click="otpOnClick()">
                  ขอ OTP อีกครั้ง
                </v-btn>
              </div>
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
      dialog: false,
      dialogMsg: "",

      otp: "",
    };
  },
  methods: {
    validate() {
      let validated = true;

      if (this.otp.length !== 6) {
        validated = false;
        this.dialog = true;
        this.dialogMsg = "กรุณากรอก OTP ให้ครบถ้วน";
      }
      return validated;
    },
    otpValOnClick() {
      if (this.validate()) {
        const otpresponse = this.$store.getters.getOtpresponse.otpresponse;
        const tel = this.$store.getters.getOtpresponse.tel;
        const formData = new FormData();
        formData.append("code", this.otp);
        formData.append("otpresponse", otpresponse);
        formData.append("tel", tel);
        this.$axios
              .post("http://192.168.64.3/otp/otpVal.php", formData)
              .then((response) => {
                this.$router.push("/dashboard");
              })
              .catch((error) => {
                console.log("error otpSend ==> ", error);
                this.dialog = true;
                this.dialogMsg = "ผิดพลาด OTP ไม่ถูกต้อง";
              });
      }
    },
    handleOnComplete(value) {
      this.otp = value;
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


