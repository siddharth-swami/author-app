@extends('layouts.app')

@section('content')
<a href="/add-book" class="btn btn-primary mb-3">Add New Book</a>
<a href="/add-author" class="btn btn-primary mb-3">Add New Author</a>
<h3>Authors</h3>
<div class="table-responsive">
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search authors...">
    <table id="authorsTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Place of Birth</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
<div class="d-flex justify-content-between align-items-center mt-3">
    <span id="entries-info"></span>
    <div id="pagination" class="btn-group"></div>
</div>

<script>
    $(document).ready(function () {
        let token = "{{ session('token') }}"; 
        let currentPage = 1;
        let totalPages = 1;
        let totalRecords = 0;
        let limit = 10;

        let table = $('#authorsTable').DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            searching: false,
            info: false,
            ordering: true,
            ajax: function (data, callback, settings) {
                let orderBy = 'id';
                let direction = 'ASC';
                if (data.order.length > 0) {
                    let columnIndex = data.order[0].column;
                    orderBy = data.columns[columnIndex].data;
                    direction = data.order[0].dir.toUpperCase();
                }
                $.ajax({
                    url: "{{ config('services.thirdparty_api.api') }}/authors",
                    type: "GET",
                    headers: { "Authorization": "Bearer " + token },
                    data: {
                        query: $('#searchInput').val(),
                        orderBy: orderBy,
                        direction: direction,
                        limit: limit,
                        page: currentPage,
                    },
                    success: function (response) {
                        totalPages = response.total_pages;
                        totalRecords = response.total_results;
                        let authors = response.items;

                        let fetchBooksPromises = authors.map(author => {
                            return $.ajax({
                                url: `{{ config('services.thirdparty_api.api') }}/authors/${author.id}`, 
                                type: "GET",
                                headers: { "Authorization": "Bearer " + token }
                            }).then(booksResponse => {                                
                                author.hasBooks = booksResponse.books.length > 0;
                            });
                        });
                        
                        Promise.all(fetchBooksPromises).then(() => {
                            callback({ data: response.items });
                            updatePagination();
                        });
                        console.log(response.items);
                        // callback({ data: response.items });
                        // updatePagination();
                    }
                });
            },
            columns: [
                { data: "id" },
                { data: "first_name" },
                { data: "last_name" },
                { data: "birthday" },
                { data: "gender" },
                { data: "place_of_birth" },
                {
                    data: "id",
                    // render: function (data) {
                    //     return `<a href="/author/${data}" class="">View Books</a>`;
                    // }
                    render: function (data, type, row) {
                        let viewBooksBtn = `<a href="/author/${data}" class="btn btn-primary btn-sm">View Books</a>`;
                        let deleteBtn = row.hasBooks ? '' : `<button onclick="deleteAuthor(${data})" class="btn btn-danger btn-sm">Delete</button>`;
                        return `${viewBooksBtn} ${deleteBtn}`;
                    }
                }
            ]
        });

        function debounce(func, delay) {
            let timer;
            return function () {
                clearTimeout(timer);
                timer = setTimeout(func, delay);
            };
        }

        // Search bar with debounce
        $('#searchInput').on('keyup', debounce(function () {
            currentPage = 1;
            table.ajax.reload();
        }, 500));

        function updatePagination() {
            $('#pagination').html('');
            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    $('#pagination').append(`<button class="page-btn btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-light'}" data-page="${i}">${i}</button>`);
                }
            }
            $('#entries-info').text(`Showing ${(currentPage - 1) * limit + 1} to ${Math.min(currentPage * limit, totalRecords)} of ${totalRecords} entries`);
        }

        $(document).on('click', '.page-btn', function () {
            currentPage = $(this).data('page');
            table.ajax.reload();
        });

    });
    function deleteAuthor(authorId) {
        if (confirm("Are you sure you want to delete this author?")) {
            fetch(`/authors/${authorId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => console.error("Error:", error));
        }
    }

</script>



@endsection
