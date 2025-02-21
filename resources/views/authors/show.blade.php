@extends('layouts.app')

@section('content')
<h3>Author Details</h3>
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title" id="authorName"></h5>
        <p class="card-text"><strong>Biography:</strong> <span id="authorBio"></span></p>
        <p class="card-text"><strong>Birthday:</strong> <span id="authorBirthday"></span></p>
        <p class="card-text"><strong>Gender:</strong> <span id="authorGender"></span></p>
        <p class="card-text"><strong>Place of Birth:</strong> <span id="authorPlace"></span></p>
    </div>
</div>

<h3>Books by Author</h3>
<div class="table-responsive">
    <table id="booksTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Release Date</th>
                <th>Format</th>
                <th>Pages</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<script>
$(document).ready(function() {
    let authorId = "{{ $id }}";
    let token = "{{ session('token') }}";
    let csrfToken ='{{ csrf_token() }}';
    
    // Fetch Author Details
    $.ajax({
        url: `{{ config('services.thirdparty_api.api') }}/authors/${authorId}`,
        type: "GET",
        headers: { "Authorization": "Bearer " + token },
        success: function(response) {
            $("#authorName").text(`${response.first_name} ${response.last_name}`);
            $("#authorBio").text(response.biography || "N/A");
            $("#authorBirthday").text(response.birthday.split("T")[0]);
            $("#authorGender").text(response.gender);
            $("#authorPlace").text(response.place_of_birth);
        }
    });

    // Initialize DataTable for Books
    $('#booksTable').DataTable({
        processing: true,
        serverSide: false,
        paging: false,
        searching: false,
        ajax: {
            url: `{{ config('services.thirdparty_api.api') }}/authors/${authorId}`,
            type: "GET",
            headers: { "Authorization": "Bearer " + token },
            dataSrc: "books"
        },
        columns: [
            { data: "id" },
            { data: "title" },
            { data: "release_date", render: function(data) { return data.split("T")[0]; }},
            { data: "format" },
            { data: "number_of_pages" },
            {
                data: "id",
                render: function(data) {
                    return `<button class="btn btn-danger btn-sm delete-book" data-id="${data}">Delete</button>`;
                }
            }
        ]
    });

    // Delete Book
    $(document).on("click", ".delete-book", function() {
        let bookId = $(this).data("id");
        if (confirm("Are you sure?")) {
            $.ajax({
                url: `/book/${bookId}`,
                type: "DELETE",
                headers: { 
                    "Authorization": "Bearer " + token,
                    "X-CSRF-TOKEN": csrfToken 
                },
                success: function() {
                    $('#booksTable').DataTable().ajax.reload();
                }
            });
        }
    });
});
</script>
@endsection
