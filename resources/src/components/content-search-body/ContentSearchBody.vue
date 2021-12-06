<template>
    <ContentLoad v-if="addressList.length < 1"></ContentLoad>

    <div
        class="card mt-3 animate__animated animate__fadeInUp"
        v-if="addressList.length > 0"
    >
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">CEPs consultados anteriormente</h5>
                    <h6 class="card-subtitle mb-2 text-muted">--</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-hover">
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
                            <tr
                                v-for="address in addressList"
                                :key="address.id"
                            >
                                <td>{{ address.id }}</td>
                                <td>
                                    <div class="widget-26-job-title">
                                        <span>{{ address.logradouro }}</span>
                                        <p class="m-0">
                                            <span class="text-muted time">{{
                                                address.bairro
                                            }}</span>
                                            -
                                            <span class="text-muted time">{{
                                                address.cidade
                                            }}</span>
                                            -
                                            <span class="text-muted time">{{
                                                address.estado
                                            }}</span>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <p>{{ address.cep }}</p>
                                </td>
                                <td>
                                    <div class="widget-26-job-salary">
                                        {{
                                            address.cadastrado_em.dia_formatado
                                        }}
                                        -
                                        {{
                                            address.cadastrado_em.hora_formatado
                                        }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-primary me-1"
                                        @click="showDataAddress(address)"
                                    >
                                        visualizar
                                    </button>
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
import * as _ from "lodash";
import ContentLoad from "../content-load/ContentLoad.vue";
export default defineComponent({
    components: {
        ContentLoad,
    },
    data() {
        return {
            addressList: [],
        };
    },
    methods: {
        makeModalOption(address: any): any {
            let title = "";
            let body = "";
            let whithModal = 600;
            if (_.isArray(address)) {
                whithModal = 980;
                title = `<strong>Endereços correspondentes</strong>`;
                body += `<table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">CEP</th>
                        <th scope="col">Logradouro</th>
                        <th scope="col">Cidade</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Google Maps</th>
                      </tr>
                    </thead>
                    <tbody>`;

                _.forEach(address, function (value, key) {
                    body += `<tr>
                    <th scope="row">${key + 1}</th>
                    <td><strong>${value.cep}</strong></td>
                    <td>${value.logradouro}</td>
                    <td>${value.cidade}</td>
                    <td>${value.estado}</td>
                    <td><a href="${value.google_map}" target="_blank">Visualizar</a></td>
                  </tr>`;
                });

                body += `</tbody></table>`;
            } else {
                title = `<strong>CEP: ${address.cep}</strong>`;
                body = `<h5>${address.logradouro}</h5>
                     <p class="m-0">
                      <span class="text-muted time">${address.bairro}</span>-
                      <span class="text-muted time">${address.cidade}</span>-
                      <span class="text-muted time">${address.estado}</span>
                    </p><br/><p><a href="${address.google_map}" target="_blank">Visualizar no Google Maps</a></p>`;
            }

            return {
                width: whithModal,
                title: title,
                html: body,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: "Fechar visualização",
                showClass: {
                    popup: "animate__animated animate__fadeInDown",
                },
                hideClass: {
                    popup: "animate__animated animate__fadeOutUp",
                },
            };
        },
        showDataAddress(address: any): void {
            this.$swal(this.makeModalOption(address)).then(() => {
                this.loadDataAddress();
            });
        },
        async loadDataAddress(): Promise<void> {
            this.addressList = [];
            try {
                const response = await this.$http.get(
                    "/api/busca-cep?sort=id&order=desc"
                );
                this.addressList = response.data.data;
            } catch (error) {
                this.$swal(
                    "Erro",
                    "Não conseguimos carregar a lista de endereço",
                    "error"
                );
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
