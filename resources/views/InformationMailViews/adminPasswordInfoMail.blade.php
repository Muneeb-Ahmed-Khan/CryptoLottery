<!DOCTYPE html>
<html>
<head>
	<title>{{config('app.name')}}</title>
</head>
<body>
    @foreach($user as $u)
    <p>You can Change your password later inside your account afer login {{ $u->name }}  {{ $u->email }}  {{ $u->password }}.</p>
    @endforeach
</body>
</html>