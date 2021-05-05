<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $title; ?></title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
				margin-top: 5px;
				padding-top: 3px;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }

    .gray {
        background-color: lightgray
    }
</style>

</head>
<body>

  
<table width="100%">
    <tr>
        <td valign="top">
					<img src="<?= $_SERVER["DOCUMENT_ROOT"].'/helpdesk2.1/assets/img/logo.png';?>" alt="" width="200" height="60"/>
				</td>
        <td align="right">
					<img src="<?= $_SERVER["DOCUMENT_ROOT"].'/helpdesk2.1/assets/img/logo/'.$logo;?>" alt="" width="80" height="80"/>
        </td>
    </tr>
		<tr >
			<td colspan="2" align="right">
				<pre>
						<?= $site; ?> &nbsp;
						<?= $alamat; ?>  &nbsp;
						<?= $phone; ?>  &nbsp;
				</pre>
			</td>
		</tr>
  </table>

  <table width="100%" border="0">
    <tr>
        <td>
					<strong>Checked By:</strong>
				</td>
				<td>
					<strong>Validate By:</strong> 
				</td>
    </tr>
		<tr>
			<td>
				1.&nbsp; <?= $staff1; ?>
			</td>
			<td>
				1.&nbsp; <?= $superior1; ?>
			</td>
		</tr>
		<tr>
			<td>
				2.&nbsp; <?= $staff2; ?>
			</td>
			<td>
				2.&nbsp; <?= $superior2; ?>
			</td>
		</tr>

  </table>

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>Category</th>
        <th>Description</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
			<?php
				$no = 0; 
				foreach ($item as $row) : 
					$no += 1;
			?>
      <tr>
        <td align="center" scope="row"><?= $no; ?></td>
        <td><?= $row['category']; ?></td>
        <td><?= $row['description']; ?></td>
        <td align="center"><?= $row['status']; ?></td>
      </tr>
			<?php endforeach ?>
    </tbody>
  </table>

</body>
</html>
