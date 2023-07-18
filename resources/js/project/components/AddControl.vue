<template>
    <div>
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Agregar Controlador</h3>
                            </div>
                            <form @submit.prevent="submit">
                                <div class="card-body">
                                    <o-field label="Nombre Completo" :variant="errors.name ? 'danger' : 'primary'" :message="errors.name">
                                        <o-input type="text" v-model="form.name" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <o-field label="Correo del Controlador" :variant="errors.email ? 'danger' : 'primary'" :message="errors.email">
                                        <o-input type="email" v-model="form.email" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <o-field label="Contraseña del Controlador" :variant="errors.password ? 'danger' : 'primary'" :message="errors.password">
                                        <o-input type="password" v-model="form.password" model-value="" maxlength="100"> </o-input>
                                    </o-field>
                                    <o-field label="Evento a Trabajar" :variant="errors.event_id ? 'danger' : 'primary'" :message="errors.event_id">
                                        <o-select v-model="form.event_id" model-value="" expanded>
                                            <option value="" selected disabled> - Seleccionar - </option>
                                            <option v-for="post in posts" :key="post.id" :value="post.id">{{ post.event_name }}</option>
                                        </o-select>
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
            posts: [],
            form: {
                name: '',
                email: '',
                password: '',
                event_id: ''
            },
            errors: {
                name: '',
                email: '',
                password: '',
                event_id: ''
            }
        }
    },
    methods: {
        listPage(id) {
            this.$axios.get('api/event/all/'+ id).then((res) => {
                this.posts = res.data.data;
            })
        },
        submit() {
            this.$axios.post('/api/control', this.form)
                .then(response => {
                    this.$router.push('/control')
                })
                .catch(error => {
                    console.log(error.response.data)

                    if(error.response.data.name) {
                        this.errors.name = error.response.data.name[0]
                    }

                    if(error.response.data.email) {
                        this.errors.email = error.response.data.email[0]
                    }

                    if(error.response.data.password) {
                        this.errors.password = error.response.data.password[0]
                    }

                    if(error.response.data.event_id) {
                        this.errors.event_id = error.response.data.event_id[0]
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