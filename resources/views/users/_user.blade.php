<div class="list-group-item">
  <img src="{{ $user->gravatar() }}" class="mr-3" alt="{{ $user->name }}" width="32">
  <a href="{{ route('users.show',$user) }}">{{ $user->name }}</a>
  @can('destroy',$user)
    <form action="{{ route('users.destroy',$user->id) }}" method="post" class="float-right">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
      <button class="btn btn-sm btn-danger delete-btn" type="submit">删除</button>
    </form>
  @endcan
</div>
