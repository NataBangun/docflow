<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document Print</title>
<style>
@media screen,print{
	table {
	  max-width: 100%;
	  background-color: transparent;
	  border-collapse: collapse;
	  border-spacing: 0;
	}
	.table {
	  width: 100%;
	  margin-bottom: 18px;
	}
	.table th,
	.table td {
	  padding: 8px;
	  line-height: 18px;
	  text-align: left;
	  vertical-align: top;
	  border-top: 1px solid #000;
	}
	.table th {
	  font-weight: bold;
	}
	.table thead th {
	  vertical-align: bottom;
	  background: #555;
	}
	.table caption + thead tr:first-child th,
	.table caption + thead tr:first-child td,
	.table colgroup + thead tr:first-child th,
	.table colgroup + thead tr:first-child td,
	.table thead:first-child tr:first-child th,
	.table thead:first-child tr:first-child td {
	  border-top: 1px solid #000;
	}
	.table tbody + tbody {
	  border-top: 1px solid #000;
	}
	.table-condensed th,
	.table-condensed td {
	  padding: 4px 5px;
	}
	.table-bordered {
	  border: 1px solid #000;
	  border-collapse: separate;
	  *border-collapse: collapsed;
	  border-left: 0;
	  -webkit-border-radius: 4px;
	  -moz-border-radius: 4px;
	  border-radius: 4px;
	}
	.table-bordered th,
	.table-bordered td {
	  border-left: 1px solid #000;
	}

	.table-bordered thead:first-child tr:first-child th:first-child,
	.table-bordered tbody:first-child tr:first-child td:first-child {
	  -webkit-border-top-left-radius: 4px;
	  border-top-left-radius: 4px;
	  -moz-border-radius-topleft: 4px;
	}
	.table-bordered thead:first-child tr:first-child th:last-child,
	.table-bordered tbody:first-child tr:first-child td:last-child {
	  -webkit-border-top-right-radius: 4px;
	  border-top-right-radius: 4px;
	  -moz-border-radius-topright: 4px;
	}
	.table-bordered thead:last-child tr:last-child th:first-child,
	.table-bordered tbody:last-child tr:last-child td:first-child {
	  -webkit-border-radius: 0 0 0 4px;
		 -moz-border-radius: 0 0 0 4px;
			  border-radius: 0 0 0 4px;
	  -webkit-border-bottom-left-radius: 4px;
			  border-bottom-left-radius: 4px;
	  -moz-border-radius-bottomleft: 4px;
	}
	.table-bordered thead:last-child tr:last-child th:last-child,
	.table-bordered tbody:last-child tr:last-child td:last-child {
	  -webkit-border-bottom-right-radius: 4px;
			  border-bottom-right-radius: 4px;
	  -moz-border-radius-bottomright: 4px;
	}
	.table-striped tbody tr:nth-child(odd) td,
	.table-striped tbody tr:nth-child(odd) th {
	  background-color: #f9f9f9;
	}
	.table tbody tr:hover td,
	.table tbody tr:hover th {
	  background-color: #f5f5f5;
	}
	/* ------ */
	.table {
		background: none repeat scroll 0 0 #FFFFFF;
		margin-top: 10px;
		.table {	margin-top:10px; background:#fff;}
	.table thead th { 
	padding-top:5px; 
	padding-bottom:5px;
	background-color: #0856A4;
	}
	.table th, .table td,.table tr td {
		border-top: 1px solid #000;
	}
	.table thead th{ color:#fff; }
	.table thead th a { color:#fff; text-decoration:underline; }
	.table tbody tr:hover td { background:#f9f9f9;}
	.table tbody td a { font-weight:bold;}
	.table .center, .center { text-align:center; }
	.table .align-right, .align-right { text-align:right;}
	table.alt1 tbody th { background:#f6f6f6; }
	}
	.letter{
		width: 21cm;
		height: 29.7cm;
		float: left;
		padding: 0 10px;
		margin-right: 20px;
		margin-bottom: 20px;
		font-family: arial;
	}
	h1{
		line-height: 34px;
	}

	thead tr th{
		background: #555555;
		color: #fff;
	}
}
</style>
</head>
<body>
	

<div style="max-width:1668px; margin: 0 auto;">
<div class="letter">
	<table class="table table-bordered">
	  <tbody>
		<tr>
		  <td width="200">No. Dok. : LAP-D2-PRO-001</td>
		  <td rowspan="4"><h1 style="text-align: center;">PROSEDUR MANAJEMEN PRODUKSI DATA COM HEAD QUARTER</h1></td>
		  <td rowspan="4" width="130"><center><img style="margin-top: 20px;" src="<?php echo base_url(); ?>assets/img/hl.jpg" alt="logo"></center></td>
		</tr>
		<tr>
		  <td>Versi : 1.0</td>
		</tr>
		<tr>
		  <td>Hal : 2  dari 2</td>
		</tr>
		<tr>
		  <td>Label : Internal</td>
		</tr>
	  </tbody>
	</table>
	<h4>KOLOM PENGESAHAN :</h4>
	<h5>Disusun Oleh :</h5>

	<table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jabatan</th>
          <th>Tanda tangan</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Luthfi Syauqie</td>
          <td>Business Process Assistant Manager</td>
          <td>_ _ _</td>
          <td>_ _ _</td>
        </tr>
      </tbody>
    </table>
	<h5>Diperiksa Oleh :</h5>

	<table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jabatan</th>
          <th>Tanda tangan</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Luthfi Syauqie</td>
          <td>Business Process Assistant Manager</td>
          <td>_ _ _</td>
          <td>_ _ _</td>
        </tr>
      </tbody>
    </table>
	
	
	<h5>Disetujui Oleh :</h5>

	<table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jabatan</th>
          <th>Tanda tangan</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Luthfi Syauqie</td>
          <td>Business Process Assistant Manager</td>
          <td>_ _ _</td>
          <td>_ _ _</td>
        </tr>
      </tbody>
    </table>
	
	
	<h5>Disahkan Oleh :</h5>

	<table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jabatan</th>
          <th>Tanda tangan</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Luthfi Syauqie</td>
          <td>Business Process Assistant Manager</td>
          <td>_ _ _</td>
          <td>_ _ _</td>
        </tr>
      </tbody>
    </table>
	<strong>DISTRIBUSI  KEPADA :</strong>
	<div class="clearfix">&nbsp;</div>
	<div style="width: 50%; float: left;">
		<ul>
			<li>CAM Finance & Supply Chain Industri Division</li>
			<li>CAM Resources & Partnership Division</li>
			<li>Operations & Maintenance Division</li>
			<li>Supply Chain Management Division</li>
		</ul>
	</div>
	<div style="width: 50%;float: left;">
		<ul>
			<li>Data Com Commerce Division</li>
			<li>Service Delivery Division</li>
			<li>Management Representative</li>
		</ul>
	</div>
	
</div>

<div class="letter">
	<table class="table table-bordered">
	  <tbody>
		<tr>
		  <td width="200">No. Dok. : LAP-D2-PRO-001</td>
		  <td rowspan="4"><h1 style="text-align: center;">PROSEDUR MANAJEMEN PRODUKSI DATA COM HEAD QUARTER</h1></td>
		  <td rowspan="4" width="130"><center><img style="margin-top: 20px;" src="<?php echo base_url(); ?>assets/img/hl.jpg" alt="logo"></center></td>
		</tr>
		<tr>
		  <td>Versi : 1.0</td>
		</tr>
		<tr>
		  <td>Hal : 2  dari 2</td>
		</tr>
		<tr>
		  <td>Label : Internal</td>
		</tr>
	  </tbody>
	</table>
	<h4>KOLOM STEMPEL</h4>
	
	<table class="table table-bordered">
	  <tbody>
		<tr>
		  <td rowspan="5"><br><br><br><br><br><br><br><br></td>
		</tr>
	  </tbody>
	</table>
</div>
<div class="clearfix">&nbsp;</div>
</div>
</body>
</html>