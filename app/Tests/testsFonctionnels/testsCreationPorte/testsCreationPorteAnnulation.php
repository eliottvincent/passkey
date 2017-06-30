<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://selenium-ide.openqa.org/profiles/test-case">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="selenium.base" href="http://localhost/" />
<title>creationPorteAnnulation</title>
</head>
<body>
<table cellpadding="1" cellspacing="1" border="1">
<thead>
<tr><td rowspan="1" colspan="3">creationPorteAnnulation</td></tr>
</thead><tbody>
<tr>
	<td>assertLocation</td>
	<td>*localhost/passkey/?action=createDoor</td>
	<td></td>
</tr>
<tr>
	<td>type</td>
	<td>id=form_control_1</td>
	<td>testAnnulationPorte</td>
</tr>
<tr>
	<td>click</td>
	<td>css=span.check</td>
	<td></td>
</tr>
<tr>
	<td>click</td>
	<td>id=building1</td>
	<td></td>
</tr>
<tr>
	<td>click</td>
	<td>//div[3]/div/div/div/label/span[2]</td>
	<td></td>
</tr>
<tr>
	<td>click</td>
	<td>id=rdc</td>
	<td></td>
</tr>
<tr>
	<td>clickAndWait</td>
	<td>css=button.btn.default</td>
	<td></td>
</tr>
<tr>
	<td>verifyText</td>
	<td>id=form_control_1</td>
	<td></td>
</tr>
<tr>
	<td>verifyNotText</td>
	<td>css=p</td>
	<td>La porte a bien été créée.</td>
</tr>
<tr>
	<td>assertLocation</td>
	<td>*localhost/passkey/?action=createDoor</td>
	<td></td>
</tr>
</tbody></table>
</body>
</html>
