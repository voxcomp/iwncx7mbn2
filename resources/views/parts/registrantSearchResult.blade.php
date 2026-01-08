@if($registrants->count()>0)
	<h2>{{$registrants->count()}} Registrants Found:</h2>
	@foreach($registrants->sortBy(function($item) { return $item->lname.', '.$item->fname; }) as $registrant)
		<div><a href="{{route('registrant.view',[$registrant->slug])}}">{{$registrant->lname}}, {{$registrant->fname}}</a> (${{$registrant->paid}}, {{date('m/d/Y',strtotime($registrant->created_at))}})</div>
	@endforeach
@else
	<h2>No Registrants Found.</h2>
@endif