<template>
  <v-app>
    <v-navigation-drawer app permanent fixed clipped width="35vw" height="70vh">
      <v-list-item v-for="(item, index) in artList" :key="index" class="pl-2 pr-2">
        <v-list-item-action class="mr-2">
          <div>
            <v-btn color="primary" fab x-small dark @click="alterList(item,'minus')">
              <v-icon>mdi-minus</v-icon>
            </v-btn>
            {{item.cant}}
            <v-btn color="primary" fab x-small dark @click="alterList(item,'plus')">
              <v-icon>mdi-plus</v-icon>
            </v-btn>
          </div>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-subtitle>
            <small>
              <b class="mifuente">{{ item.name }}</b>
            </small>
          </v-list-item-subtitle>
        </v-list-item-content>

        <v-list-item-action class="ml-2">
          <v-btn color="red" fab x-small dark @click="alterList(item,'remove')">
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </v-list-item-action>
      </v-list-item>
    </v-navigation-drawer>
    <v-app-bar app clipped-left height="35vh">
      <v-toolbar-title>
        <strong>{{mesa.nombre}}</strong>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn @click="salir" color="warning">
        <v-icon>mdi-arrow-left-bold</v-icon>
      </v-btn>
    </v-app-bar>
    <v-main app height="40vh">
      <v-toolbar dense min-width="65vw">
        <v-slide-group multiple show-arrows>
          <v-slide-item v-for="(item, index) in familias" :key="index" v-slot:default="{ active }">
            <v-btn
              class="ma-2 white--text card card-block"
              tile
              :input-value="active"
              color="light-blue darken-4"
              @click="getArticles(item)"
            >{{item.nombre}}</v-btn>
          </v-slide-item>
        </v-slide-group>
      </v-toolbar>
      <perfect-scrollbar>
        <v-card class="d-flex flex-wrap" flat tile>
          <v-card
            v-for="(item, index) in articulos"
            :key="index"
            color="orange lighten-4"
            dark
            width="10rem"
            height="8rem"
            class="pa-2 mt-4 ml-4"
            @click="addToMesa(item,index)"
          >
            <v-card-text class="black--text" style="height:5rem!important">{{item.nombre}}</v-card-text>
            <v-card-actions class="black">
              <strong>S/.{{item.precio}}</strong>
            </v-card-actions>
          </v-card>
        </v-card>
      </perfect-scrollbar>
    </v-main>
    <v-footer app fixed class="text--accent-3" height="40vh">
      <strong class="subheading">Total: S/.{{ total}}</strong>
      <v-spacer></v-spacer>
      <v-btn
        v-for="key in buttonKeys"
        :key="key.accion"
        @click="actionButton(key.accion)"
        color="primary"
        class="mr-3 mt-0"
      >
        <v-icon large>{{key.icon}}</v-icon>
      </v-btn>
    </v-footer>
    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  class="centered-input mt-3 display-1"
                  label="Ingrese nota de comanda"
                  type="text"
                  v-model="noteCmd"
                  :rules="[v => !!v || 'Ingrese una nota']"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
          <small>*campos requeridos</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" text @click="dialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="addNote">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
export default {
  data: () => ({
    ip: "",
    familias: "",
    buttonKeys: [
      { accion: "cocina", icon: "mdi-pot-steam" },
      { accion: "anotacion", icon: "mdi-note-text-outline" },
      { accion: "precuenta", icon: "mdi-cash-register" }
    ],
    articulos: [],
    articlesEnMesa: [],
    mesa: "",
    mesaId: "",
    total: "",
    pin: "",
    dialog: false,
    noteCmd: "",
    numcomen: 0,
    cantidad: 0,
    userID: 0
  }),
  computed: {
    artList() {
      return this.articlesEnMesa;
    }
  },
  created() {
    this.familias = JSON.parse(this.$store.getters.getFAMILIAS);
    this.mesa = JSON.parse(this.$store.getters.get_MESA_ACTUAL);
    this.mesaId = this.$store.getters.get_ID_MESA_ACTUAL;
    this.pin = this.$store.getters.getPIN;
    this.ip = this.$store.getters.getIP;
    this.userID = this.$store.getters.getUSERID;
    this.getArticlesinMesa();
  },
  methods: {
    getArticles(fam) {
      this.articulos = [];
      this.articulos = fam.json_prod;
    },
    getArticlesinMesa() {
      var url = `/conexion/cd_articulo.php?&parm_pin=${this.pin}&parm_id_mesas=${this.mesaId}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&articulo=1&ip=${this.ip}`;
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            this.articlesEnMesa = data.prod;
            this.total = data.total;
          } else {
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
    },
    addToMesa(item, index) {
      var url = `/conexion/cd_articulo.php?&parm_pin=${this.pin}&parm_id_mesas=${this.mesaId}&parm_id_prod=${index}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&articulo=2&ip=${this.ip}`;
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            this.articlesEnMesa = data.prod;
            this.total = data.total;
          } else {
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
    },
    addNote() {
      var url = `/conexion/cd_imprimir.php?nomFun=tb_new_notacmd&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&imprimmir=4&ip=${this.ip}&parm_nota=${this.noteCmd}`;
      axios
        .get(url)
        .then(({ data }) => {
          this.dialog = false;
          if (data.msg == "OK") {
          } else {
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
    },
    alterList(item, action) {
      var cant = 1;
      if (action == "minus") {
        cant = -1;
      }
      if (action == "remove") {
        cant = 0;
      }
      if (item.print === 1 && action != "plus") {
        Swal.fire({
          title: "Advertencia!",
          text: "Esta accion solo la puede ejecutar un administrador",
          icon: "warning",
          confirmButtonText: "Cool"
        });
      } else {
        var url = `/conexion/cd_articulo.php?&parm_pin=${this.pin}&parm_id_mesas=${this.mesaId}&parm_id_prod=${item.idprod}&parm_cant=${cant}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&articulo=3&ip=${this.ip}`;
        axios
          .get(url)
          .then(({ data }) => {
            if (data.msg == "Ok") {
              this.articlesEnMesa = data.prod;
              this.total = data.total;
            } else {
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
    },
    actionButton(val) {
      switch (val) {
        case "cocina":
          this.sendKitchen();
          break;
        case "precuenta":
          this.sendPrecuenta();
          break;
        case "anotacion":
          this.dialog = true;
          break;

        default:
          break;
      }
    },
    sendKitchen() {
      var url = `/conexion/cd_imprimir.php?nomFun=tb_enviar_cmd&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&imprimmir=2&ip=${this.ip}&parm_nota=${this.noteCmd}`;
      //console.log(url)
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "OK") {
            this.$store.dispatch("BREAK");
            this.$router.push({ name: "Home" });
            Swal.fire({
              title: "Enviado a cocina!",
              text: data.msg,
              icon: "success",
              confirmButtonText: "Cool"
            });
          } else {
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
    },
    sendPrecuenta() {
      var url = `/conexion/cd_imprimir.php?nomFun=tb_cobrar_mesa&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_cmd=${this.mesa.id_cmd}&parm_dade=1&parm_id_mesero=${this.userID}&imprimmir=3&ip=${this.ip}`;
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            this.$store.dispatch("BREAK");
            this.$router.push({ name: "Home" });
            this.articlesEnMesa = data.prod;
            this.total = data.total;
          } else {
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
    },
    salir() {
      this.$store.dispatch("BREAK");
      this.$router.push({ name: "Home" });
    }
  }
};
</script>
<style>
.mifuente {
  font-size: 1.3vw;
  font-weight: bold;
}
</style>