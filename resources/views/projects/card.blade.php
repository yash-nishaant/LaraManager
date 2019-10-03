<div class="card flex flex-col" style="height: 200px;">
    <h3 class="font-normal text-xl py-4 pl-4 -ml-5 border-l-4" style="border-color: rgb(19, 155, 218);">
        <a href="{{ $project->path() }}" class="text-default no-underline">{{$project->title}}</a>
    </h3>

    <div class="text-grey mb-4 flex-1">{{ Str::limit($project->description, 200) }}</div>

    @can('manage', $project)
        <footer>
            <form method="POST" action="{{$project->path()}}" class="text-right">
                @method('DELETE')
                @csrf
                <button type="submit" class="text-xs">Delete</button>
            </form>
        </footer>
    @endcan
</div>
