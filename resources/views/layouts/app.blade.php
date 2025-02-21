<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authors App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            height: 100%;
        }
        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .content {
            flex: 1;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="w-100 px-2">
        @if(session()->has('token'))
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="/dashboard">Authors App</a>
                <div class="ml-auto">
                    <a href="/profile" class="btn btn-info btn-sm">Profile</a>
                    <form method="POST" action="/logout" class="d-inline">
                        @csrf
                        <button class="btn btn-danger btn-sm">Logout</button>
                    </form>
                </div>
            </nav>
        @endif
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>
    <footer class="bg-light text-center py-3 mt-4">
        @if(Session::has('user'))
            <p class="mb-0">Logged in as: <strong>{{ Session::get('user')['first_name'] }} {{ Session::get('user')['last_name'] }}</strong></p>
        @else
            <p class="mb-0">Welcome to Authors App</p>
        @endif
    </footer>
</div>
</body>
</html>
