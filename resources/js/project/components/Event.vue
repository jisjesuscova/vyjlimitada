<template>
    <div>
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 margin-left">
                        <h1>
                            Eventos
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">
                                <router-link to="/add_event" class="btn btn-block btn-success">Agregar</router-link>
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
                                    <o-table-column field="event_name" label="Nombre del Evento" v-slot="p">
                                        {{ p.row.event_name }}  
                                    </o-table-column>
                                    <o-table-column field="ticket_quantity" label="N° de Entradas" v-slot="p">
                                        {{ p.row.ticket_quantity }}
                                    </o-table-column>
                                    <o-table-column field="event_date" label="Fecha del Evento" v-slot="p">
                                        {{ formatDate(p.row.event_date) }}
                                    </o-table-column>
                                    <o-table-column field="" label="" v-slot="p">
                                        <div class="btn-group">
                                            <router-link :to="`/ticket/${p.row.id}`" class="btn btn-success mr-2">
                                                <i class="fa-solid fa-eye"></i>
                                            </router-link>
                                            <o-button variant="danger" @click="deleteEvent(p.row.id)">
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
            this.$axios.get('api/event/'+ id +'?page='+this.currentPage).then((res) => {
                this.posts = res.data.data;
                this.isLoading = false;
            })
        },
        deleteEvent(id) {
            if (confirm("¿Estás seguro de que deseas eliminar el registro?")) {
                this.$axios.delete('api/event/'+id).then((res) => {
                    
                });

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
        formatDate(date) {
            if (!date) return null;
            const [year, month, day] = date.split('-');
            return `${day}-${month}-${year}`;
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