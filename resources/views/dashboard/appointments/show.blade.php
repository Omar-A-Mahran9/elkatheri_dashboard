@extends('partials.dashboard.master')
@section('content')

<!-- begin :: Subheader -->
<div class="toolbar">

    <div class="container-fluid d-flex flex-stack">

        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">

            <!-- begin :: Title -->
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1"><a
                    href="{{ route('dashboard.orders.index') }}" class="text-muted text-hover-primary">{{ __("Orders")
                    }}</a></h1>
            <!-- end   :: Title -->

            <!-- begin :: Separator -->
            <span class="h-20px border-gray-300 border-start mx-4"></span>
            <!-- end   :: Separator -->

            <!-- begin :: Breadcrumb -->
            <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                <!-- begin :: Item -->
                <li class="breadcrumb-item text-muted">
                    {{ __("Order data") }}
                </li>
                <!-- end   :: Item -->
            </ul>
            <!-- end   :: Breadcrumb -->
        </div>

    </div>

</div>
<!-- end   :: Subheader -->

<div class="card">
    <!--begin::Card head-->
    <div class="card-header card-header-stretch">
        <!--begin::Title-->
        <div class="card-title d-flex align-items-center">
                <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-03-24-172858/core/html/src/media/icons/duotune/general/gen014.svg-->
                <span class="svg-icon svg-icon-primary svg-icon-1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="currentColor"/>
                        <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="currentColor"/>
                        <path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z" fill="currentColor"/>
                    </svg>
                </span>
                <!--end::Svg Icon-->

            <h3 class="fw-bold m-0 text-gray-800">{{ $appointment->date->translatedFormat('M d, Y') }}</h3>

        </div>
        <!--end::Title-->
        <div class="card-toolbar d-flex align-items-center me-5">
            <select class="form-select" data-control="select2" name="status" id="status-sp" data-placeholder="{{ __("Choose the status") }}" data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                <option value="pending" {{ $appointment->status == "pending" ? 'selected' : ''}}>{{ __('Pending') }}</option>
                <option value="confirmed" {{ $appointment->status == "confirmed" ? 'selected' : ''}}>{{ __('Confirmed') }}</option>
                <option value="cancelled" {{ $appointment->status == "cancelled" ? 'selected' : ''}}>{{ __('Cancelled') }}</option>
            </select>
            <p class="invalid-feedback" id="status_id" ></p>
        </div>

    </div>
    <!--end::Card head-->

    <!--begin::Card body-->
    <div class="card-body">
        <!--begin::Tab Content-->
        <div class="tab-content">
            <!--begin::Tab panel-->
            <div id="kt_activity_today" class="card-body p-0 tab-pane fade show active" role="tabpanel"
                aria-labelledby="kt_activity_today_tab">
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Name') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ $appointment->name }}</span>
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Email') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ $appointment->email }}</span>
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Phone') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ $appointment->phone }}</span>
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Status') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        @if($appointment->status == 'pending')
                            <span class="badge badge-warning">{{ __(ucfirst($appointment->status)) }}</span>
                        @elseif($appointment->status == 'confirmed')
                            <span class="badge badge-success">{{ __(ucfirst($appointment->status)) }}</span>
                        @else
                            <span class="badge badge-danger">{{ __(ucfirst($appointment->status)) }}</span>
                        @endif
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Car') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ $appointment->brand ? $appointment->brand->name . ' '. $appointment->model->name . " ". $appointment->model_year : $appointment->model->name . " ". $appointment->model_year }}</span>
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Branch') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ $appointment->branch->name }}</span>
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Maintainance type') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ __($appointment->maintenance_type) }}</span>
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Extra information about the appointment') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ $appointment->description }}</span>
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Date') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ $appointment->date->format('Y-m-d') }}</span>
                    </div>
                    <!--end::Label-->
                </div>
                <div class="d-flex align-items-center justify-content-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                    <!--begin::Title-->
                    <span class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">{{ __('Time') }}</span>
                    <!--end::Title-->
                    <!--begin::Label-->
                    <div class="min-w-175px pe-2">
                        <span class="badge badge-light text-muted">{{ $appointment->time->translatedFormat('H:i A') }}</span>
                    </div>
                    <!--end::Label-->
                </div>
            </div>
            <!--end::Tab panel-->
        </div>
        <!--end::Tab Content-->
    </div>
    <!--end::Card body-->
</div>

@endsection
@push('scripts')
    <script>
        let appointmentid = "{{ $appointment->id }}";
        $("#status-sp").change(function (e) {
            e.preventDefault();
            let status = $(this).val();

            $.ajax({
                type: "post",
                url: `/dashboard/appointments/${appointmentid}/change-status`,
                data: {status},
                success: function (response) {
                    window.location.reload();
                }
            });
        });
    </script>
@endpush