<template>
  <v-app>
    <v-app-bar app clipped-right style="height: 3rem;">
      <v-slide-group multiple show-arrows>
        <v-slide-item v-for="(item, index) in pisos" :key="index" v-slot:default="{ active }">
          <v-btn
            class="ma-2 white--text card card-block"
            tile
            :input-value="active"
            :color="index==pisoActual?'black':'light-green darken-4'"
            @click="getMesas(index)"
          >{{item}}</v-btn>
        </v-slide-item>
      </v-slide-group>
      <v-spacer></v-spacer>
      <v-switch v-model="switchNavegator" label="Opciones de mesa"></v-switch>
    </v-app-bar>
    <v-main app>
      <perfect-scrollbar>
        <v-col cols="12" v-show="loading">
          <app-spinner></app-spinner>
        </v-col>
        <v-card class="d-flex flex-wrap" flat tile>
          <v-card
            v-for="(item, index) in mesas"
            :key="index"
            :color="item.st_mesa=='L'?'success':'error'"
            dark
            height="6rem"
            width="8rem"
            class="pa-2 mt-4 ml-4 mr-3"
            @click="actionMesa(item,index)"
          >
            <v-card-title class="p-0">
              {{item.nombre}}
              <i v-show="showJoinsMesa(item)" :class="checkJoin(index)"></i>
            </v-card-title>
            <v-card-text class="pb-0">
              {{item.mesero}}
              <i :class="getIcon(item)"></i>
            </v-card-text>
            <v-card-text class="p-0">
              <div class="mesa__header">
                <i v-show="item.juntada == 1" class="fas fa-link black--text"></i>
              </div>
            </v-card-text>
          </v-card>
        </v-card>
      </perfect-scrollbar>
    </v-main>
    <v-navigation-drawer v-if="switchNavegator" app permanent right clipped width="12rem" height="70vh">
      <v-list dense>
        <v-list-item @click="actionButton('JUNTAR')">
          <v-list-item-icon>
            <v-icon>fas fa-circle-notch</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{showJoins==true?'OK':'JUNTAR'}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="actionButton('COBRAR')">
          <v-list-item-icon>
            <v-icon>fas fa-circle-notch</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>COBRAR</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="actionButton('ELIMINAR')">
          <v-list-item-icon>
            <v-icon>fas fa-circle-notch</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>ELIMINAR</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="actionButton('SEPARAR')">
          <v-list-item-icon>
            <v-icon>fas fa-circle-notch</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>SEPARAR</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="actionButton('MOVER')">
          <v-list-item-icon>
            <v-icon>fas fa-circle-notch</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>MOVER</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-footer app class="text--accent-3" style="height: 3rem;">
      <v-spacer></v-spacer>
      <v-btn
        class="ma-2 white--text mt-0"
        tile
        min-width="8rem"
        color="purple accent-4"
        @click="logout"
      >BLOQUEAR</v-btn>
    </v-footer>

    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">INGRESE CANTIDAD DE COMENSALES</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  class="centered-input mt-3 display-1"
                  type="number"
                  min="1"
                  maxlength="2"
                  height="10vh"
                  v-model="numcomen"
                  :counter="2"
                  :rules="[v => !!v || 'Ingrese una cantidad']"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" text @click="dialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="saveComanda">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
export default {
  data: () => ({
    loading: true,
    showJoins: false,
    switchNavegator: false,
    buttonKeys: [
      { accion: "JUNTAR", icon: "fas fa-circle-notch" },
      { accion: "COBRAR", icon: "fas fa-circle-notch" },
      { accion: "ELIMINAR", icon: "fas fa-circle-notch" },
      { accion: "SEPARAR", icon: "fas fa-circle-notch" },
      { accion: "MOVER", icon: "fas fa-circle-notch" }
    ],
    ip: "",
    pisos: "",
    pisoActual: "0",
    mesas: "",
    mesaId: "",
    mesaActual: "",
    arrayMesas: [],
    pin: "",
    dialog: false,
    numcomen: 1
  }),
  watch: {
    pisos(val) {
      if (Object.keys(val).length > 0) {
        var index = Object.keys(val)[0];
        if (this.pisoActual == "0") {
          this.getMesas(index);
        } else {
          this.getMesas(this.pisoActual);
        }
      }
    }
  },
  created() {
    this.pisos = JSON.parse(this.$store.getters.getPISOS);
    this.pin = this.$store.getters.getPIN;
    this.ip = this.$store.getters.getIP;
    this.pisoActual = this.$store.getters.getPISO_ACTUAL;
  },
  methods: {
    getMesas(piso) {
      this.mesas = [];
      this.loading = true;
      this.pisoActual = piso;
      axios
        .get(
          `${this.ip}/?nomFun=tb_mesas&parm_pin=${this.pin}&parm_piso=${piso}&parm_tipo=M$`
        )
        .then(({ data }) => {
          this.loading = false;
          this.mesas = data.mesas;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getIcon(item) {
      var st_cmd = item.st_cmd;
      var icon = "";
      switch (st_cmd) {
        case "1":
          //Pidio precuenta

          break;
        case "3":
          //Pidio precuenta
          icon = "fas fa-coins";
          break;

        case "4":
          //Esta en preparción
          icon = "mdi mdi-pot-steam";
          break;

        default:
          break;
      }
      return icon;
    },
    actionButton(key) {
      switch (key) {
        case "JUNTAR":
          if (this.showJoins) {
            this.showJoins = !this.showJoins;
            this.unirMesas();
          } else {
            this.showJoins = !this.showJoins;
          }
          break;
        /*case "SEPARAR":
          if (this.showJoins) {
            this.showJoins = !this.showJoins;
            this.separarMesas();
          } else {
            this.showJoins = !this.showJoins;
          }
          break;*/

        default:
          break;
      }
    },
    actionMesa(item, index) {
      var st_cmd = item.st_cmd;
      if (this.showJoins) {
        if ((st_cmd != "3") & (st_cmd != "4")) {
          var existe = false;
          this.arrayMesas.forEach(e => {
            if (e.id == index) {
              existe = true;
            }
          });
          if (existe) {
            var i = this.arrayMesas.indexOf(item);
            this.arrayMesas.splice(i, 1);
          } else {
            var mesa = {
              id: index,
              id_cmd: item.id_cmd,
              st_cmd: item.st_cmd,
              st_join: 1
            };
            this.arrayMesas.push(mesa);
          }
        }
      } else {
        this.newComanda(item, index);
      }
    },
    checkJoin(index) {
      var std = "mdi mdi-checkbox-blank-outline";
      this.arrayMesas.forEach(e => {
        if (e.id == index) {
          std = "mdi mdi-checkbox-marked-outline";
        }
      });
      return std;
    },
    showJoinsMesa(item) {
      var st_cmd = item.st_cmd;
      var std = false;
      if (this.showJoins & (st_cmd != "3") & (st_cmd != "4")) {
        std = true;
      }
      return std;
    },
    newComanda(item, index) {
      this.mesaId = index;
      this.mesaActual = item;
      this.$store.commit("SET_MESA_ACTUAL", JSON.stringify(item));
      this.$store.commit("SET_ID_MESA_ACTUAL", index);
      var id = this.$store.getters.getUSERID;
      if (item.st_cmd == "") {
        this.dialog = true;
      } else {
        var url = `${this.ip}/?nomFun=tb_revisar_cmd&parm_pin=${this.pin}&parm_piso=${this.pisoActual}&parm_id_cmd=${item.id_cmd}&parm_tipocmd=1&parm_id_mesero=${id}&parm_tipo=M$`;
        axios
          .get(url)
          .then(({ data }) => {
            if (data.status !== 0) {
              this.$router.push({ name: "Store" });
              this.$store.commit("SET_PISO_ACTUAL", this.pisoActual);
            } else {
              if (data.msg == "Comanda no existe") {
                Swal.fire({
                  title: "Advertencia!",
                  text: data.msg,
                  icon: "warning",
                  confirmButtonText: "Cool"
                });
                this.getMesas(this.pisoActual);
              } else {
                var name = this.$store.getters.getUSERNAME;
                var msg = `Comanda esta siendo Editada por mesero: ${name}`;
                if (data.msg != msg) {
                  Swal.fire({
                    title: "Advertencia!",
                    text: data.msg,
                    icon: "warning",
                    confirmButtonText: "Cool"
                  });
                } else {
                  this.$router.push({ name: "Store" });
                  this.$store.commit("SET_PISO_ACTUAL", this.pisoActual);
                }
              }
            }
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    saveComanda() {
      var id = this.$store.getters.getUSERID;
      var url = `${this.ip}/?nomFun=tb_new_cmd&parm_pin=${this.pin}&parm_piso=${this.pisoActual}&parm_id_mesas=${this.mesaId}&parm_num=${this.numcomen}&parm_tipocmd=1&parm_id_mesero=${id}&parm_tipo=M$`;
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            //this.getMesas(this.pisoActual);
            this.mesaActual.id_cmd = data.idcmd;
            this.$store.commit(
              "SET_MESA_ACTUAL",
              JSON.stringify(this.mesaActual)
            );
            this.$router.push({ name: "Store" });
            this.$store.commit("SET_PISO_ACTUAL", this.pisoActual);
            this.dialog = false;
          } else {
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "Cool"
            });
            this.getMesas(this.pisoActual);
            this.dialog = false;
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    unirMesas() {
      var id = this.$store.getters.getUSERID;
      var id_cmd = "";
      var id_mesas = "";
      this.arrayMesas.forEach(e => {
        if (e.id_cmd != 0) {
          id_cmd = e.id_cmd;
        }
        id_mesas = id_mesas + "," + String(e.id);
      });
      var url = `${this.ip}/?nomFun=tb_juntar_mesa&parm_id_cmd=${id_cmd}&parm_id_mesas=${id_mesas}&parm_tipo=M$`;
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            this.getMesas(this.pisoActual);
          } else {
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "Cool"
            });
            this.getMesas(this.pisoActual);
            this.arrayMesas = [];
          }
        })
        .catch(error => {
          this.arrayMesas = [];
          console.log(error);
        });
    },
    separarMesas() {
      var id = this.$store.getters.getUSERID;
      var id_cmd = "";
      var id_mesas = "";
      this.arrayMesas.forEach(e => {
        if (e.id_cmd != 0) {
          id_cmd = e.id_cmd;
        }
        id_mesas = id_mesas + "," + String(e.id);
      });
      var url = `${this.ip}/?nomFun=tb_separar_mesa&parm_id_cmd=${id_cmd}&parm_id_mesa=${id_mesas}&parm_tipo=M$`;
      axios
        .get(url)
        .then(({ data }) => {
          if (data.msg == "Ok") {
            this.getMesas(this.pisoActual);
          } else {
            Swal.fire({
              title: "Advertencia!",
              text: data.msg,
              icon: "warning",
              confirmButtonText: "Cool"
            });
            this.getMesas(this.pisoActual);
            this.arrayMesas = [];
          }
        })
        .catch(error => {
          this.arrayMesas = [];
          console.log(error);
        });
    },
    logout() {
      this.$store.commit("SET_PIN", null);
      this.$router.push({ name: "Login" });
    }
  }
};
</script>
<style>
.centered-input input {
  text-align: center;
}
#inspireme--body--index {
  height: 70vh;
}
.__vuescroll .hasVBar {
  height: 60vh !important;
}
.mesa__header {
  display: flex;
  width: 95%;
  justify-content: space-between;
}
</style>