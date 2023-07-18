<template>
    <div>
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 margin-left">
                        <h1>
                            Validación de Entrada
                        </h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <center>
                                <div v-if="status === 1" class="alert alert-success text-center" role="alert">
                                    <h3><i class="fa-regular fa-circle-check"></i> Entrada aceptada</h3>
                                </div>
                                <div v-if="status === 0" class="alert alert-danger text-center" role="alert">
                                    <h3><i class="fa-solid fa-circle-exclamation"></i> Entrada denegada</h3>
                                </div>
                                <router-link to="/qr_code" class="btn btn-block btn-warning">Validar Nueva Entrada</router-link>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section> 
    </div>
</template>
  
<script>
export default {
    data() {
        return {
            status: null,
            id: ''
        }
    },
    methods: {
        checkTicket(id) {
            this.$axios.get('/api/ticket/check/'+ this.$route.params.id + '/' + id).then((res) => {
                this.status = res.data.data;
            })
        }
    },
    async mounted() {
        axios.get('/session-data')
        .then(response => {
            this.id = response.data.id;

            this.checkTicket(this.id);
        })
        .catch(error => {
            console.log(error);
        });
    }
}
</script>