<div class="panel-heading">
    by <a href="#">{{ $reply->owner->name }}</a>
    <span class="reply-time">{{ $reply->created_at->diffForHumans() }}</span>
</div>
<div class="panel-body">
    {{ $reply->body }}
</div>