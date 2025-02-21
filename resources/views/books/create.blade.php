@extends('layouts.app')

@section('content')
<h3>Add New Book</h3>
<form id="addBookForm">
    @csrf
    <div class="form-group">
        <label>Author</label>
        <select name="author_id" id="authorDropdown" class="form-control"></select>
    </div>
    <div class="form-group mt-2">
        <label>Title</label>
        <input type="text" name="title" class="form-control">
    </div>
    <div class="form-group mt-2">
        <label>Release Date</label>
        <input type="date" name="release_date" class="form-control">
    </div>

    <div class="form-group mt-2">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="form-group mt-2">
        <label>ISBN</label>
        <input type="text" name="isbn" class="form-control">
    </div>
    <div class="form-group mt-2">
        <label>Format</label>
        <input type="text" name="format" class="form-control">
    </div>
    <div class="form-group mt-2">
        <label>Number of Pages</label>
        <input type="number" name="number_of_pages" class="form-control">
    </div>
    <button type="submit" class="btn btn-success mt-3 w-100">Add Book</button>
</form>

<script>
$(document).ready(function(){
    $.ajax({
        url: "{{ config('services.thirdparty_api.api') }}/authors",
        type: "GET",
        headers: {
            "Authorization": "Bearer {{ session('token') }}",
        },
        success: function(response) {
            if (response.items && response.items.length > 0) {
                response.items.forEach(author => {
                    $("#authorDropdown").append(
                        `<option value="${author.id}">${author.first_name} ${author.last_name}</option>`
                    );
                });
            } else {
                console.warn("No authors found in API response.");
            }
        },
        error: function(xhr) {
            console.error("Error fetching authors:", xhr.responseText);
        }
    });


    $("#addBookForm").submit(function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        let dataObject = {};
        formData.forEach((value, key) => {
            dataObject[key] = value;
        });

        if (dataObject.release_date) {
            let formattedDate = new Date(dataObject.release_date).toISOString();
            dataObject.release_date = formattedDate;
        }

        $.ajax({
            url: "/add-book",
            type: "POST",
            data: JSON.stringify(dataObject),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                "Content-Type": "application/json"
            },
            dataType: "json",
            success: function(response) {
                alert("Book added successfully!");
                window.location.href = "/dashboard";
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors || {};
                let errorMessage = xhr.responseJSON.message || "Failed to add book.";
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
