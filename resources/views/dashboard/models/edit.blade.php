@extends('partials.dashboard.master')
@section('content')

    <!-- begin :: Subheader -->
    <div class="toolbar">

        <div class="container-fluid d-flex flex-stack">

            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                 data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                 class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">

                <!-- begin :: Title -->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1"><a href="{{ route('dashboard.models.index') }}"
                    class="text-muted text-hover-primary">{{ __("Models") }}</a></h1>
                <!-- end   :: Title -->

                <!-- begin :: Separator -->
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!-- end   :: Separator -->

                <!-- begin :: Breadcrumb -->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!-- begin :: Item -->
                    <li class="breadcrumb-item text-muted">
                        {{ __("Edit model") }}
                    </li>
                    <!-- end   :: Item -->
                </ul>
                <!-- end   :: Breadcrumb -->

            </div>

        </div>

    </div>
    <!-- end   :: Subheader -->

    <div class="card">
        <!-- begin :: Card body -->
        <div class="card-body p-0">
            <!-- begin :: Form -->
            <form action="{{ route('dashboard.models.update',$model->id) }}" class="form" method="post" id="submitted-form" data-redirection-url="{{ route('dashboard.models.index',['type' => request('type')]) }}">
            @csrf
            @method("PUT")
            <input type="hidden" value="{{ request('type') }}" name="model_type">
            <!-- begin :: Card header -->
                <div class="card-header d-flex align-items-center">
                    <h3 class="fw-bolder text-dark">{{ __("Edit model") . ' : ' . $model->name }}</h3>
                </div>
                <!-- end   :: Card header -->

                <!-- begin :: Inputs wrapper -->
                <div class="inputs-wrapper">


                    <!-- begin :: Row -->
                    <div class="row mb-10">

                        <!-- begin :: Column -->
                        <div class="col-md-{{ request('type') == 'parent' ? '12' : '6' }} fv-row">

                            <label class="fs-5 fw-bold mb-2">{{ __("Brand") }}</label>
                            <select class="form-select" data-control="select2" name="brand_id" id="brand-sp" data-placeholder="{{ __("Choose the brand") }}" data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                @foreach( $brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $brand->id == $model->brand_id ? 'selected' : '' }}> {{ $brand->name }} </option>
                                @endforeach
                            </select>
                            <p class="invalid-feedback" id="brand_id" ></p>


                        </div>
                        <!-- end   :: Column -->

                    @if( request('type') === "sub")
                        <!-- begin :: Column -->
                            <div class="col-md-6 fv-row">

                                <label class="fs-5 fw-bold mb-2">{{ __("Main model") }}</label>
                                <select class="form-select" data-control="select2" name="parent_model_id" id="parent-model-sp" data-placeholder="{{ __("Choose the main model") }}" data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                    <option value="" selected ></option>

                                </select>
                                <p class="invalid-feedback" id="parent_model_id" ></p>


                            </div>
                            <!-- end   :: Column -->
                    @endif

                    </div>
                    <!-- end   :: Row -->


                    <!-- begin :: Row -->
                    <div class="row mb-10">

                        <!-- begin :: Column -->
                        <div class="col-md-6 fv-row">

                            <label class="fs-5 fw-bold mb-2">{{ __("Name in arabic") }}</label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name_ar_inp" name="name_ar" value="{{ $model->name_ar }}" placeholder="example"/>
                                <label for="name_ar_inp">{{ __("Enter the name in arabic") }}</label>
                            </div>
                            <p class="invalid-feedback" id="name_ar" ></p>


                        </div>
                        <!-- end   :: Column -->

                        <!-- begin :: Column -->
                        <div class="col-md-6 fv-row">

                            <label class="fs-5 fw-bold mb-2">{{ __("Name in english") }}</label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name_en_inp" name="name_en" value="{{ $model->name_en }}" placeholder="example"/>
                                <label for="name_en_inp">{{ __("Enter the name in english") }}</label>
                            </div>
                            <p class="invalid-feedback" id="name_en" ></p>


                        </div>
                        <!-- end   :: Column -->

                    </div>
                    <!-- end   :: Row -->

                    <!-- begin :: Row -->
                    <div class="row mb-10">

                        <!-- begin :: Column -->
                        <div class="col-md-6 fv-row">

                            <label class="fs-5 fw-bold mb-2">{{ __("Meta tag keywords in arabic") }}</label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="meta_keyword_ar_inp" name="meta_keyword_ar" value="{{ $model->meta_keyword_ar }}" placeholder="example"/>
                                <label for="meta_keyword_ar_inp">{{ __("Enter the meta tag keywords in arabic") }}</label>
                            </div>
                            <p class="invalid-feedback" id="meta_keyword_ar" ></p>


                        </div>
                        <!-- end   :: Column -->

                        <!-- begin :: Column -->
                        <div class="col-md-6 fv-row">

                            <label class="fs-5 fw-bold mb-2">{{ __("Meta tag keywords in english") }}</label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="meta_keyword_en_inp" name="meta_keyword_en" value="{{ $model->meta_keyword_en }}" placeholder="example"/>
                                <label for="meta_keyword_en_inp">{{ __("Enter the meta tag keywords in english") }}</label>
                            </div>
                            <p class="invalid-feedback" id="meta_keyword_en" ></p>


                        </div>
                        <!-- end   :: Column -->

                    </div>
                    <!-- end   :: Row -->

                    <!-- begin :: Row -->
                    <div class="row mb-10">

                        <!-- begin :: Column -->
                        <div class="col-md-6 fv-row">

                            <label class="fs-5 fw-bold mb-2">{{ __("Meta description in arabic") }}</label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="meta_desc_ar_inp" name="meta_desc_ar" value="{{ $model->meta_desc_ar }}" placeholder="example"/>
                                <label for="meta_desc_ar_inp">{{ __("Enter the meta tag description in arabic") }}</label>
                            </div>
                            <p class="invalid-feedback" id="meta_desc_ar" ></p>


                        </div>
                        <!-- end   :: Column -->

                        <!-- begin :: Column -->
                        <div class="col-md-6 fv-row">

                            <label class="fs-5 fw-bold mb-2">{{ __("Meta description in english") }}</label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="meta_desc_en_inp" name="meta_desc_en" value="{{ $model->meta_desc_en }}" placeholder="example"/>
                                <label for="meta_desc_en_inp">{{ __("Enter the meta tag description in english") }}</label>
                            </div>
                            <p class="invalid-feedback" id="meta_desc_en" ></p>


                        </div>
                        <!-- end   :: Column -->

                        <!-- begin :: model image component -->
                        <div id="model-images">

                        </div>
                        <!-- end   :: model image component -->

                    </div>
                    <!-- end   :: Row -->


                </div>
                <!-- end   :: Inputs wrapper -->

                <!-- begin :: Form footer -->
                <div class="form-footer">

                    <!-- begin :: Submit btn -->
                    <button type="submit" class="btn btn-primary" id="submit-btn">

                        <span class="indicator-label">{{ __("Save") }}</span>

                        <!-- begin :: Indicator -->
                        <span class="indicator-progress">{{ __("Please wait ...") }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                        <!-- end   :: Indicator -->

                    </button>
                    <!-- end   :: Submit btn -->

                </div>
                <!-- end   :: Form footer -->
            </form>
            <!-- end   :: Form -->
        </div>
        <!-- end   :: Card body -->
    </div>

    <!-- end     :: edit images modal -->
    <div class="modal" tabindex="-1" id="edit-images-modal">
        <div class="modal-dialog modal-lg"  >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title" ></h5>
                    <button type="button" class="btn" id="undo-delete-image" disabled > <i class="fa fa-undo"></i> {{ __("undo") }} </button>
                </div>
                <div class="modal-body" >
                    <h3 class="text-center my-15 d-none" id="no-images-text">{{ __("There are no images") }}</h3>

                    <div class="row" id="images-container">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                    <button type="button" class="btn btn-primary" id="save-imgs-btn">{{ __("Save changes") }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end     :: edit images modal -->

@endsection
@push('scripts')
    <script src="{{ asset('js/dashboard/forms/car_models/common.js') }}"></script>
    <script src="{{ asset('js/dashboard/forms/car_models/edit.js') }}?v=1"></script>
    <script>
        let modelImages = @json($model->images->pluck('image'));
        $(document).ready( () => {
            brandsSp.trigger('change',"{{ $model->parent_model_id }}");
        });
    </script>
@endpush
