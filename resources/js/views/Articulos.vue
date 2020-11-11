<template>
  <v-app>
    <meta charset="utf-8" />
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
        <v-card-text class="p-0">
          <v-container class="pt-0 pb-0">
            <v-row>
              <v-col cols="12" class="pt-0 pb-0">
                <v-text-field
                  class="centered-input display-1"
                  label="Ingrese nota de comanda"
                  type="text"
                  v-model="noteCmd"
                  :rules="[v => !!v || 'Ingrese una nota']"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions class="pt-0 pb-0">
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
    switchNavegator: false,
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
      var url = `${this.ip}/?nomFun=tb_revisar_cmd&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&parm_tipo=M$`;
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
              confirmButtonText: "OK"
            });
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    addToMesa(item, index) {
      var url = `${this.ip}/?nomFun=tb_item&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_prod=${index}&parm_cant=1&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&parm_tipo=M$`;
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
              confirmButtonText: "OK"
            });
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    addNote() {
      var url = `${this.ip}/?nomFun=tb_new_notacmd&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&parm_nota=${this.noteCmd}&parm_tipo=M$`;
      axios
        .get(url)
        .then(({ data }) => {
          this.dialog = false;
          if (data.msg == "OK") {
            this.noteCmd = "";
          } else {
            this.noteCmd = "";
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "OK"
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
          confirmButtonText: "OK"
        });
      } else {
        var url = `${this.ip}/?nomFun=tb_item&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_prod=${item.idprod}&parm_cant=${cant}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&parm_tipo=M$`;
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
                confirmButtonText: "OK"
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
          this.getPrintDataKitchen();
          break;
        case "precuenta":
          this.getPrintDataPrecuenta();
          break;
        case "anotacion":
          this.dialog = true;
          break;

        default:
          break;
      }
    },
    getPrintDataKitchen() {
      var url = `${this.ip}/?nomFun=tb_print_cocina&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&parm_tipo=M$`;
      //console.log(url)
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            var titulo = data.titulo;
            var prod = data.prod;
            var nro_print = data.nro_impresiones;
            var mozo = data.mozo;
            var nrocmd = data.nrocmd;
            this.sendKitchen(titulo, prod, nro_print, mozo, nrocmd);
          } else {
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "OK"
            });
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    sendKitchen(titulo, prod, nro_print, mozo, nrocmd) {
      var url = `api/comanda/imprimir/cocina`;
      axios
        .post(url, {
          titulo: titulo,
          prod: prod,
          nro_print: nro_print,
          mozo: mozo,
          nrocmd: nrocmd
        })
        .then(({ data }) => {
          if (data.msg == "OK") {
            Swal.fire({
              title: "Enviado a cocina!",
              text: data.msg,
              icon: "success",
              confirmButtonText: "OK"
            });
            this.salir();
          } else {
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "OK"
            });
          }
        })
        .catch(error => {
          if (error.response) {
            if (error.response.status === 401) {
            }
          }
        });
    },
    getPrintDataPrecuenta() {
      var url = `${this.ip}/?nomFun=tb_print_precuenta&parm_pin=${this.pin}&parm_piso=20&parm_id_mesas=${this.mesaId}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&parm_tipo=M$`;
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            var empresa = data.empresa;
            var cajero = data.cajero;
            var mozo = data.mozo;
            var mesas = data.mesas;
            var prod = data.prod;
            var sub_total = data.sub_total;
            var adicionales = data.adicionales;
            var total = data.total;
            this.sendPrecuenta(empresa, cajero, mozo, mesas, prod,sub_total, adicionales,total);
          } else {
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "OK"
            });
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    sendPrecuenta(empresa, cajero, mozo, mesas, prod,sub_total, adicionales,total) {
      var url = `api/comanda/imprimir/precuenta`;
      axios
        .post(url, {
          empresa: empresa,
          cajero: cajero,
          mozo: mozo,
          mesas: mesas,
          prod: prod,
          sub_total: sub_total,
          adicionales: adicionales,
          total: total
        })
        .then(({ data }) => {
          if (data.msg == "OK") {
            this.salir();
          } else {
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "OK"
            });
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    salir() {
      var url = `${this.ip}/?nomFun=tb_des_cmd&parm_pin=${this.pin}&parm_id_cmd=${this.mesa.id_cmd}&parm_id_mesero=${this.userID}&parm_tipo=M$`;
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            this.$router.push({ name: "Home" });
          } else {
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "OK"
            }).then(result => {
              if (result.value) {
                this.$router.push({ name: "Home" });
              }
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
.mifuente {
  font-size: 1.3vw;
  font-weight: bold;
}
</style>