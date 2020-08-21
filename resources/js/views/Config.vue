<template>
  <v-app id="inspire">
    <v-main fullscreen>
      <v-row align="center" justify="center">
        <v-col cols="12" md="8">
          <v-card>
            <v-card-text>
              <v-text-field
                height="10vh"
                class="centered-input display-1 white--text mt-3"
                type="text"
                v-model="ip"
                :counter="2"
                :rules="[v => !!v || 'Ingrese una dirección IP']"
                required
              ></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-btn color="error" dark @click="cancel">Cancelar</v-btn>
              <v-spacer></v-spacer>
              <v-btn color="primary" dark @click="save">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data: () => ({
    ip: ""
  }),
  computed: {},
  created() {
    this.ip = this.$store.getters.getIP;
  },
  methods: {
    save() {
      this.$store.commit("SET_IP", this.ip);
      this.$router.push({ name: "Login" });
    },
    cancel() {
      this.$router.push({ name: "Login" });
    }
  }
};
</script>
<style>
</style>