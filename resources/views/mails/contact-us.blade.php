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
        table, td, div, h1, p {font-family: Arial, sans-serif;}
    </style>
</head>
<body style="margin:0;padding:0;">
<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
        <td align="center" style="padding:0;">
            <table role="presentation" style="width:70%;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:center;">
                <tr>
                    <td align="center" style="padding:40px 0 30px 0;background:#4EA069;">
                        @if($reply)
                            <img src="{{  $message->embed(asset('web/img/ease_and_comfort.svg'))  }}" alt="" width="150" style="height:auto;display:block;" />
                        @else
                            <h1>New inquiry</h1>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding:36px 30px 42px 30px;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                                <td align="center" style="padding:40px 0 30px 0;color:#153643;">
                                   @if(!$web)
                                      <div style="display: flex; margin:auto;">
                                        <strong style="margin-right: 10px;">Name:&nbsp;</strong>
                                        <p style="margin: 0 0 12px 0; font-size: 16px; line-height: 24px; font-family: Arial, sans-serif;">
                                          {{ $contactUsRequest->name }}
                                        </p>
                                      </div>
                                      <hr/>
                                      
                                      <div style="display: flex; margin:auto;">
                                        <strong style="margin-right: 10px;">Phone:&nbsp;</strong>
                                        <p style="margin: 0 0 12px 0; font-size: 16px; line-height: 24px; font-family: Arial, sans-serif;">
                                          {{ $contactUsRequest->phone }}
                                        </p>
                                      </div>
                                      <hr/>
                                    
                                      <div style="display: flex; margin:auto;">
                                        <strong style="margin-right: 10px;">Email:&nbsp;</strong>
                                        <p style="margin: 0 0 12px 0; font-size: 16px; line-height: 24px; font-family: Arial, sans-serif;">
                                          {{ $contactUsRequest->email }}
                                        </p>
                                      </div>
                                      <hr/>
                                    
                                      <div style="display: flex; margin:auto;">
                                        <strong style="margin-right: 10px;">Subject:&nbsp;</strong>
                                        <p style="margin: 0 0 12px 0; font-size: 16px; line-height: 24px; font-family: Arial, sans-serif;">
                                          {{ $contactUsRequest->title }}
                                        </p>
                                      </div>
                                      <hr/>
                                    
                                      <div style="display: flex; margin:auto;">
                                        <strong style="margin-right: 10px;">Message:&nbsp;</strong>
                                        <p style="margin: 0 0 12px 0; font-size: 16px; line-height: 24px; font-family: Arial, sans-serif;">
                                          {{ $contactUsRequest->message }}
                                        </p>
                                      </div>
                                      <hr/>
                                    
                                      <div style="display: flex; margin:auto;">
                                        <strong style="margin-right: 10px;">Reply:&nbsp;</strong>
                                        <p style="margin: 0 0 12px 0; font-size: 16px; line-height: 24px; font-family: Arial, sans-serif;">
                                          {{ $reply }}
                                        </p>
                                      </div>
                                  @else
                                      <div style="display: flex; margin:auto;">
                                            <strong style="margin-right: 10px;">Name:&nbsp;</strong>
                                            <p style="margin: 0 0 12px 0; font-size: 16px; line-height: 24px; font-family: Arial, sans-serif;">{{ $contactUs->name }}</p>
                                        </div>
                                             <hr/>
                                      <div style="display: flex; margin:auto;">

                                        <strong>phone:&nbsp; </strong>  <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sanCreating Email Magics-serif;">{{ $contactUs->phone }}</p>
                                      </div
                                        <hr/>
                                      <div style="display: flex; margin:auto;">

                                          <strong>Email:&nbsp;</strong>  <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sanCreating Email Magics-serif;">{{ $contactUs->email }}</p>
                                        </div>
                                        <hr/>
                                        <div style="display: flex; margin:auto;">

                                         <strong>Subject:&nbsp;</strong>  <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sanCreating Email Magics-serif;">{{ $contactUs->title }}</p>
                                       </div>
                                        <hr/>
                                       <div style="display: flex; margin:auto;">

                                        <strong>Message:&nbsp;</strong>  <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sanCreating Email Magics-serif;">{{ $contactUs->message }}</p>
                                       </div>
                                        <hr/>

                                        <form action="{{ route('dashboard.contact-us.edit', $contactUs) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-primary mx-4" id="submit-btn-about-website">Contact us</button>
                                        </form>
                                    @endif
                                    <h5 style="margin:0 0 10px 0">{{ date('Y-m-d h:m a') }}</h5>
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
