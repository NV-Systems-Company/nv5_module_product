<!-- BEGIN: main -->
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{DATA.language}" lang="{DATA.language}" xml:lang="{DATA.language}">
<head>
<title>{DATA.title}</title>
<base href="{DATA.base}" />
</head>
<body>
<div style="text-align: center;">
  <h1>{DATA.heading_title}</h1>
  <p>{DATA.text_response}</p>
  <p>{DATA.text_failure}</p>
  <p>{DATA.text_failure_wait}</p>
</div>
<script type="text/javascript">
<!--
setTimeout('location = \'{DATA.continue}\';', 5000);
//--></script>
</body>
</html>
<!-- END: main -->