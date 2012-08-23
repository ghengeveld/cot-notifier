<!-- BEGIN: MAIN -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>{SUBJECT}</title>
  {EXTCSS}
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" id="background">
	<tr>
		<td valign="top">
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td id="container">
            <table cellpadding="0" cellspacing="0" border="0" id="header">
              <tr>
                <td valign="top">
                  <h1><a href="{PHP.cfg.mainurl}">{PHP.cfg.maintitle}</a></h1>
                </td>
              </tr>
            </table>
            <table cellpadding="0" cellspacing="0" border="0" id="main">
              <tr>
                <td valign="top" id="body">
                  <p>{GREETING}</p>
                  <p>{DESC}</p>
                  <!-- BEGIN: ITEM -->
                  <hr/>
                  <blockquote><p>{MESSAGE|cot_string_truncate($this, 250, false, false, '...')}</p></blockquote>
                  <p>
                    <a href="{URL_VIEW}" target="_blank">{PHP.L.ViewMessage}</a> - 
                    <a href="{URL_UNSUBSCRIBE}" target="_blank">{PHP.L.Unsubscribe}</a> - 
                    <a href="{URL_NOTIFIER}" target="_blank">{PHP.L.EmailSettings}</a>
                  </p>
                  <!-- END: ITEM -->
                </td>
              </tr>
            </table>
            <table cellpadding="0" cellspacing="0" border="0" id="footer">
              <tr>
                <td valign="top">
                  {PHP.cfg.maintitle} - {PHP.cfg.subtitle}
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
<!-- END: MAIN -->