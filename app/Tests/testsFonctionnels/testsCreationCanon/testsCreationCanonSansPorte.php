<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://selenium-ide.openqa.org/profiles/test-case">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="selenium.base" href="http://localhost/" />
<title>testsCreationCanonSansPorte</title>
</head>
<body>
<table cellpadding="1" cellspacing="1" border="1">
<thead>
<tr><td rowspan="1" colspan="3">testsCreationCanonSansPorte</td></tr>
</thead><tbody>
<tr>
	<td>assertLocation</td>
	<td>http://localhost/passkey/?action=createLock</td>
	<td></td>
</tr>
<tr>
	<td>type</td>
	<td>id=form_control_1</td>
	<td>testCreationCanon</td>
</tr>
<tr>
	<td>select</td>
	<td>name=lock_door</td>
	<td>label=(Aucune)</td>
</tr>
<tr>
	<td>clickAndWait</td>
	<td>css=button.btn.blue</td>
	<td></td>
</tr>
<tr>
	<td>assertText</td>
	<td>css=p</td>
	<td>Le canon a bien été enregistré.</td>
</tr>
<tr>
	<td>click</td>
	<td>css=button.close</td>
	<td></td>
</tr>
<tr>
	<td>assertLocation</td>
	<td>http://localhost/passkey/?action=createLock</td>
	<td></td>
</tr>

</tbody></table>
</body>
</html>
