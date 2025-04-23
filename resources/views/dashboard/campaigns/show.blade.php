@extends('partials.dashboard.master')
@push('styles')
    <link href="{{ asset('dashboard-assets/css/datatables' . (isDarkMode() ? '.dark' : '') . '.bundle.css') }}"
        rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <!-- begin :: Subheader -->
    <div class="toolbar">

        <div class="container-fluid d-flex flex-stack">

            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">

                <!-- begin :: Title -->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('Campaigns') }}</h1>
                <!-- end   :: Title -->

                <!-- begin :: Separator -->
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!-- end   :: Separator -->

                <!-- begin :: Breadcrumb -->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!-- begin :: Item -->
                    <li class="breadcrumb-item text-muted">
                        {{ __('Campaigns list') }}
                    </li>
                    <!-- end   :: Item -->
                </ul>
                <!-- end   :: Breadcrumb -->

            </div>

        </div>

    </div>
    <!-- end   :: Subheader -->
    <div class="row g-6 g-xl-9 mb-10 ">
        <!--begin::Col-->
        <div class="col-md-12 col-xl-6 ">
            <!--begin::Card-->
            <div class="card border-hover-primary ">
                <!--begin::Card header-->
                <div class="card-header border-0  ">
                    <!--begin::Card Title-->
                    <div class="card-title m-0">
                        <!--begin::Avatar-->
                        <div class=" w-35px h-35px bg-light m-auto d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 18 18"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.0073 11.6724C7.93315 11.6724 5.30565 12.1382 5.30565 13.999C5.30565 15.8599 7.91565 16.339 11.0073 16.339C14.0823 16.339 16.709 15.8774 16.709 14.0157C16.709 12.154 14.0998 11.6724 11.0073 11.6724Z"
                                    stroke="#1C1D22" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.0071 9.01652C13.0254 9.01652 14.6621 7.38069 14.6621 5.36236C14.6621 3.34402 13.0254 1.70819 11.0071 1.70819C8.98961 1.70819 7.35294 3.34402 7.35294 5.36236C7.34544 7.37319 8.97044 9.00902 10.9813 9.01652H11.0071Z"
                                    stroke="#1C1D22" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M5.26367 8.06802C3.92951 7.88052 2.90201 6.73552 2.89951 5.34969C2.89951 3.98385 3.89534 2.85052 5.20117 2.63635"
                                    stroke="#1C1D22" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M3.50391 11.2769C2.21141 11.4694 1.30891 11.9227 1.30891 12.856C1.30891 13.4985 1.73391 13.9152 2.42057 14.176"
                                    stroke="#1C1D22" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h4 class="fw-bold me-auto px-4 py-3">{{ __('Visiting Count') }}</h4>

                        <!--end::Avatar-->
                    </div>
                    <!--end::Car Title-->
                    <!--begin::Card toolbar-->
                    {{-- <div class="card-toolbar">
                    <span class="badge badge-light-primary fw-bold me-auto px-4 py-3">In Progress</span>
                </div> --}}
                    <!--end::Card toolbar-->
                </div>
                <!--end:: Card header-->
                <!--begin:: Card body-->
                <div class="card-body">
                    <!--begin::Name-->
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="rounded min-w-125px px-4">
                            <div class="fw-bold text-center" style="font-size: 3rem;">{{ $count_campaign }}</div>
                        </div>
                    </div>

                    <!--end::Name-->
                </div>

            </div>
            <!--end::Card-->
        </div>

        <div class="col-md-12 col-xl-6 ">
            <!--begin::Card-->
            <div class="card border-hover-primary ">
                <!--begin::Card header-->
                <div class="card-header border-0  ">
                    <!--begin::Card Title-->
                    <div class="card-title m-0">
                        <!--begin::Avatar-->
                        <div class="w-35px h-35px bg-light m-auto d-flex justify-content-center align-items-center rounded-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7 18C7 19.1046 7.89543 20 9 20C10.1046 20 11 19.1046 11 18C11 16.8954 10.1046 16 9 16C7.89543 16 7 16.8954 7 18ZM1 2C1 1.44772 1.44772 1 2 1H3.621C4.03151 1 4.40642 1.32202 4.45942 1.725C4.70692 3.69959 5.49212 5.47248 6.71909 6.60603C7.39109 7.25333 8.40158 7.62328 9.50158 7.62328H14.4984C15.5984 7.62328 16.6089 7.25333 17.2809 6.60603C18.5079 5.47248 19.2931 3.69959 19.5406 1.725C19.5936 1.32202 19.9685 1 20.379 1H22C22.5523 1 23 1.44772 23 2C23 2.55228 22.5523 3 22 3H21.2071L19.5204 16.6421C19.3474 17.2493 18.7533 17.6597 18.1461 17.6597H5.85385C5.24669 17.6597 4.65256 17.2493 4.47961 16.6421L2.79289 3H2C1.44772 3 1 2.55228 1 2ZM9 16C9 16.5523 9.44772 17 10 17C10.5523 17 11 16.5523 11 16C11 15.4477 10.5523 15 10 15C9.44772 15 9 15.4477 9 16Z"
                                    fill="#1C1D22"/>
                            </svg>
                        </div>

                        <h4 class="fw-bold me-auto px-4 py-3">{{ __('Orders Count') }}</h4>

                        <!--end::Avatar-->
                    </div>
                    <!--end::Car Title-->
                    <!--begin::Card toolbar-->
                    {{-- <div class="card-toolbar">
                    <span class="badge badge-light-primary fw-bold me-auto px-4 py-3">In Progress</span>
                </div> --}}
                    <!--end::Card toolbar-->
                </div>
                <!--end:: Card header-->
                <!--begin:: Card body-->
                <div class="card-body">
                    <!--begin::Name-->
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="rounded min-w-125px px-4">
                            <div class="fw-bold text-center" style="font-size: 3rem;">{{ $count_orders }}</div>
                        </div>
                    </div>

                    <!--end::Name-->
                </div>

            </div>
            <!--end::Card-->
        </div>


    </div>
    <!-- begin :: Datatable card -->
    <div class="card mb-2">
        <!-- begin :: Card Body -->
        <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">

            <!-- begin :: Filter -->
            <div class="d-flex flex-stack flex-wrap mb-15">

                <!-- begin :: General Search -->
                <div class="d-flex align-items-center position-relative my-1 mb-2 mb-md-0">

                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <i class="fa fa-search fa-lg"></i>
                    </span>

                    <input type="text" class="form-control form-control-solid w-250px ps-15 border-gray-300 border-1"
                        id="general-search-inp" placeholder="{{ __('Search ...') }}">

                </div>
                <!-- end   :: General Search -->





            </div>
            <!-- end   :: Filter -->
            <table id="kt_datatable" data-campaign-id="{{ $id }}"
                class="table text-center table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>{{ __('Campaign Name') }}</th>
                        <th>{{ __('IP Address') }}</th>
                        <th>{{ __('User Agent') }}</th>
                        <th>{{ __('created date') }}</th>
                    </tr>
                </thead>

                <tbody class="text-gray-600 fw-bold text-center">

                </tbody>
            </table>
            <!-- end   :: Datatable -->


        </div>
        <!-- end   :: Card Body -->
    </div>
    <!-- end   :: Datatable card -->
@endsection
@push('scripts')
    <script src="{{ asset('js/dashboard/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/dashboard/datatables/campaignsresults.js') }}"></script>
@endpush
