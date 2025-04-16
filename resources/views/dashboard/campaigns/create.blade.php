@extends('partials.dashboard.master')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- begin :: Subheader -->
    <div class="toolbar">

        <div class="container-fluid d-flex flex-stack">

            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">

                <!-- begin :: Title -->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1"><a
                        href="{{ route('dashboard.campaigns.index') }}"
                        class="text-muted text-hover-primary">{{ __('Campaigns') }}</a></h1>
                <!-- end   :: Title -->

                <!-- begin :: Separator -->
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!-- end   :: Separator -->

                <!-- begin :: Breadcrumb -->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!-- begin :: Item -->
                    <li class="breadcrumb-item text-muted">
                        {{ __('Add new campaign') }}
                    </li>
                    <!-- end   :: Item -->
                </ul>
                <!-- end   :: Breadcrumb -->

            </div>

        </div>

    </div>
    <!-- end   :: Subheader -->
    <form action="{{ route('dashboard.campaigns.store') }}" class="form" method="post" id="submitted-form"
        data-redirection-url="{{ route('dashboard.campaigns.index') }}">

        @csrf
        <div class="card mb-5">
            <!-- begin :: Card body -->
            <div class="card-body p-0">

                <div class="card-header d-flex align-items-center">
                    <div class="card-title">
                        <h3 class="fw-bolder text-dark">{{ __('Add new campaign') }}</h3>
                    </div>
                </div>

                <div class="inputs-wrapper">
                    <!-- Website URL -->
                    <div class="row mb-10">
                        <div class="col-md-12 fv-row">
                            <label class="fs-5 fw-bold mb-2">{{ __('Website URL') }}</label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="website_url_inp" name="website_url"
                                    value="{{ env('APP_URL') ?? 'https://alkathirimotors.com.sa' }}"
                                    placeholder="https://example.com" />
                                <label for="website_url_inp">{{ __('Enter the Website URL') }}</label>
                            </div>
                            <p class="invalid-feedback" id="website_url"></p>

                        </div>
                    </div>

                    <!-- Campaign Source -->
                    <div class="row mb-10">
                        <div class="col-md-12 fv-row">
                            <label class="fs-5 fw-bold mb-2">{{ __('Campaign Source') }}</label>
                            <input type="text" class="form-control @error('campaign_source') is-invalid @enderror"
                                name="campaign_source" value="{{ old('campaign_source') }}"
                                placeholder="Google, Facebook, etc." />
                            <p class="invalid-feedback" id="campaign_source"></p>

                        </div>
                    </div>

                    <!-- Campaign Medium -->
                    <div class="row mb-10">
                        <div class="col-md-12 fv-row">
                            <label class="fs-5 fw-bold mb-2">{{ __('Campaign Medium') }}</label>
                            <input type="text" class="form-control @error('campaign_medium') is-invalid @enderror"
                                name="campaign_medium" value="{{ old('campaign_medium') }}"
                                placeholder="CPC, banner, email, etc." />
                            <p class="invalid-feedback" id="campaign_medium"></p>

                        </div>
                    </div>

                    <!-- Campaign Name -->
                    <div class="row mb-10">
                        <div class="col-md-12 fv-row">
                            <label class="fs-5 fw-bold mb-2">{{ __('Campaign Name') }}</label>
                            <input type="text" class="form-control @error('campaign_name') is-invalid @enderror"
                                name="campaign_name" value="{{ old('campaign_name') }}"
                                placeholder={{ __('Campaign') }} />
                            <p class="invalid-feedback" id="campaign_name"></p>

                        </div>
                    </div>
                </div>


            </div>
            <!-- end   :: Card body -->
        </div>

        <div class="card">

            <div class="card-header d-flex align-items-center">
                <div class="card-title d-flex flex-column">
                    <h3 class="fw-bolder text-dark mb-5">{{ __('Share the generated campaign URL') }}</h3>
                    <p class="text-muted mt-5">
                        {{ __('Use this URL in any promotional channels you want to be associated with this custom campaign.') }}
                    </p>
                </div>
            </div>


            <div class="inputs-wrapper">
                <!-- Generated Link -->
                <div class="row mb-10">
                    <div class="col-md-12 fv-row">
                        <label class="fs-5 fw-bold mb-2">{{ __('Generated Link') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="website_url_new" id="generated-url"
                                placeholder="{{ __('generate URL') }}" readonly />
                            <button type="button" class="btn btn-outline-secondary bordered-2" id="copy-btn"
                                title="Copy" style="border: 1px solid rgb(193, 193, 193)">
                                <i class="fas fa-copy"></i> <!-- Font Awesome -->
                            </button>
                        </div>
                        <p class="invalid-feedback" id="website_url_new"></p>

                    </div>
                </div>
            </div>

            <input type="hidden" id="app-url" value="{{ env('APP_URL') ?? 'https://alkathirimotors.com.sa' }}">

            <div class="inputs-wrapper">
                <!-- Shorten Link -->
                <div class="row mb-10">
                    <label class="fs-5 fw-bold mb-2">{{ __('Shorten Link') }}</label>
                    <div class="col-md-8 fv-row">
                        <input type="text" class="form-control" id="shorten-url" name="shorten_link"
                            placeholder="{{ __('generate a shortened URL') }}" readonly />
                    </div>
                    <div class="col-md-4 fv-row">
                        <input class="btn btn-primary" type="button" id="generate-shorten-btn"
                            value="{{ __('Create shorten url') }}">
                        <p class="invalid-feedback" id="shorten_link"></p>

                    </div>
                </div>
            </div>



        </div>
        <div class="card">
            <div class="form-footer">
                <!-- begin :: Submit btn -->
                <button type="submit" class="btn btn-primary" id="submit-btn">

                    <span class="indicator-label">{{ __('Save') }}</span>

                    <!-- begin :: Indicator -->
                    <span class="indicator-progress">{{ __('Please wait ...') }}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                    <!-- end   :: Indicator -->

                </button>
                <!-- end   :: Submit btn -->
            </div>


        </div>
    </form>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function generateUTMUrl() {
                let baseUrl = document.getElementById("website_url_inp").value.trim();
                let utmSource = document.querySelector("input[name='campaign_source']").value.trim();
                let utmMedium = document.querySelector("input[name='campaign_medium']").value.trim();
                let utmCampaign = document.querySelector("input[name='campaign_name']").value.trim();

                let params = new URLSearchParams();
                if (utmSource) params.set("utm_source", utmSource);
                if (utmMedium) params.set("utm_medium", utmMedium);
                if (utmCampaign) params.set("utm_campaign", utmCampaign);

                let paramString = params.toString();
                let generatedUrl = baseUrl ? baseUrl + (paramString ? `?${paramString}` : "") : "";

                document.getElementById("generated-url").value = generatedUrl;
            }

            document.querySelectorAll(
                "input[name='campaign_source'], input[name='campaign_medium'], input[name='campaign_name'], #website_url_inp"
            ).forEach(input => {
                input.addEventListener("input", generateUTMUrl);
            });

            document.getElementById("copy-btn").addEventListener("click", function() {
                let generatedUrlInput = document.getElementById("generated-url");
                if (generatedUrlInput.value) {
                    navigator.clipboard.writeText(generatedUrlInput.value).then(() => {
                        let copyBtn = document.getElementById("copy-btn");
                        let originalHTML = copyBtn.innerHTML; // Save original icon & text

                        copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                        setTimeout(() => {
                            copyBtn.innerHTML = originalHTML; // Restore original icon
                        }, 1000);
                    });
                }
            });

            generateUTMUrl(); // Generate URL on page load if values exist
        });

        document.getElementById("generate-shorten-btn").addEventListener("click", function() {
            let baseDomain = document.getElementById("app-url").value; // Get APP_URL from hidden input
            let uniqueReference = Math.random().toString(36).substr(2, 8); // Generate a unique reference
            let shortenedUrl = baseDomain + "/short/" + uniqueReference; // Format shortened URL
            document.getElementById("shorten-url").value = shortenedUrl;
        });
    </script>
@endpush
