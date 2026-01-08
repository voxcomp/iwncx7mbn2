@extends('layouts.app',['fullwidth'=>true, 'wall'=>true, 'hideHeader'=>true])

@section('title')
	PROMISE WALL
@stop

@section('content')
<div class="grid">
	<div class="grid-sizer"></div>
	
</div>
@stop

@section('footer')
<script>
/*
	var $grid = $('.grid').masonry({
	  // options
	  itemSelector: '.grid-item',
	  columnWidth: '.grid-sizer',
	  percentPosition: true
	});
	$grid.imagesLoaded().progress( function() {
	  $grid.masonry('layout');
	});
*/
	
	
$( function() {
  var $container = $('.grid').masonry({
	  itemSelector: '.grid-item',
	  columnWidth: '.grid-sizer',
	  percentPosition: true
  });

  $(document).ready(function() {
	    var $items = getItems();
	    $container.masonryImagesReveal( $items );
  });
  
});

$.fn.masonryImagesReveal = function( $items ) {
  var msnry = this.data('masonry');
  var itemSelector = msnry.options.itemSelector;
  // hide by default
  $items.hide();
  // append to container
  this.append( $items );
  $items.imagesLoaded().progress( function( imgLoad, image ) {
    // get item
    // image is imagesLoaded class, not <img>, <img> is image.img
    var $item = $( image.img ).parents( itemSelector );
    // un-hide item
    $item.show();
    // masonry does its thing
    msnry.appended( $item );
  });
  
  return this;
};

function getItems() {
  var items = '@foreach($donations as $donation)<div class="grid-item"><div class="wall-item"><img class="img-responsive" src="/storage/{{$donation->photo}}"><div class="title">{{$donation->memoryof}}</div></div></div>@endforeach';
  return $( items );
}	
	
	
</script>
@stop