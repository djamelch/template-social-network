@extends('templates.default')

@section('content')
    <div class="row">
        <div class="col-lg-5">
            @include('user.partial.userblock') <!-- We have already made a userblock that displays our avatar and our name and location. -->
            <hr>

            @if(!$statuses->count())
                <p>{{ $user->getFirstNameOrUsername() }} hasn't posted anything yet.</p>
            @else
                @foreach($statuses as $status)
                    <div class="media">
                        <a class="pull-left" href="{{ route('profile.index', [
                        'username' => $status->user->username
                    ]) }}">
                            <img class="media-object" alt="{{ $status->user->getNameOrUsername() }}" src="{{ $status->user->getAvatarUrl() }}">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><a href="{{ route('profile.index', [
                        'username' => $status->user->username
                    ]) }}">{{ $status->user->getNameOrUsername() }}</a></h4>
                            <p>{{ $status->body }}</p>
                            <ul class="list-inline">
                                <li>{{ $status->created_at->diffForHumans() }}</li> <!--  1 hour ago etc. -->
                                @if($status->user->id !== Auth::user()->id)
                                    @if(!Auth::user()->hasLikedStatus($status))
                                        <li><a href="{{ route('status.like', [ 'statusId' => $status->id ]) }}">Like</a></li>

                                    @elseif(Auth::user()->hasLikedStatus($status))
                                        <li><a href="{{ route('status.unlike', ['statusId' => $status->id ]) }}">Unlike</a></li>
                                    @endif
                                @endif

                                @if(Auth::user()->id == $status->user_id)
                                    <li><a href="{{ route('status.delete', ['statusId' => $status->id]) }}">Delete</a></li>
                                @endif
                                <li>{{ $status->likes->count() }} {{ str_plural('like', $status->likes->count()) }}</li>
                            </ul>


                            @foreach($status->paginatingReplies() as $reply)
                                <div class="media">
                                    <a class="pull-left" href="{{ route('profile.index', ['username' => $reply->user->username ]) }}">
                                        <img class="media-object" alt="{{ $reply->user->getNameOrUsername() }}" src="{{ $reply->user->getAvatarUrl() }}">
                                    </a>
                                    <div class="media-body">
                                        <h5 class="media-heading"><a href="{{ route('profile.index', ['username' => $reply->user->username ]) }}">{{ $reply->user->getNameOrUsername() }}</a></h5>
                                        <p>{{ $reply->body }}</p>
                                        <ul class="list-inline">
                                            <li>{{ $reply->created_at->diffForHumans() }}</li>
                                            @if($reply->user->id !== Auth::user()->id)
                                                @if(!Auth::user()->hasLikedStatus($reply))
                                                    <li><a href="{{ route('status.like', [ 'statusId' => $reply->id ]) }}">Like</a></li>

                                                @elseif(Auth::user()->hasLikedStatus($reply))
                                                    <li><a href="{{ route('status.unlike', ['statusId' => $reply->id ]) }}">Unlike</a></li>
                                                @endif
                                            @endif

                                            @if(Auth::user()->id == $reply->user_id)
                                                <li><a href="{{ route('status.delete', ['statusId' => $reply->id]) }}">Delete</a></li>
                                            @endif

                                            <li>{{ $reply->likes->count() }} {{ str_plural('like', $reply->likes->count()) }}</li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                            {!! $status->paginatingReplies()->render() !!}

                            @if($authUserIsFriend || Auth::user()->id === $status->user->id)
                                <form role="form" action="{{ route('status.reply', ['statusId' => $status->id]) }}" method="post">
                                    <div class="form-group{{ $errors->has("reply-{$status->id}") ? ' has-error' : '' }}">
                                        <textarea name="reply-{{$status->id}}" class="form-control" rows="2" placeholder="Reply to this status"></textarea> <!-- $status->id which status we are replying to -->
                                        @if($errors->has("reply-{$status->id}"))
                                            <span class="help-block">{{ $errors->first("reply-{$status->id}") }}</span>
                                        @endif
                                    </div>
                                    <input type="submit" value="Reply" class="btn btn-default btn-sm">
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
        <div class="col-lg-4 col-lg-offset-3">
            @if (Auth::user()->hasFriendRequestPending($user)) <!-- Checks to see if the user has a friend request from the signed in user -->
                <p>Waiting for {{ $user->getNameOrUsername() }} to accept your request</p>

            @elseif(Auth::user()->hasFriendRequestReceived($user))
                <a href="{{ route('friend.accept', ['username' => $user->username ]) }}" class="btn btn-primary">Accept friend request</a>

            @elseif(Auth::user()->isFriendsWith($user))
                <p>You and {{ $user->getFirstNameOrUsername() }} are friends.</p>

                <form action="{{ route('friend.delete', ['username' => $user->username ]) }}" method="post">
                    <input type="submit" value="Unfriend" class="btn btn-danger">
                    {{ csrf_field() }}
                </form>

            @elseif(Auth::user()==$user)

            @else
                <a href="{{ route('friend.add', ['username' => $user->username ]) }}" class="btn btn-primary">Add as friend</a>
            @endif

            <h4>{{ $user->getFirstNameOrUsername() }}'s friends</h4>
            @if(!$user->friends()->count()) <!-- check if the user has friends. -->
                <p>{{ $user->getFirstNameOrUsername() }} has no friends.</p>

            @else
                @foreach($user->friends() as $user)
                    @include('user/partial/userblock') <!-- show the friends in their blocks. -->
                @endforeach
            @endif
        </div>
    </div>
@stop