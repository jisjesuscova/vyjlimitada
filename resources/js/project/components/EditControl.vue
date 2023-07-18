<template>
    <div>
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Editar Controlador</h3>
                            </div>
                            <form @submit.prevent="submit">
                                <div class="card-body">
                                    <o-field label="Estatus del Controlador" :variant="'primary'">
                                        <o-select v-model="form.status_id" model-value="" expanded>
                                            <option value="" selected disabled> - Seleccionar - </option>
                                            <option value="1"> Habilitar </option>
                                            <option value="0"> Deshabilitar </option>
                                        </o-select>
                                    </o-field>
                                </div>

                                <div class="card-footer">
                                    <o-field
                                    ><!-- Label left empty for spacing -->
                                        <o-button variant="success" native-type="submit"> Actualizar </o-button>
                                    </o-field>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            posts: [],
            form: {
                status_id: ''
            }
        }
    },
    methods: {
        submit() {
            this.$axios.put('/api/control/' + this.$route.params.id, this.form)
                .then(response => {
                    this.$router.push('/control')
                })
                .catch(error => {
                    console.log(error.response.data)
                })
        }
    },
    async mounted() {
        axios.get('/session-data')
        .then(response => {
            this.id = response.data.id;
            this.listPage(this.id); // pasa this.id a listPage()
        })
        .catch(error => {
            console.log(error);
        });
    }
}
</script>