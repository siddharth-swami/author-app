@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
                <form id="loginForm">
                    @csrf  {{-- CSRF Token --}}
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        <div class="text-danger small" id="emailError"></div>
                    </div>
                    <div class="form-group mt-2">
                        <label>Password</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="6">
                        <div class="text-danger small" id="passwordError"></div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 w-100">Login</button>
                </form>
                <div id="loginError" class="text-danger mt-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#loginForm").submit(function(event){
        event.preventDefault();
        
        $("#emailError, #passwordError, #loginError").text("");

        let email = $("#email").val().trim();
        let password = $("#password").val().trim();
        let isValid = true;

        if (email === "" || !email.match(/^\S+@\S+\.\S+$/)) {
            $("#emailError").text("Enter a valid email.");
            isValid = false;
        }
        if (password.length < 6) {
            $("#passwordError").text("Password must be at least 6 characters.");
            isValid = false;
        }
        if (!isValid) return;

        $.ajax({
            url: "/login",
            method: "POST",
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                window.location.href = "/dashboard";
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.email) $("#emailError").text(errors.email[0]);
                    if (errors.password) $("#passwordError").text(errors.password[0]);
                } else {
                    $("#loginError").text("Invalid credentials or server error.");
                }
            }
        });
    });
});
</script>
@endsection
