<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
    @forelse($projects as $project)
        <a href="{{ $project->path() }}">
            <h3>{{ $project->title }}</h3>
        </a>
    @empty
        <li>Hooray! Index is empty!</li>
    @endforelse
</body>
</html>
