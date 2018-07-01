<div class="container">
  <div class="row">
    @for ($i=0; $i<count($links); $i++)
      <div class="{{isset($links[$i]->colSm)?'col-sm-'.$links[$i]->colSm:''}} {{isset($links[$i]->colSmOffset)?'offset-sm-'.$links[$i]->colSmOffset:''}} {{isset($links[$i]->colMd)?'col-md-'.$links[$i]->colMd:''}} {{isset($links[$i]->colMdOffset)?'offset-md-'.$links[$i]->colMdOffset:''}}">
        <a class="btn btn-{{$links[$i]->btnClass}}" href="{{$links[$i]->url}}">
          <i class="{{$links[$i]->iconClass}}"></i>
        </a>
      </div>
    @endfor
  </div>
</div>
<hr>