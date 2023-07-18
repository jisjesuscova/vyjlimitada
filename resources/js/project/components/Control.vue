<template>
    <div>
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 margin-left">
                        <h1>
                            Controladores
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">
                                <router-link to="/add_control" class="btn btn-block btn-success">Agregar</router-link>
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Datos</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <o-table :loading="isLoading" :data="posts.current_page && posts.data.length == 0 ? [] : posts.data">
                                    <o-table-column field="id" label="Id" numeric v-slot="p">
                                        {{ p.row.id }}
                                    </o-table-column>
                                    <o-table-column field="name" label="Nombre Completo" v-slot="p">
                                        {{ p.row.name }}  
                                    </o-table-column>
                                    <o-table-column field="event_name" label="Nombre del Evento" v-slot="p">
                                        {{ p.row.event_name }}  
                                    </o-table-column>
                                    <o-table-column field="status_id" label="" v-slot="p">
                                        <span class="badge badge-success" v-if="p.row.status_id == 1">Activado</span>
                                        <span class="badge badge-secondary" v-else>Desactivado</span>
                                    </o-table-column>
                                    <o-table-column field="" label="" v-slot="p">
                                        <div class="btn-group">
                                            <router-link :to="`/control/edit/${p.row.control_id}`" class="btn btn-success mr-2">
                                                <i class="fa-solid fa-pencil"></i>
                                            </router-link>
                                            <o-button variant="danger" @click="deleteControl(p.row.control_id)">
                                                <i class="fa-solid fa-trash"></i>
                                            </o-button>
                                        </div>
                                    </o-table-column>
                                </o-table>
                                <hr />
                                <o-pagination
                                v-if="posts.current_page && posts.data.length > 0"
                                @change="updatePage"
                                :total="posts.total"
                                v-model:current="currentPage"
                                :range-before="2"
                                :range-after="2"
                                order="centered"
                                :size="small"
                                :simple="false"
                                :rounded="true"
                                :per-page="posts.per_page"
                                >
                                </o-pagination>
                            </div>
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
            id: '',
            posts: [],
            isLoading: true,
            currentPage: 1
        }
    },
    methods: {
        updatePage() {
            setTimeout(this.listPage, 200);
        },
        listPage(id) {
            this.isLoading = true;
            this.$axios.get('api/control/'+ id +'?page='+this.currentPage).then((res) => {
                this.posts = res.data.data;
                this.isLoading = false;
            })
        },
        deleteControl(id) {
            if (confirm("¿Estás seguro de que deseas eliminar el registro?")) {
                this.$axios.delete('api/control/'+id).then((res) => {
                    
                })

                axios.get('/session-data')
                .then(response => {
                    this.id = response.data.id;
                    this.listPage(this.id); // pasa this.id a listPage()
                })
                .catch(error => {
                    console.log(error);
                });
            }
        },
        async getUser() {
            try {
                // Obtén el ID de sesión de Laravel
                const UserIdResponse = await this.$axios.get('/user_id');
                const userId = UserIdResponse.data.user_id;
            } catch (error) {
                console.log(error);
            }
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