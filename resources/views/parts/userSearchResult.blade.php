@if($users->count()>0)
	<h2>{{$users->count()}} Users Found:</h2>
	@foreach($users as $user)
		<div>{{$user->lname}}, {{$user->fname}} (<a href="{{route('user.profile',$user->slug)}}">{{$user->username}}</a>) - <a href="mailto:{{$user->email}}">{{$user->email}}</a></div>
	@endforeach
@else
	<h2>No Users Found.</h2>
@endif