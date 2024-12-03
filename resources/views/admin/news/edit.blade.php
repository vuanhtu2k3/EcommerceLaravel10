@extends('admin.layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update News</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('news.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('news.update', $new->id) }}" id="updatedNewForm" name="updatedNewForm" method="post">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        placeholder="Title" value="{{ $new->title }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea rows="5" name="description" id="description" class="summernote">{{ $new->description }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="published_at">Published At</label>
                                    <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                                        value="{{ $new->published_at ? \Carbon\Carbon::parse($new->published_at)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('news.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#updatedNewForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('news.update', $new->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        window.location.href = "{{ route('news.index') }}";
                    } else {
                        alert(response["message"] || "Update failed!");
                    }
                },
                error: function(xhr) {
                    $("button[type=submit]").prop('disabled', false);
                    alert("Error: " + xhr.statusText);
                }
            });
        });
    </script>
@endsection
