<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<title>{{ config('app.name') }}</title>
		<style>
			a {
				color:#E34856;
				text-decoration: none;
			}
			a:hover {
				color:#a4a4a4;
				text-decoration: none;
			}
		</style>
	</head>
	<body>
    <!--[if mso]>
	 <center>
	 <table><tr><td width="600">
	<![endif]-->
	    <div style="max-width:600px; margin:0 auto;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td colspan="3" height="30px">&nbsp;</td>
				</tr>
				<tr>
					<td width="2%">&nbsp;</td>
					<td width="50%"><img src="{{ config('app.url') }}/images/logo.png" width="100%" alt="{{ config('app.name') }}" style="max-width:300px;height:auto;display:inline-block;"></td>
					<td width="48%">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" height="30px">&nbsp;</td>
				</tr>
		
				<tr><td colspan="3" height="30"></td></tr>
				<tr>
					<td width="2%">&nbsp;</td>
					<td>
						@yield('content')
					</td>
					<td width="2%">&nbsp;</td>
				</tr>
				@if(isset($link) && !empty($link))
				<tr>
					<td width="2%">&nbsp;</td>
					<td width="96%">
						<p style="text-align:center" align="middle"><a href="{{ $link }}" style="display:inline-block;padding:10px 16px;border-radius:4px;background-color:#E34856;color:#fff;text-decoration:none;font-size:15px;">@yield('buttontext')</a></p>
						<p>&nbsp;</p>
						<p>If you are having trouble clicking the button above, please copy and paste the URL below into your web browser:</p>
						<p>{{ $link }}</p>
					</td>
					<td width="2%">&nbsp;</td>
				</tr>
				@endif
				<tr><td colspan="3" height="30"></td></tr>
				<tr>
					<td width="2%">&nbsp;</td>
					<td>
						<p>The Czar's Promise Team</p>
					</td>
					<td width="2%">&nbsp;</td>
				</tr>
				<tr><td colspan="3" height="15">&nbsp;</td></tr>
				<tr><td colspan="3" bgcolor="#575757" height="2"></td></tr>
				<tr><td colspan="3" height="15"></td></tr>
				<tr>
					<td width="2%">&nbsp;</td>
					<td width="96%">
						<p><font size="1" color="#575757"><span style="color:#575757;font-size:10px;">Czar's Promise is a 501(c)(3) organization.</span></font></p>
						<p><font size="1" color="#575757"><span style="color:#575757;font-size:10px;">Note: This email has been sent from an unmonitored email account.  Please do not respond directly to this email.  If you have questions <a href="https://www.czarspromise.com/contact-us/">contact Czar's Promise</a></span></font></p>
					</td>
					<td width="2%">&nbsp;</td>
				</tr>
			</table>
	    </div>
	<!--[if mso]>
	 </td></tr></table>
	 </center>
	<![endif]-->
	</body>
</html>