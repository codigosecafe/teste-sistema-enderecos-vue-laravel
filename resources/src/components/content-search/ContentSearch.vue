<template>
    <div class="row animate__animated animate__zoomIn">
        <div class="col-md-12 mt-3 mb-3">
            <h1 class="text__color--white text-center">
                Sistema de consulta de endereços com base no CEP
            </h1>
        </div>
    </div>
    <div class="row animate__animated animate__fadeInUp">
        <div class="col-lg-12 card-margin mt-3">
            <div class="card search-form">
                <div class="card-body p-0">
                    <form id="search-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="row no-gutters">
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <select
                                            class="
                                                form-select form-select-lg
                                                mb-1
                                                bg--orange
                                                text__color--white
                                            "
                                            id="exampleFormControlSelect1"
                                            v-model="typeSearch"
                                        >
                                            <option value="1" selected>
                                                Buscar por CEP
                                            </option>
                                            <option value="2">
                                                Buscar por Logradouro
                                            </option>
                                        </select>
                                    </div>
                                    <div
                                        class="col-lg-8 col-md-6 col-sm-12 p-0"
                                    >
                                        <vue-mask
                                            v-if="typeSearch == 1"
                                            class="form-control input-search"
                                            :placeholder="placeholderText"
                                            v-model="searchTerm"
                                            mask="00.000-000"
                                            :raw="false"
                                        >
                                        </vue-mask>

                                        <input
                                            type="text"
                                            v-if="typeSearch == 2"
                                            :placeholder="placeholderText"
                                            v-model="searchTerm"
                                            class="form-control input-search"
                                        />
                                    </div>
                                    <div
                                        class="col-lg-1 col-md-3 col-sm-12 p-0"
                                    >
                                        <button
                                            class="
                                                btn btn-base
                                                bg--purple
                                                text__color--white
                                            "
                                            @click="searchDataZipCodeAddress"
                                            @keydown.enter="
                                                searchDataZipCodeAddress
                                            "
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="feather feather-search"
                                            >
                                                <circle
                                                    cx="11"
                                                    cy="11"
                                                    r="8"
                                                ></circle>
                                                <line
                                                    x1="21"
                                                    y1="21"
                                                    x2="16.65"
                                                    y2="16.65"
                                                ></line>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <div class="row">
        <div class="col-md-12 mt-5 animate__animated animate__fadeInUp animate__delay-4s">
            <h6 class="text__color--white text-center">Feito com <i class="fas fa-heart"></i> por Claudio Alexssandro Lino</h6>
        </div>
    </div>
    <ContentLoad v-if="showLoad"></ContentLoad>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import ContentLoad from "../content-load/ContentLoad.vue";
export default defineComponent({
    components: {
        ContentLoad,
    },
    data() {
        return {
            showLoad: false,
            typeSearch: 1,
            searchTerm: "",
            placeholderText: "Digite aqui o CEP a ser pesquisado",
        };
    },
    watch: {
        typeSearch(typeSearchValue) {
            this.placeholderText =
                typeSearchValue == 1
                    ? "Digite aqui o CEP a ser pesquisado"
                    : "Digite aqui o endereço a ser pesquisado. essa busca se limita apenas a nossa base de dados";

            this.searchTerm = "";
        },
    },
    methods: {
        searchDataZipCodeAddress(): any {
            this.showLoad = true;
            let urlSearch = `/api/busca-cep/${this.searchTerm}`;
            if (this.typeSearch === "2") {
                urlSearch = `/api/busca-cep?searchTerm=${this.searchTerm}&sort=street&order=asc`;
            }
            this.$http
                .get(urlSearch)
                .then((response: any) => {
                    this.emitter.emit("showDataAddress", response.data.data);
                })
                .catch((error: any) => {
                    if (error.response) {
                        this.$swal(
                            "Erro",
                            error.response.data.message,
                            "error"
                        );
                    }
                })
                .then(() => {
                    this.showLoad = false;
                });

            return 0;
        },
    },
    mounted(): void {},
});
</script>

<style lang="scss" src="./style.scss"></style>
