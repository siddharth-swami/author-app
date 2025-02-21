@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>User Profile</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Email</th>
                    <td>{{ $profile['email'] }}</td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td>{{ $profile['first_name'] }}</td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>{{ $profile['last_name'] }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ ucfirst($profile['gender']) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
