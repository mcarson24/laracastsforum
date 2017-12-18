<div class="panel panel-default">
    @if ($thread->replies)
        <replies @added="repliesCount++" 
                 @removed="repliesCount--">
        </replies>
    @endif
</div>
