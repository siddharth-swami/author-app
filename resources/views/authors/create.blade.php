@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Add New Author</h3>

    <form id="addAuthorForm">
        @csrf
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <div class="form-group mt-2">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>

        <div class="form-group mt-2">
            <label>Birthday</label>
            <input type="date" name="birthday" class="form-control" required>
        </div>

        <div class="form-group mt-2">
            <label>Biography</label>
            <textarea name="biography" class="form-control" required></textarea>
        </div>

        <div class="form-group mt-2">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="form-group mt-2">
            <label>Place of Birth</label>
            <input type="text" name="place_of_birth" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">Add Author</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $("#addAuthorForm").submit(function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        // Convert FormData to JSON object
        let dataObject = {};
        formData.forEach((value, key) => {
            dataObject[key] = value;
        });

        // Format birthday to ISO 8601
        if (dataObject.birthday) {
            dataObject.birthday = new Date(dataObject.birthday).toISOString();
        }

        $.ajax({
            url: "{{ route('author.store') }}",
            type: "POST",
            data: JSON.stringify(dataObject),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function(response) {
                alert(response.message);
                window.location.href = "/dashboard";
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors || {};
                let errorMessage = xhr.responseJSON.message || "Failed to add author.";

                for (let key in errors) {
                    errorMessage += "\n" + errors[key].join(", ");
                }

                alert(errorMessage);
            }
        });
    });
});
</script>
@endsection
