<?php 
  session_start();
  error_reporting(0);
  ?>
<head>
<title>Mirza Mart</title>
<style>
.input1 {
	height: 20px;
	font-size: 12px;
	padding-left: 5px;
	margin: 5px 0px 0px 5px;
	width: 97%;
	border: none;
	color: red;
}
table {
	border: 0px solid #cecece;
}
.td {
	border: 0px solid #cecece;
}
#kiri{
width:50%;
float:left;
}

#kanan{
width:50%;
float:right;
padding-top:20px;
margin-bottom:5px;
}
</style>
</head>

<body onload="window.print()">
<?php 
  include "../config/koneksi.php";
  include "../config/fungsi_indotgl.php";
  include "../config/library.php";
  include "../config/fungsi_rupiah.php";



  if ($_GET['stat']=='1'){
    $status = 'Eceran';
    $sql2=mysqli_query($koneksi, "SELECT orders_detail.*, produk.harga as harga, produk.kode_produk, produk.nama_produk, produk.id_produk, produk.satuan FROM orders_detail, produk 
                          WHERE orders_detail.id_produk=produk.id_produk AND orders_detail.id_orders='$_GET[id]'");
  }else{
    $status = 'Grosir';
    $sql2=mysqli_query($koneksi, "SELECT orders_detail.*, produk.harga_grosir as harga, produk.kode_produk, produk.nama_produk, produk.id_produk, produk.satuan FROM orders_detail, produk 
                          WHERE orders_detail.id_produk=produk.id_produk AND orders_detail.id_orders='$_GET[id]'");
  }  

echo "<center><h6 style='text-transform:uppercase'>MIRZA MART <br>
$hari_ini, $tgl_sekarang</h6>";
				
   $edit = mysqli_query($koneksi, "SELECT * FROM orders a JOIN costumer b ON a.id_costumer=b.id_costumer WHERE a.id_orders='$_GET[id]'");
    $r    = mysqli_fetch_array($edit);
    $tanggal=tgl_indo($r['tgl_order']);
		
  // tampilkan rincian produk yang di order
  // echo "
  //   <tr>
  //   <td width=120px><span style= 'font-size:10px;'>No Transaksi</span></td>
  //   <td width=120px><span style= 'font-size:10px;'>:</span></td>
  //   <td><span style= 'font-size:10px;'></b></td></tr>   
  //   <tr><td><span style= 'font-size:10px;'>Tanggal</span></td>
  //   <td><span style= 'font-size:10px;'><b></b></span></td></tr> </br>  
  //   <tr><td><span style= 'font-size:10px;'>Jam</span></td>
  //   <td width=120px><span style= 'font-size:10px; margin-left:39px;'>:</span></td>
  //   <td><span style= 'font-size:10px;'> $nama_kasir</b></span></td></tr>
  //   <tr><td><span style= 'font-size:10px;'>Kasir</span></td>
  //    <td width=120px><span style= 'font-size:10px;'>:</span></td>
  //   <td><span style= 'font-size:10px;'></b></span></td></tr>   
  //     </table>
  //  <hr/> ";

  
  
  echo "<table width=100%>
        <tr style='color:#000; height:35px;'><th><span style= 'font-size:8px;'>Nama Produk</span></th><th><span style= 'font-size:8px;'>Jumlah</span></th><th><span style= 'font-size:10px;'>Harga </span></th><th><span style= 'font-size:8px;'>Sub Total</span></th></tr>";
  
  while($s=mysqli_fetch_array($sql2)){
     // rumus untuk menghitung subtotal dan total	
	  $subtotal1    = ($s['harga'] * $s['jumlah'])* $s['diskon']/100 ;
    $subtotal2    = $s['harga'] * $s['jumlah'] ;
	  $subtotal    = $subtotal2 - $subtotal1 ;
    $total       = $total + $subtotal;
    $subtotal_rp = format_rupiah($subtotal);    
    $total_rp    = format_rupiah($total);    
    $harga       = format_rupiah($s['harga']);
    echo "<tr><td><span style='color:#000; font-size:8px;'><b>$s[nama_produk]</b></span></td><td><span style='color:#000; font-size:10px;'>$s[jumlah] $s[satuan]</span></td><td><span style='color:#000; font-size:8px;'><b>Rp. $harga</b></span></td><td><span style= 'color:#000; font-size:8px;'><b>Rp. $subtotal_rp</b></span</td></tr>";
  }   
		
    $by = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM orders where id_orders='$_GET[id]'")); 
    $kembali = $by['bayar'] - $total;

echo "
	  <table width=100%>
    <tr><td width=120px><span style= 'font-size:10px;'>Total</span></td><td><span style= 'font-size:10px;'> Rp. <b>$total_rp</b></td></tr>   
    <tr><td><span style= 'font-size:10px;'>Bayar</span></td><td><span style= 'font-size:10px;'> Rp. <b>".format_rupiah($by['bayar'])."</b></span></td></tr>   
    <tr><td><span style= 'font-size:10px;'>Kembali</span></td><td><span style= 'font-size:10px;'> Rp. <b>".format_rupiah($kembali)."</b></span></td></tr><br>  
      </table>
		<span style= 'font-size:10px;'>$_SESSION[namalengkap]</span></span></td></tr>
    <br/><center><span style= 'font-size:10px;'>Terimakasih dan Belanja lagi disini yaa:)</span></center>
    <br/><span style= 'font-size:10px;'>NB: Barang yang sudah dibeli tidak dapat di Tukar/di Kembalikan.</span>
    ";

if ($_GET['page']=='report'){

}else{  

}				

?>

<script type="text/javascript">
   document.onkeydown = function(teziger){
   switch(teziger.keyCode){
      // Menambah fungsi shortcut lain
      case 121:    // KeyCode tombol backspace
      window.location='transaksi-belanja-1.html';
      break;
   }
   teziger.preventDefault();    // Menghapus fungsi default tombol
}
</script>