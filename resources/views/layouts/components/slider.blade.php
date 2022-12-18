<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">

    <ol class="carousel-indicators">
      @if(count($promos) > 0)
        @foreach($promos as $e)
          <li data-target="#carouselExampleCaptions" data-slide-to="0" class="{{$loop->first?'active':''}}"></li>
        @endforeach
      @else
        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
      @endif
    </ol>
    
    <div class="carousel-inner">
      @if(count($promos) > 0)
        @foreach($promos as $e)
          <div class="carousel-item {{$loop->first ? 'active':''}}">
              <img src="{{asset('files/images/' . $e->name_img)}}" class="d-block w-100 sliderImage" style="object-fit: cover" height="450px" alt="...">
              <div class="carousel-caption d-none d-md-block ac-caption-carousel">
                <h5>{{$e->title}}</h5>
                <p>{{$e->content}} <a href="javascript:void(0);" @click="onShowPromo({{$e->item_id}}, {{$e->type_ads}})">Ver mas</a></p>
              </div>
            </div>    
        @endforeach
      @else
          <div class="carousel-item active">
              <img src="{{asset('images/popular_empty.jpg')}}" class="d-block w-100 sliderImage" style="object-fit: cover" height="450px" alt="...">
              <div class="carousel-caption d-none d-md-block ">
                <h5></h5>
                <p>No se tienen promociones registradas aun.</p>
              </div>
            </div>       
      @endif
    </div>
    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>