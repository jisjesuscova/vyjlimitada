<template>
    <div>
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Agregar Equipo</h3>
                            </div>
                            <form @submit.prevent="submit">
                                <div class="card-body">
                                    <o-field label="Nombre del Equipo" :variant="errors.team_name ? 'danger' : 'primary'" :message="errors.team_name">
                                        <o-input type="text" v-model="form.team_name" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <o-field label="Nombre del Organizador" :variant="errors.name ? 'danger' : 'primary'" :message="errors.name">
                                        <o-input type="text" v-model="form.name" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <o-field label="Correo del Organizador" :variant="errors.email ? 'danger' : 'primary'" :message="errors.email">
                                        <o-input type="email" v-model="form.email" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <o-field label="Contraseña del Organizador" :variant="errors.password ? 'danger' : 'primary'" :message="errors.password">
                                        <o-input type="password" v-model="form.password" model-value="" maxlength="100"> </o-input>
                                    </o-field>
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
                team_name: '',
                name: '',
                email: '',
                password: ''
            },
            errors: {
                team_name: '',
                name: '',
                email: '',
                password: ''
            }
        }
    },
    methods: {
        submit() {
            this.$axios.post('/api/team', this.form)
                .then(response => {
                    this.$router.push('/team')
                })
                .catch(error => {
                    console.log(error.response.data)

                    if(error.response.data.team_name) {
                        this.errors.team_name = error.response.data.team_name[0]
                    }

                    if(error.response.data.name) {
                        this.errors.name = error.response.data.name[0]
                    }

                    if(error.response.data.email) {
                        this.errors.email = error.response.data.email[0]
                    }

                    if(error.response.data.password) {
                        this.errors.password = error.response.data.password[0]
                    }
                })
        },
        getUser() {
            this.$axios.get('/api/user')
                .then(response => {
                    console.log(response.data.name)
                    this.form.name = response.data.name
                    this.form.email = response.data.email
                })
                .catch(error => {
                    console.log(error.response.data)
                })
        }
    },
}
</script>