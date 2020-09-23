<template>
  <v-row class="lime darken-4">
    <v-col cols="0" sm="7"></v-col>
    <v-col cols="12" sm="5">
      <v-card color="rgb(0, 0, 0, 0.4)" dark class="pa-2 mt-4">
        <v-card-title class="text-center headline mt-3">
          <v-text-field
            class="centered-input white--text mt-3"
            type="password"
            maxlength="4"
            v-model="password"
            :counter="4"
            :rules="[v => !!v || 'PIN requerido']"
            required
            disabled
          ></v-text-field>
        </v-card-title>
        <v-card-text>
          <div class="vuertual-numeric-keyboard bg-light rounded border p-3">
            <v-btn
              v-for="key in keys"
              :key="key"
              class="mx-2"
              fab
              dark
              id="button__lenght"
              color="rgb(0, 0, 0, 0.4)"
              @click="actionButton(key)"
            >{{key}}</v-btn>
          </div>
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
    keys: [7, 8, 9, 4, 5, 6, 1, 2, 3, "DEL", 0, "Ok"]
  }),
  watch: {
    password(val) {
      if (val === "777") {
        this.$router.push({ name: "Config" });
      }
      if (val.length == 4) {
        this.loggin();
      }
    }
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
              confirmButtonText: "Cool"
            });
          }
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
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
</style>