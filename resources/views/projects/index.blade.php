<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
    @foreach($projects as $project)
        <h2>{{ $project->title }}</h2>

        <p>{{ $project->description }}</p>
    @endforeach
</body>
</html>
