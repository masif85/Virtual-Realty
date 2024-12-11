<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<form method="post" action="<?php echo base_url(); ?>Item/index_post">
	
	<table width="100%" border="1">
  <tbody>
    <tr>
      <td>Title</td>
      <td><input type="text" name="title" id="textfield"></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><input type="text" name="description" id="textfield2"></td>
    </tr>
    <tr>
      <td>Created at</td>
      <td><input type="text" name="created_at" id="textfield3"></td>
    </tr>
    <tr>
      <td>Updated at</td>
      <td><input type="text" name="updated_at" id="textfield4"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Submit"></td>
    </tr>
  </tbody>
</table>

	</form>
</body>
</html>