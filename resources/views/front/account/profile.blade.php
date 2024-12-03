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
                            <h2 class="h5 mb-0 pt-2 pb-2">{{ __('Personal Information') }}</h2>
                        </div>
                        <form action="" name="profileForm" id="profileForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name"
                                            placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email"
                                            placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">{{ __('Phone') }}</label>
                                        <input value="{{ $user->phone }}" type="text" name="phone" id="phone"
                                            placeholder="{{ __('Enter Your Phone') }}" class="form-control">
                                        <p></p>
                                    </div>



                                    <div class="d-flex">
                                        <button class="btn btn-dark">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">{{ __('Address') }}</h2>
                        </div>
                        <form action="" name="addressForm" id="addressForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-mb-6 mb-3">
                                        <label for="name">{{ __('First Name') }}</label>
                                        <input value="{{ !empty($address) ? $address->first_name : '' }}" type="text"
                                            name="first_name" id="first_name" placeholder="Enter Your First Name"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-mb-6 mb-3">
                                        <label for="name">{{ __('Last Name') }}</label>
                                        <input value="{{ !empty($address) ? $address->last_name : '' }}" type="text"
                                            name="last_name" id="last_name" placeholder="Enter Your Last Name"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-mb-6 mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ !empty($address) ? $address->email : '' }}" type="text"
                                            name="email" id="email" placeholder="Enter Your Email"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-mb-6 mb-3">
                                        <label for="phone">{{ __('Mobile') }}</label>
                                        <input value="{{ !empty($address) ? $address->mobile : '' }}" type="text"
                                            name="mobile" id="mobile" placeholder="Enter Your Phone"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-mb-6 mb-3">
                                        <label for="name">{{ __('Country') }}</label>
                                        <select name="country_id" id="country_id" class="form-control">
                                            <option value="">{{ __('Select a Country') }}</option>
                                            @if ($countries->isNotEmpty())
                                                @foreach ($countries as $country)
                                                    <option
                                                        {{ !empty($address) && $address->country_id == $country->id ? 'selected' : '' }}
                                                        value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="col-mb-6 mb-3">
                                        <label for="phone">{{ __('Address') }}</label>
                                        <textarea name="address" id="adddress" cols="30" rows="5" class="form-control">
                                            {{ !empty($address) ? $address->address : '' }}
                                        </textarea>
                                        <p></p>
                                    </div>
                                    <div class="col-mb-6 mb-3">
                                        <label for="phone">{{ __('City') }}</label>
                                        <input value="{{ !empty($address) ? $address->city : '' }}" type="text"
                                            name="city" id="city" placeholder=" Ciry" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-dark">{{ __('Update') }}</button>
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
    <script>
        $("#profileForm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('account.updateProfile') }}",
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#name").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        $("#email").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        $("#phone").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        window.location.href = "{{ route('account.profile') }}";
                    } else {
                        var errors = response.errors;
                        if (errors.name) {
                            $("#name").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.name);
                        } else {
                            $("#name").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.phone) {
                            $("#phone").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.phone);
                        } else {
                            $("#phone").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                    }
                }
            });
        });
        $("#addressForm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('account.updateAddress') }}",
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#first_name").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        $("#last_name").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        $("#email").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        $("#mobile").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        $("#address").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        $("#city").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        $("#country_id").removeClass('is-invalid').removeClass('invalid-feedback')
                            .siblings('p').html('');
                        window.location.href = "{{ route('account.profile') }}";
                    } else {
                        var errors = response.errors;
                        if (errors.first_name) {
                            $("#first_name").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.first_name);
                        } else {
                            $("#first_name").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.last_name) {
                            $("#last_name").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.last_name);
                        } else {
                            $("#last_name").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.mobile) {
                            $("#mobile").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.mobile);
                        } else {
                            $("#mobile").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.apartment) {
                            $("#apartment").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.apartment);
                        } else {
                            $("#apartment").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.country_id) {
                            $("#country_id").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.country_id);
                        } else {
                            $("#country_id").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.address) {
                            $("#address").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.address);
                        } else {
                            $("#address").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                        if (errors.city) {
                            $("#city").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(errors.city);
                        } else {
                            $("#city").removeClass('is-invalid').removeClass('invalid-feedback')
                                .siblings('p').html('');
                        }
                    }
                }
            });
        });
    </script>
@endsection
