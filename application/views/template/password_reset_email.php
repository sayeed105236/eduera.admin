

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Demystifying Email Design</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<style type="text/css">
	 .button {
	    border-radius: 2px;
	}

	.button a {
	    padding: 8px 12px;
	    border-radius: 2px;
	    font-family: Helvetica, Arial, sans-serif;
	    font-size: 14px;
	    color: #ffffff;
	    text-decoration: none;
	    font-weight: bold;
	    display: inline-block;
	}
</style>
</head>
<body style="margin: 0; padding: 0;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td style="padding: 10px 0 30px 0;">
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
					<tr style="height: 120px; border-bottom: 2px solid #e33667">
						<td align="center"  style="background-color: #ffffff; padding: 40px 0 30px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;"><img src="http://file.server.eduera.com.bd/logo/eduera-logo.png" height="50px">

						</td>
					</tr>
					<tr>
						<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr >
									<td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
										<b>Hi <?=$user_data->first_name?>,</b>
									</td>
								</tr>
								<tr>
									<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
										You've recently asked to reset the password for this eduera account

									</td>


								</tr>
								<tr>
									Email: <?=$user_data->email?>
								</tr>
								<br>
								<tr>
									<td  style="font-size: 16px; font-weight:bold">
									 	Your password is: <?=$password?>
									</td>
								</tr>
								<tr>
									<td>
										<p>To login your account, click the button below:</p>
									</td>
								</tr>
								<tr>
									<td class="button" >
					                    <a style="background-color: #1a6596" class="link" href="https://www.eduera.com.bd/login" target="_blank">
					                          Login your account
					                    </a>
				                   </td>
								</tr>
								<tr>

									<tr>
										<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
											 If this was a mistake, just ignore this email and nothing will happen.
										</td>
									</tr>
									<tr>

										<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
										 Best Regards, <br/>
										<a style="text-decoration:none; color: #153643;" href="#">Eduera</a> Team
									</td>

								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td  style="background-color: #e33667; padding: 30px 30px 30px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
										© <?=date('Y')?>  Eduera. All rights reserved.
										 &reg; Regards <br/>
										<a href="#" style="color: #ffffff;"><font color="#ffffff">Eduera</font></a> Team

										<br><a href="https://www.eduera.com.bd/" style="color: #ffffff;"><font color="#ffffff">eduera.com.bd</font></a>
									</td>

									<td align="right" width="25%">

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