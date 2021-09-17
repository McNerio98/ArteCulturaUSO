<link href="{{ asset('css/bannerSolicitud.css') }}" rel="stylesheet">
<div class="containerSolicitud mb-4 md-md-1">
    <div id='show_bg_2'>
        <span class="title">Arte y Cultura en Sonsonate</span>
        <p class="parrafoInfo">
        ¿Eres artista o posees un grupo artístico? ¿Coordinas un colectivo artístico o administras alguna institución que promueva la cultura y el arte? Crea una cuenta y obtén acceso a nuestra plataforma para que puedas publicar tu contenido.
        </p>
        <br>
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
