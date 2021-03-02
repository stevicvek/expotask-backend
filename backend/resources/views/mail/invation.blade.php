<h1>{{ $user->fullname }}, you have been invited to {{ $team->name }}!</h1>

<a href="http://localhost:82/api/v/1/team/approve?team={{ $team->id }}&code={{ $code }}">Click here!</a>