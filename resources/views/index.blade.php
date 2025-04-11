@extends('layouts.app')

@section('content-page')
    <style>
        .note-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.2);
            padding: 12px 16px;
            width: 500px;
            transition: all 0.3s ease-in-out;
        }

        .note-card input,
        .note-card textarea {
            width: 100%;
            border: none;
            outline: none;
            font-size: 16px;
            resize: none;
            background: transparent;
            margin-bottom: 8px;
        }

        .note-card.collapsed .note-title,
        .note-card.collapsed .note-actions {
            display: none;
        }

        .note-actions {
            display: flex;
            justify-content: flex-end;
        }

        .note-actions button {
            background: #f1f3f4;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        .note-actions button:hover {
            background: #e0e0e0;
        }

        .note-description {
            transition: all 0.3s ease;
            padding-top: 5px;
        }
    </style>

    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a> --}}
                    <a href="{{ route('admin.customer.create') }}" class="btn btn-primary btn-round">Add Customer</a>
                </div>
            </div>

            {{-- Stats section (as is) --}}
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Products</p>
                                        <h4 class="card-title" id="productCount">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Customers</p>
                                        <h4 id="customerCount">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-luggage-cart"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Sales</p>
                                        <h4 id="sales">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Transcation</p>
                                        <h4 id="transaction">₹ 0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes Section --}}
            <form id="noteForm">
                @csrf
                <div class="note-card collapsed" id="noteCard">
                    <input type="text" name="title" placeholder="Add title" class="note-title" id="noteTitle">
                    <textarea name="description" rows="1" placeholder="Take a note..." id="noteDescription"></textarea>
                    <div class="note-actions">
                        <button type="submit">Save</button>
                        <button type="button" onclick="closeNote()">Close</button>
                    </div>
                </div>
            </form>

            <div id="notesList" class=" row mt-3"></div>
        </div>
    </div>

    <script>
        const noteCard = document.getElementById('noteCard');
        const noteDescription = document.getElementById('noteDescription');
        const noteTitle = document.getElementById('noteTitle');

        // Expand card on focus
        noteDescription.addEventListener('focus', () => {
            noteCard.classList.remove('collapsed');
        });

        // Load existing notes on page load
        window.addEventListener('DOMContentLoaded', fetchNotes);

        // Handle form submission
        document.getElementById('noteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitNote();
        });

        function fetchNotes() {
            fetch("{{ route('notes.index') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notesList = document.getElementById('notesList');
                        notesList.innerHTML = '';
                        data.notes.forEach(note => addNoteToList(note));
                    }
                })
                .catch(error => console.error('Error fetching notes:', error));
        }

        function submitNote() {
            const title = noteTitle.value.trim();
            const description = noteDescription.value.trim();

            if (!title && !description) return;

            fetch("{{ route('notes.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        title,
                        description
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchNotes(); // Or use: addNoteToList(data.note);
                        closeNote();
                    } else {
                        alert("Something went wrong!");
                    }
                })
                .catch(error => console.error('Error submitting note:', error));
        }

        function closeNote() {
            noteTitle.value = '';
            noteDescription.value = '';
            noteCard.classList.add('collapsed');
        }

        function addNoteToList(note) {
            const notesList = document.getElementById('notesList');

            const col = document.createElement('div');
            col.className = 'col-sm-2 mb-3'; // 4 notes per row
            col.setAttribute('data-id', note.id);

            const card = document.createElement('div');
            card.className = 'card h-100';

            card.innerHTML = `
        <div class="card-body" >
            <h5 class="card-title note-toggle" style="cursor: pointer;">${note.title || '(No Title)'}</h5>
            <p class="card-text note-description" style="display: none;">${note.description}</p>
            <button class="btn btn-sm btn-danger delete-note mt-3" style="display: none;">Delete</button>
            </div>
    `;

            card.querySelector('.note-toggle').addEventListener('click', function() {
                const description = card.querySelector('.note-description');
                const deleteBtn = card.querySelector('.delete-note');
                const isVisible = description.style.display === 'block';

                description.style.display = isVisible ? 'none' : 'block';
                deleteBtn.style.display = isVisible ? 'none' : 'inline-block';
            });

            card.querySelector('.delete-note').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const noteId = note.id;

                        fetch(`admin/notes/destroy/${noteId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    notesList.removeChild(col); // Remove the note card from UI
                                    Swal.fire(
                                        'Deleted!',
                                        'Your note has been deleted.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'An error occurred while deleting.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error("Delete error:", error);
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting.',
                                    'error'
                                );
                            });
                    }
                });
            });

            col.appendChild(card);
            notesList.prepend(col);
        }
    </script>
@endsection
