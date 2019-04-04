<li id="tabInstituciones" class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#contenidoInstituciones" role="tab" aria-controls="contenidoInstituciones" aria-selected="true" v-on:click="ocultarPestana">Buscador</a>
</li>

@push('scripts')
    <script>
        var tabIndex = new Vue(
        {
            delimiters: ['${', '}'],
            el: '#tabInstituciones',
            data: {

            },
            created() {

            },
            methods: {
                ocultarPestana: function () {
                    $("#tabModificarInstitucion").css({"display": "none"});
                }
            }
        })
    </script>
@endpush
