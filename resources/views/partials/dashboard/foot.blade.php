<script>
    let locale = "{{ getLocale() }}";
    let imagesBasePath  = "{{ asset('/storage/Images') }}";
    let currency        = " {{ __( settings()->get('currency') ) }} ";
    let ordersStatuses  = @json( settings()->get('orders_statuses') );
    var soundStatus = 1;

    var faviconPath = '{{ asset(getImagePathFromDirectory( settings()->get('favicon') , "Settings")) }}';
    var notificationSoundOn = 1;


</script>
<script src="{{asset('dashboard-assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard-assets/js/scripts.bundle.js')}}"></script>
<script src="{{asset('js/dashboard/translations.js')}}"></script>
<script src="{{asset('js/dashboard/global_scripts.js')}}"></script>
<script>

    $(window).on('load', function() {
        setTimeout( () => $("#loading-div").hide() , 500);
    });

</script>
<script>
    var alertUploadFileHtml = `{!! alertUploadFileHtml() !!}`
    if($(`input[type="file"]`).length > 0){
        $(`#dashboardContent`).prepend(alertUploadFileHtml);
    }
</script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-analytics.js"></script>
<script src="{{ asset('js/dashboard/listen-to-firebase-notification.js') }}"></script>
@stack('scripts')
