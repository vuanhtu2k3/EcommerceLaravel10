@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">{{ __('My Account') }}</a></li>
                    <li class="breadcrumb-item">{{ __('Settings') }}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('front.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">{{ __('Change Password') }}</h2>
                        </div>
                        <form action="" method="post" id="changePasswordForm" name="changePasswordForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">{{ __('Old Password') }}</label>
                                        <input type="password" name="old_password" id="old_password" class="form-control"
                                            placeholder="{{ __('Old Password') }}">

                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">{{ __('New Password') }}</label>
                                        <input type="password" name="new_password" id="new_password"
                                            placeholder="{{ __('New Password') }}" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">{{ __('Confirm Password') }}</label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                            placeholder="{{ __('Confirm Password') }}" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button id="submit" name="submit" type="submit"
                                            class="btn btn-dark">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#changePasswordForm").submit(function(event) {
                event.preventDefault();

                $("button[type=submit]").prop('disabled', true);

                $.ajax({
                    url: "{{ route('account.processChangePassword') }}",
                    type: "POST",
                    data: $(this).serializeArray(),
                    success: function(response) {
                        $("#submit").prop('disabled', false);
                        if (response.status == 'true') {
                            window.location.href = '{{ route('account.changePassword') }}';
                        } else {
                            var errors = response.errors;
                            if (errors.old_password) {
                                $("#old_password").addClass('is-invalid')
                                    .siblings('p').addClass('invalid-feedback').html(response
                                        .old_password)
                            } else {
                                $("#old_password").removeClass('is-invalid')
                                    .siblings('p').removeClass('invalid-feedback').html("")
                            }
                            if (errors.new_password) {
                                $("#new_password").addClass('is-invalid')
                                    .siblings('p').addClass('invalid-feedback').html(response
                                        .new_password)
                            } else {
                                $("#new_password").removeClass('is-invalid')
                                    .siblings('p').removeClass('invalid-feedback').html("")
                            }
                            if (errors.confirm_password) {
                                $("#confirm_password").addClass('is-invalid')
                                    .siblings('p').addClass('invalid-feedback').html(response
                                        .confirm_password)
                            } else {
                                $("#confirm_password").removeClass('is-invalid')
                                    .siblings('p').removeClass('invalid-feedback').html("")
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
