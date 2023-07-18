<template>
    <div>
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 margin-left">
                        <h1>
                            Lector Qr
                        </h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Lector</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <qr-stream @decode="onDecode" class="qr-reader"></qr-stream>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> 
    </div>
</template>
  
<script>
import { defineComponent, ref } from 'vue';

export default defineComponent({
  name: 'QrCode',
  setup() {
    const result = ref(null);
    const idRef = ref(null);

    async function validateControler(id, value) {
      try {
        const response = await axios.get(`api/control/status/${id}`);
        console.log(response.data);
        if (response.data.data == 1)  {
            window.location.href = "https://jesuscova.cl/ticket/check/" + value;
        } else {
            alert('Usted no tiene los permisos para validar la entrada');
        }
      } catch (error) {
        console.log(error);
      }
    }

    function onDecode(data) {
      result.value = data;
      validateControler(idRef.value, data);
    }

    axios.get('/session-data')
        .then(response => {
            idRef.value = response.data.id;
        })
        .catch(error => {
            console.log(error);
        });

    return {
      result,
      onDecode,
    };
  },
});
</script>

<style>
.qr-reader {
    width: 100%;
    border: 1px solid #ccc;
}
</style>