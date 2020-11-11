<template>
  <v-row
    justify="end"
    align="center"
    style="
      background-image: url('/img/fondo-login.jpg');
      width: 100vw;
      height: 100vh;
      background-size: cover;
    "
  >
    <v-col cols="12" sm="8" md="4">
      <v-card color="rgb(0, 0, 0, 0.4)" dark class="pa-2 mt-4">
        <v-card-text class="black-color">
          <v-row align="center">
            <v-col cols="12" class="py-0">
              <v-text-field
                class="centered-input white--text mt-3"
                type="password"
                maxlength="4"
                v-model="password"
                :counter="4"
                :rules="[(v) => !!v || 'PIN requerido']"
                required
                disabled
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(7)"
                >7</v-btn
              ></v-col
            >
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(8)"
                >8</v-btn
              ></v-col
            >
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(9)"
                >9</v-btn
              ></v-col
            >
          </v-row>
          <v-row>
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(4)"
                >4</v-btn
              ></v-col
            >
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(5)"
                >5</v-btn
              ></v-col
            >
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(6)"
                >6</v-btn
              ></v-col
            >
          </v-row>
          <v-row>
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(1)"
                >1</v-btn
              ></v-col
            >
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(2)"
                >2</v-btn
              ></v-col
            >
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(3)"
                >3</v-btn
              ></v-col
            >
          </v-row>
          <v-row>
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton('DEL')"
                >DEL</v-btn
              ></v-col
            >
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton(0)"
                >0</v-btn
              ></v-col
            >
            <v-col cols="4" class="py-0">
              <v-btn
                class="mx-2"
                fab
                dark
                id="button__lenght"
                color="rgb(0, 0, 0, 0.8)"
                @click="actionButton('Ok')"
                >Ok</v-btn
              ></v-col
            >
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
    <v-dialog v-model="loading" persistent max-width="600px">
      <app-spinner v-show="loading"></app-spinner>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  data: () => ({
    loading: false,
    password: "",
    ip: "",
    keys: [7, 8, 9, 4, 5, 6, 1, 2, 3, "DEL", 0, "Ok"],
  }),
  watch: {
    password(val) {
      if (val === "777") {
        this.$router.push({ name: "Config" });
      }
      if (val.length == 4) {
        this.loggin();
      }
    },
  },
  created() {
    this.ip = this.$store.getters.getIP;
  },
  methods: {
    actionButton(val) {
      switch (val) {
        case "DEL":
          this.password = this.password.substring(0, this.password.length - 1);
          break;
        case "Ok":
          this.loggin();
          break;

        default:
          if (this.password.length < 4) {
            this.password = this.password + val;
          }
      }
    },
    loggin() {
      axios
        .get(
          `${this.ip}/?nomFun=tb_login&parm_cod=Xyfk8Gixnf&parm_new=0&parm_pin=${this.password}&parm_tipo=M$`
        )
        .then(({ data }) => {
          console.log(data);
          this.loading = false;
          if (data.status === 1) {
            this.$store.commit("SET_PIN", this.password);
            this.$store.commit("SET_PISOS", JSON.stringify(data.pisos));
            this.$store.commit("SET_FAMILIAS", JSON.stringify(data.fam));
            this.$store.commit("SET_USER_NAME", data.nombre);
            this.$store.commit("SET_USER_ID", data.id_usr);
            this.$router.push({ name: "Home" });
          } else {
            this.password = "";
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "OK",
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style>
.centered-input input {
  text-align: center;
}
#button__lenght {
  height: 4rem !important;
  width: 4rem !important;
}
.vuertual-numeric-keyboard {
  display: grid;
  grid-template-columns: auto auto auto;
  grid-gap: 10px;
  background-color: rgba(0, 0, 0, 0.2) !important;
  border: none !important;
}
.black-color {
  background-color: rgba(0, 0, 0, 0.2) !important;
  border: none !important;
}
</style>