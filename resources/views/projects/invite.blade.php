<div class="card flex flex-col mt-3">
    <h3 class="font-normal text-xl py-4 pl-4 -ml-5 border-l-4" style="border-color: rgb(19, 155, 218);">
        <strong>Invite a User</strong>
    </h3>

    <form method="POST" action="{{$project->path() . '/invitations'}}">
        @csrf
        <div class="mb-3">
            <input type="email" name="email" class="border-grey rounded w-full py-2 px-3" placeholder="Email address">
        </div>
        <button type="submit" class="text-xs">Invite</button>
    </form>
    @include('errors', ['bag' => 'invitations'])
</div>