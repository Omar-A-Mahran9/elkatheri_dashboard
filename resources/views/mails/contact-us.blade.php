<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        table,
        td,
        div,
        h1,
        p {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body style="margin:0;padding:0;">
    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation"
                    style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                    <tr>
                        <td align="center" style="padding:40px 0 30px 0;background:#4EA069;">
                            @if ($reply)
                                <img src="{{ $message->embed(asset('web/img/logo-white.png')) }}" alt="Company Logo"
                                    width="200" style="height:auto;display:block;" />
                            @else
                                <h1 style="color: #ffffff;">New Inquiry</h1>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td align="center" style="color:#153643;padding-bottom:30px;">
                                        @if (!$web)
                                            <p>{{ $message }}</p>
                                            <hr>
                                            <p
                                                style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;" class="fw-bold">
                                              {{ $reply }}
                                            </p>
                                        @else
                                            <form action="{{ route('dashboard.contact-us.edit', $contactUs) }}"
                                                method="GET" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary"
                                                    id="submit-btn-about-website">Contact Us</button>
                                            </form>
                                        @endif
                                        <h5 style="margin:10px 0 0 0;font-size:14px;color:#666666;">
                                            {{ date('Y-m-d h:i a') }}</h5>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
