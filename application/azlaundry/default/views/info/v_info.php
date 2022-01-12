<?php 
    $this->load->helper('az_config');
?>
<!DOCTYPE html>
<html>
    <head>
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo base_url().AZAPP.'assets/images/logo.png';?>" />
        <title><?php echo az_get_config('app_name');?></title>
        <meta name="description" content="AZLaundry adalah sebuah aplikasi laundry / software laundry yang digunakan untuk memanajemen bisnis laundry. AZLaundry cocok digunakan bagi pemilik usaha laundry lebih dari 1 outlet/cabang. Cukup instal AZLaundry di 1 domain untuk semua bisnis laundry anda. AZLaundry juga bisa digunakan secara offline. Multi outlet/cabang, Support laundry kiloan dan laundry satuan, Tampilan responsive, bisa diakses via mobile (handphone), Bisa online maupun offline, Data pelanggan, Transaksi pembayaran langsung bayar dan bayar nanti, Status transaksi laundry (BARU/PROSES/SELESAI/DIAMBIL), Deadline/batas pengerjaan transaksi, Rincian laundry (Jenis dan jumlah baju), Diskon per produk dan diskon keseluruhan, Pajak per produk dan pajak keseluruhan, 2 jenis nota, nota kecil dan nota besar (invoice), Transaksi pengeluaran, Cetak transaksi, Laporan rugi/laba, Multi bahasa, bahasa Indonesia dan bahasa Inggris, Multi user, bisa setting hak akses pengguna. Source code laundry, source code codeigniter. Melayani pembuatan aplikasi custom sesuai kebutuhan, segera konsultasikan kebutuhan anda. Salam, Muhammad Isman Subakti, S.Kom | 08993896581 (WA)"/>

        <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap/bootstrap.min.css" type="text/css" />
	</head>
	<body style="padding: 20px;">
        <a href="<?php echo app_url();?>" style="font-size:20px;"><button>AZLaundry</button></a>
        <?php echo az_get_config('app_preface');?>
    </body>
</html>