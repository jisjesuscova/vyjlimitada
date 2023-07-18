<template>
    <div>
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Agregar Evento</h3>
                            </div>
                            <form @submit.prevent="submit">
                                <div class="card-body">
                                    <o-field label="Nombre del Evento" :variant="errors.event_name ? 'danger' : 'primary'" :message="errors.event_name">
                                        <o-input type="text" v-model="form.event_name" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <o-field label="Cantidad de Entradas" :variant="errors.ticket_quantity ? 'danger' : 'primary'" :message="errors.ticket_quantity">
                                        <o-input type="number" v-model="form.ticket_quantity" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <o-field label="Fecha del Evento" :variant="errors.event_date ? 'danger' : 'primary'" :message="errors.event_date">
                                        <o-input type="date" v-model="form.event_date" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <b-field type="hidden" label="Campo oculto">
                                        <input v-model="form.id" type="hidden">
                                    </b-field>
                                </div>

                                <div class="card-footer">
                                    <o-field
                                    ><!-- Label left empty for spacing -->
                                        <o-button variant="success" native-type="submit"> Guardar </o-button>
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
            form: {
                id: '',
                event_name: '',
                ticket_quantity: '',
                event_date: ''
            },
            errors: {
                event_name: '',
                ticket_quantity: '',
                event_date: ''
            }
        }
    },
    mounted() {
        this.getUser();
    },
    methods: {
        submit() {
            this.$axios.post('/api/event', this.form)
                .then(response => {
                    this.$router.push('/event')
                })
                .catch(error => {
                    console.log(error.response.data)

                    if(error.response.data.event_name) {
                        this.errors.event_name = error.response.data.event_name[0]
                    }

                    if(error.response.data.ticket_quantity) {
                        this.errors.ticket_quantity = error.response.data.ticket_quantity[0]
                    }

                    if(error.response.data.event_date) {
                        this.errors.event_date = error.response.data.event_date[0]
                    }
                })
        },
        async getUser() {
            try {
                const UserIdResponse = await this.$axios.get('/user_id');
                const userId = UserIdResponse.data.user_id;

                // Realiza la solicitud para obtener los datos del usuario
                const userResponse = await this.$axios.get('/api/user/' + userId);

                this.form.id = userId;
                this.form.email = userResponse.data.data.email;
                this.form.password = userResponse.data.data.password;
            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>