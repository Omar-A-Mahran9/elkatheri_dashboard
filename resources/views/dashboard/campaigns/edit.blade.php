@extends('partials.dashboard.master')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="toolbar">
        <div class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    <a href="{{ route('dashboard.campaigns.index') }}"
                        class="text-muted text-hover-primary">{{ __('Campaigns') }}</a>
                </h1>
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        {{ __('Edit Campaign') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <form action="{{ route('dashboard.campaigns.update', $campaign->id) }}" method="POST" class="form"
        id="submitted-form" data-redirection-url="{{ route('dashboard.campaigns.index') }}">

        @csrf
        @method('PUT')

        <div class="card mb-5">
            <div class="card-body p-0">
                <div class="card-header d-flex align-items-center">
                    <div class="card-title">
                        <h3 class="fw-bolder text-dark">{{ __('Edit Campaign') }}</h3>
                    </div>
                </div>

                <div class="inputs-wrapper">
                    <!-- Website URL -->
                    <div class="row mb-10">
                        <div class="col-md-12 fv-row">
                            <label class="fs-5 fw-bold mb-2">{{ __('Website URL') }}</label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="website_url_inp" name="website_url"
                                    value="{{ $campaign->website_url ?? (env('APP_URL') ?? 'https://alkathirimotors.com.sa') }}"
                                    placeholder="https://example.com" />
                                <label for="website_url_inp">{{ __('Enter the Website URL') }}</label>
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Source -->
                    <div class="row mb-10">
                        <div class="col-md-12 fv-row">
                            <label class="fs-5 fw-bold mb-2">{{ __('Campaign Source') }}</label>
                            <input type="text" class="form-control @error('campaign_source') is-invalid @enderror"
                                name="campaign_source" value="{{ old('campaign_source', $campaign->campaign_source) }}"
                                placeholder="Google, Facebook, etc." />
                        </div>
                    </div>

                    <!-- Campaign Medium -->
                    <div class="row mb-10">
                        <div class="col-md-12 fv-row">
                            <label class="fs-5 fw-bold mb-2">{{ __('Campaign Medium') }}</label>
                            <input type="text" class="form-control @error('campaign_medium') is-invalid @enderror"
                                name="campaign_medium" value="{{ old('campaign_medium', $campaign->campaign_medium) }}"
                                placeholder="CPC, banner, email, etc." />
                        </div>
                    </div>

                    <!-- Campaign Name -->
                    <div class="row mb-10">
                        <div class="col-md-12 fv-row">
                            <label class="fs-5 fw-bold mb-2">{{ __('Campaign Name') }}</label>
                            <input type="text" class="form-control @error('campaign_name') is-invalid @enderror"
                                name="campaign_name" value="{{ old('campaign_name', $campaign->campaign_name) }}"
                                placeholder={{ __('Campaign') }} />
                        </div>
                    </div>
                </div>
            </div>
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
                            {{-- <input type="text" class="form-control" name="website_url_new" id="generated-url"
                                value="{{ $campaign->generated_url ?? '' }}" placeholder="{{ __('generate URL') }}"
                                readonly /> --}}

                            <input type="hidden" class="form-control" name="website_url_new" id="generated-url"
                                value="{{ $campaign->generated_url ?? '' }}" placeholder="{{ __('generate URL') }}"
                                readonly />

                            <input type="text" class="form-control" id="generated-fake-url"
                                value="{{ $campaign->generated_url ?? '' }}" placeholder="{{ __('generate URL') }}"
                                readonly />


                        </div>
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
                            value="{{ $campaign->shorten_link ?? '' }}" placeholder="{{ __('generate a shortened URL') }}"
                            readonly />
                    </div>
                    <div class="col-md-4 fv-row d-flex">
                        <input class="btn btn-primary me-2" type="button" id="generate-shorten-btn"
                            value="{{ __('Create shorten url') }}">
                        <button type="button" class="btn btn-outline-secondary" id="copy-btn" title="Copy">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="form-footer">
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <span class="indicator-label">{{ __('Update') }}</span>
                    <span class="indicator-progress">{{ __('Please wait ...') }}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
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

                let generatedUrl = baseUrl;
                if (paramString) {
                    // if URL already has a "?", append using "&"
                    generatedUrl += (baseUrl.includes('?') ? '&' : '?') + paramString;
                }

                let encodedUrl = btoa(generatedUrl);
                document.getElementById("generated-fake-url").value = encodedUrl;

                document.getElementById("generated-url").value = generatedUrl;
            }

            document.querySelectorAll(
                "input[name='campaign_source'], input[name='campaign_medium'], input[name='campaign_name'], #website_url_inp"
            ).forEach(input => {
                input.addEventListener("input", generateUTMUrl);
            });

            // Copy button logic (for shortened URL)
            document.getElementById("copy-btn").addEventListener("click", function() {
                let shortenUrlInput = document.getElementById("shorten-url");
                if (shortenUrlInput.value) {
                    navigator.clipboard.writeText(shortenUrlInput.value).then(() => {
                        let copyBtn = document.getElementById("copy-btn");
                        let originalHTML = copyBtn.innerHTML;

                        copyBtn.innerHTML = '<i class="fas fa-check"></i>';
                        setTimeout(() => {
                            copyBtn.innerHTML = originalHTML;
                        }, 1000);
                    });
                }
            });

            // Generate shorten link
            document.getElementById("generate-shorten-btn").addEventListener("click", function() {
                let generatedUrl = document.getElementById("generated-url")
                    .value; // decrypt the hidden full URL

                try {
                    let urlObj = new URL(generatedUrl); // parse the URL safely
                    let mainDomain = urlObj.origin; // e.g., "https://alkathirimotors.com.sa"

                    let uniqueReference = Math.random().toString(36).substr(2, 8);
                    let shortenedUrl = mainDomain + "/short/" + uniqueReference;

                    document.getElementById("shorten-url").value = shortenedUrl;
                } catch (error) {
                    console.error("Invalid URL format");
                    document.getElementById("shorten-url").value = "";
                }
            });

            generateUTMUrl();
        });
    </script>
@endpush
