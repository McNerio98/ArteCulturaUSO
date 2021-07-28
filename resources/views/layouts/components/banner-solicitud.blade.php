<link href="{{ asset('css/bannerSolicitud.css') }}" rel="stylesheet">
<div class="containerSolicitud">
    <div id='show_bg_2'>
        <span class="title">Arte y Cultura en Sonsonate</span>
        <p class="parrafoInfo">
            ¿Eres un artista? Crea una cuenta y obtén acceso a nuestra plataforma para que
            puedas publicar tu contenido.
        </p>
        <div class="text-center mt-2">
            <a  data-toggle="modal" data-target="#requestAccountModal" style="text-decoration:none; color:white" href="#" class="buttonSolicitud">
                Llenar formulario
            </a>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="request">
<div class="modal fade" id="requestAccountModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-user-plus"></i> Solicitar cuenta</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                <request-component></request-component>
        </div>
      </div>
    </div>
  </div>
</div>