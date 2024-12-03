@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">{{ __('HOME') }}</a></li>
                    <li class="breadcrumb-item">{{ __('Register') }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-10">
        <div class="container">
            <div class="login-form">
                <form action="" name="registrationForm" id="registrationForm" method="post">
                    <h4 class="modal-title">{{ __('Register now') }}</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ __('Name') }}" id="name"
                            name="name">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ __('Phone') }}" id="phone"
                            name="phone">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="{{ __('Password') }}" id="password"
                            name="password">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}"
                            id="password_confirmation" name="password_confirmation">
                        <p></p>
                    </div>
                    <div class="form-group small">
                        <a href="#" class="forgot-link">{{ __('Forgot password?') }}</a>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block btn-lg"
                        value="Register">{{ __('Register') }}</button>
                </form>
                <div class="text-center small">{{ __('Already have an account?') }} <a
                        href="{{ route('account.login') }}">{{ __('Login Now') }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        $("#registrationForm").submit(function(event) {
            event.preventDefault();

            $("button[type='submit']").prop('disabled', true);

            $.ajax({
                url: '{{ route('account.processRegister') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);

                    var errors = response.errors;
                    if (response.status == false) {
                        if (errors.name) {
                            $("#name").siblings("p").addClass('invalid-feedback').html(errors.name);
                            $("#name").addClass('is-invalid');
                        } else {
                            $("#name").siblings("p").removeClass('invalid-feedback').html('');
                            $("#name").removeClass('is-invalid');
                        }

                        if (errors.email) {
                            $("#email").siblings("p").addClass('invalid-feedback').html(errors.email);
                            $("#email").addClass('is-invalid');
                        } else {
                            $("#email").siblings("p").removeClass('invalid-feedback').html('');
                            $("#email").removeClass('is-invalid');
                        }

                        if (errors.phone) {
                            $("#phone").siblings("p").addClass('invalid-feedback').html(errors.phone);
                            $("#phone").addClass('is-invalid');
                        } else {
                            $("#phone").siblings("p").removeClass('invalid-feedback').html('');
                            $("#phone").removeClass('is-invalid');
                        }

                        if (errors.password) {
                            $("#password").siblings("p").addClass('invalid-feedback').html(errors
                                .password);
                            $("#password").addClass('is-invalid');
                        } else {
                            $("#password").siblings("p").removeClass('invalid-feedback').html('');
                            $("#password").removeClass('is-invalid');
                        }

                        if (errors.password_confirmation) {
                            $("#password_confirmation").siblings("p").addClass('invalid-feedback').html(
                                errors.password_confirmation);
                            $("#password_confirmation").addClass('is-invalid');
                        } else {
                            $("#password_confirmation").siblings("p").removeClass('invalid-feedback')
                                .html('');
                            $("#password_confirmation").removeClass('is-invalid');
                        }
                    } else {
                        $("#name").siblings("p").removeClass('invalid-feedback').html('');
                        $("#name").removeClass('is-invalid');

                        $("#email").siblings("p").removeClass('invalid-feedback').html('');
                        $("#email").removeClass('is-invalid');

                        $("#phone").siblings("p").removeClass('invalid-feedback').html('');
                        $("#phone").removeClass('is-invalid');

                        $("#password").siblings("p").removeClass('invalid-feedback').html('');
                        $("#password").removeClass('is-invalid');

                        $("#password_confirmation").siblings("p").removeClass('invalid-feedback').html(
                            '');
                        $("#password_confirmation").removeClass('is-invalid');

                        window.location.href = "{{ route('account.login') }}";
                    }
                },
                error: function(jQXHR, exception) {
                    console.log("Something went wrong");
                }
            });
        });
    </script>
@endsection
