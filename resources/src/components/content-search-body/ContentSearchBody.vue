<template>

    <ContentLoad v-if="addressList.length < 1"></ContentLoad>

  <div class="card mt-5 animate__animated animate__fadeInUp" v-if="addressList.length > 0">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <h5 class="card-title">CEPs consultados anteriormente</h5>
          <h6 class="card-subtitle mb-2 text-muted">--</h6>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <table class="table widget-26">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Endereço</th>
                <th scope="col">CEP</th>
                <th scope="col">Data de cadastro</th>
                <th scope="col" class="text-center">Ação</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="address in addressList" :key="address.id">
                <td>{{ address.id }}</td>
                <td>
                  <div class="widget-26-job-title">
                    <span>{{ address.logradouro }}</span>
                    <p class="m-0">
                      <span class="text-muted time">{{ address.bairro }}</span>
                      -
                      <span class="text-muted time">{{ address.cidade }}</span>
                      -
                      <span class="text-muted time">{{ address.estado }}</span>
                    </p>
                  </div>
                </td>
                <td>
                  <p>{{ address.cep }}</p>
                </td>
                <td>
                  <div class="widget-26-job-salary">
                    {{ address.cadastrado_em.dia_formatado }} -
                    {{ address.cadastrado_em.hora_formatado }}
                  </div>
                </td>

                <td class="text-center">
                  <button type="button" class="btn btn-primary me-1" @click="showDataAddress(address)">
                    visualizar
                  </button>
                  <button type="button" class="btn btn-success me-1">
                    Atualizar
                  </button>
                  <button type="button" class="btn btn-danger">Deletar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>


<script lang="ts">
import { defineComponent, ref } from "vue";
import ContentLoad from "../content-load/ContentLoad.vue";
export default defineComponent({
     components: {
      ContentLoad
  },
  data() {
    return {
      addressList: [],
    };
  },
  methods: {
    showDataAddress(address: any): void {
        const title = `<i>CEP: ${address.cep}</i>`;
        const body = `<h5>${address.logradouro}</h5>
                     <p class="m-0">
                      <span class="text-muted time">${ address.bairro }</span>-
                      <span class="text-muted time">${ address.cidade }</span>-
                      <span class="text-muted time">${ address.estado }</span>
                    </p>`;
        this.$swal({ html:body, title: title}).then(() =>{
            this.loadDataAddress();
        });
    },
    async loadDataAddress(): Promise<void> {
        this.addressList = [];
        try {
            const response = await this.$http.get('/api/busca-cep?sort=id&order=desc');
            this.addressList = response.data.data;
        } catch (error) {
            this.$swal("Erro", "Não conseguimos carregar a lista de endereço", "error");
        }
    },
  },
  mounted(): void {
      this.loadDataAddress();
      this.emitter.on("reloadDataAddress", () => {
        this.loadDataAddress();
    });
      this.emitter.on("showDataAddress", (address: any) => {
        this.showDataAddress(address);
    });
  },
});
</script>


<style lang="scss" src="./style.scss"></style>
