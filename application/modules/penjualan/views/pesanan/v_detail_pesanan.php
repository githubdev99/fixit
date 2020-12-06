<?php 
     $StringStatusTransaksi = '';
	if($get_data->status_transaksi == 'Telah dibayar' && $get_data->status_pesanan == 'dikirim'){
        $StringStatusTransaksi = '<span class="badge badge-warning text-bold text-white">Pesanan Dikirim</span>';
        
    }
    if($get_data->status_transaksi == 'Telah dibayar' && $get_data->status_pesanan == 'disiapkan' && $get_data->terima_pesanan=='baru'){
        $StringStatusTransaksi = '<span class="badge badge-warning text-bold text-white">Pesanan Baru</span>';
    }
    if($get_data->status_transaksi == 'Telah dibayar' && $get_data->status_pesanan == 'disiapkan' && $get_data->terima_pesanan=='terima'){
        $StringStatusTransaksi = '<span class="badge badge-warning text-bold text-white">Pesanan Diproses</span>';
        $Kirimkan = '
                    <a href="#" style="background:#43d39e; color:#fff;font-size:8pt!important;border:#13a06c solid 1px;border-radius:5px;padding:2px 4px;padding-bottom:2px;">Kirimkan Pesanan <i class="fas fa-share"></i></a>
        ';					
    }
    if($get_data->status_transaksi == 'Telah dibayar' && $get_data->status_pesanan == 'selesai'){
        $StringStatusTransaksi = '<span class="badge badge-warning text-bold text-white">Pesanan Selesai</span>';					
    }
    if($get_data->status_transaksi == 'Batal'){
        $StringStatusTransaksi = '<small class="badge badge-warning text-bold text-white">Pesanan Dibatalkan </small><br>
        <small>'.$get_data->status_pembatalan.'</small>';
    }				
    if($get_data->status_transaksi == 'Menunggu Pembayaran'){
        $StringStatusTransaksi = '<span class="badge badge-warning text-bold text-white">Pesanan Belum Dibayar</span>';
    }

    if($get_data->terima_pesanan=='tolak'){
				

        $TerimaPesanan = '
        <br><a style="align:right!important; background:#ef144a;color:#fff;font-size:8pt!important;border:#ef144a solid 1px;border-radius:5px;padding:2px 4px;padding-bottom:2px;">Pesanan ditolak <i class="fas fa-check-circle"></i></a>';
        $SetKeterangan = '<small><b>Keterangan :</b> <br>'.$get_data->alasan_tolak.'</small>';

    }else{
        $TerimaPesanan='';
        $SetKeterangan ='';
    }
?>
                    <!-- Start Content-->
                    <div class="container-fluid">
                        <div class="row page-title mt-2 d-print-none">
                            <div class="col-md-12">
                           
                                <h4 class="mb-1 mt-0">Invoice</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Logo & title -->
                                        <div class="clearfix">
                                            <div class="float-sm-right">
                                       
                                                <!-- <div class="badge badge-success" style="float:right;"><b><?= ucwords($get_data->status_pesanan);?></b></div><br> -->
                                                <address class="pl-2 mt-2">
                                                    <img src="https://seller.jaja.id/asset/images/logo-full.png" width="150px" >

                                                    <?= $TerimaPesanan.'<br>'.$SetKeterangan ?><br>
                                                    <b><?= $StringStatusTransaksi;?></b>
                                                </address>                                                
                                            </div>
                                            <div class="float-sm-left">
                                                <h6 class="m-0 d-print-none"> <span class="badge badge-success text-bold text-white"><?= $get_data->status_transaksi ?></span></h6>
                                                <dl class="row mb-2 mt-3">
                                                    <dt class="col-sm-12 font-weight-normal">Nomor Invoice : <?= $get_data->invoice ?>
                                                    </dt>

                                                    <dt class="col-sm-12 font-weight-normal">Tanggal Transaksi :
                                                    <?= date('d M Y',strtotime($get_data->created_date))  ?></dt>
                                                </dl>
                                                
                                            </div>
                                            
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-3">
                                            <h6 class="font-weight-normal">Tujuan Pengiriman:</h6> 
                                                <address>
                                                
                                                <?= $get_data->alamat_pengiriman ?>
                                                </address>
                                            </div>
                                            <div class="col-md-2">
                                                <h6 class="font-weight-normal">Penerima:</h6>
                                                <!-- <h6 class="font-size-16"><b><?= $get_data->nama_customer ?></b></h6>
                                                <address>
                                                <?= $get_data->telepon ?><br>
                                                <?= $get_data->email ?><br>
                                        
                                                </address> -->

                                                <?php 
                                                    if(!empty($get_data->nama_penerima)){
                                                        echo '<b>'.strtoupper($get_data->nama_penerima).'</b><br>'.$get_data->telp_penerima.'<br>';
                                                    }else{
                                                        echo '<b>'.strtoupper($get_data->nama_lengkap).'</b><br>'.$get_data->telepon.'<br>';

                                                    }
                                                ?>
                                            </div> 
                                        </div>
                                   
                                        <h6 class="font-size-16"><b>Pengirim : <?= $get_data->nama_toko ?></b></h6>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table mt-4 table-centered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Produk</th>
                                                                <th style="width: 15%">Harga Satuan</th>
                                                                <th style="width: 15%">Jumlah</th>
                                                                <th style="width: 15%" class="text-right">Sub Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php 
                                                            $nomor = 1;
                                                            $HargaTotal = NULL; 
                                                            foreach ($get_data_produk as $inv) {
                                                        ?>
                                                            <tr>
                                                                <td><?= $nomor;?></td>
                                                                <td>
                                                                    <img src="<?= $inv->foto_produk ?>"  width="50" class="img-thumbnail img-responsive mt-2">
                                                                    <span class="font-size-14 mt-0 mb-2"><b><?= $inv->nama_produk?></b></span>

                                                                </td>
                                                                <td>Rp. <?= number_format($inv->harga_aktif) ?></td>
                                                                <td><?= $inv->qty ?></td>
                                                                <td class="text-right">Rp. <?= number_format($inv->sub_total) ?></td>
                                                            </tr>
                                                        <?php
                                                            $HargaTotal += $inv->sub_total ;
                                                            $nomor++;    
                                                        } ?>

                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive -->
                                            </div> <!-- end col -->
                                        </div>
                                        <!-- end row -->

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="clearfix pt-5">
                                                    <h7 class="text-muted">Pesan Customer:</h7><br>
                                                    <small class="text-muted">
                                                    <?php 
                                                        if(!empty($get_data->pesan_customer_toko)){
                                                            echo '<i>'.$get_data->pesan_customer_toko.'</i>';
                                                        }else{
                                                            echo '<i>[Tidak Ada Pesan dari Pembeli].</i>';
                                                        }
                                                    ?> 
                                                    </small><br>

                                                    <h7 class="text-muted">Catatan Package:</h7><br>
                                                    <small class="text-muted">
                                                    <?php 
                                                       if(!empty($inv->catatan_package_toko)){
                                                        echo '<i>'.$inv->catatan_package_toko.'</i>';
                                                        }else{
                                                            echo '<i>[Tidak Ada Catatan Package].</i>';
                                                        }
                                                    ?> 
                                                    </small>
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-sm-6">
                                                <div class="float-right mt-3">
                                                    <p><span class="font-weight-medium">Total Harga :</span> 
                                                        <span class="float-right"><b>Rp. <?= number_format($HargaTotal,0); ?></b></span></p>
                                                    
                                                    <p><span class="font-weight-medium">Pengiriman : <br> <small><?= $get_data->pengiriman ?></small></span> 
                                                        <span class="float-right"> &nbsp;&nbsp;&nbsp; Rp.
                                                        <?php 
                                                            $total_ongkir_all=0;
                                                            $total_ongkir_all += $get_data->ongkir;
                                                            echo number_format($total_ongkir_all,0);
                                                        ?>
                                                        </span>
                                                    </p>
                                                    <?php if(!empty($inv->biaya_package)){?>
                                                        <p><span class="font-weight-medium">Biaya Package :</span> 
                                                            <span class="float-right"> &nbsp;&nbsp;&nbsp; Rp.<?php echo number_format($inv->biaya_package,0);?></span></p>
                                                    <?php }?>

                                                    <?php if(!empty($inv->diskon_voucher)){?>
                                                        <p><span class="font-weight-medium">Diskon Voucher JAJA :</span> 
                                                            <span class="float-right"> &nbsp;&nbsp;&nbsp; 
                                                            <?php 
                                                                $persentase_diskon=($inv->persentase_diskon/100)*$HargaTotal;
                                                                $TotalPesanan = $HargaTotal - $persentase_diskon;
                                                
                                                                    if($inv->diskon_voucher <= 100){
                                                                        $diskon_voucher_jaja	= ($inv->diskon_voucher/100)*$TotalPesanan;				
                                                                        $text = '<small style="font-size:7pt;">'.$inv->diskon_voucher.'% </small>';	
                                                                    }else if($inv->diskon_voucher > 100){
                                                                        // if($inv->biaya_ongkir <= $inv->diskon_voucher){
                                                                        //     $diskon_voucher_jaja = $inv->biaya_ongkir;                                                                     
                                                                        // }else{
                                                                        //     $diskon_voucher_jaja = $inv->diskon_voucher;                                                                       
                                                                        // } 
                                                                        
                                                                        $diskon_voucher_jaja = $inv->diskon_voucher;
                                                                        $text = '';                                                               
                                                                    }else{										
                                                                        echo 'Rp 0,-';	
                                                                        $text = '';								
                                                                    }

                                                             
                                                                

                                                            ?>
                                                                <?=  $text ?>
                                                              <span style="color: red; " >(-) Rp.<?php echo number_format($diskon_voucher_jaja,0);?> </span>
                                                              
                                                            </span>
                                                        </p>
                                                    <?php } else {
                                                        $diskon_voucher_jaja = 0;
                                                    }?>
                                                    
                                                    <?php if(!empty($inv->id_voucher_toko)){?>
                                                        <p><span class="font-weight-medium">Diskon Voucher Toko :</span> 
                                                            <span class="float-right"> &nbsp;&nbsp;&nbsp; 
                                                            <?php                                                  
                                                                if(!empty($inv->persentase_diskon)){
                                                                    $diskon_voucher_toko=($inv->persentase_diskon/100)*$HargaTotal;
                                                                    echo '-Rp.'.number_format($diskon_voucher_toko);
                                                                    echo '<br><small style="color: red; font-size:7pt;">'.$inv->persentase_diskon.'% </small>';
                                                                }else{
                                                                    $diskon_voucher_toko = $inv->nominal_diskon;
                                                                    echo '-Rp'.number_format($diskon_voucher_toko);
                                                                }
                                                                    
                                                 
                                                            ?>
                                                            </span>
                                                        </p>
                                                    <?php } else {
                                                        $diskon_voucher_toko = 0;
                                                    } ?>

                                                    <?php if(!empty($get_data->metode_pembayaran)){?>
                                                    <p><span class="font-weight-medium">Metode Pembayaran :</span> 
                                                        <span class="float-right"> &nbsp;&nbsp;&nbsp;<small> <?= $get_data->metode_pembayaran ?> </small></span></p>  
                                                    
                                                    <?php }?>

                                                    <?php 
                                                        
                                                        $total_pembayaran = 0;
                                                        $total_pembayaran = ($HargaTotal+$total_ongkir_all)-$diskon_voucher_jaja - $diskon_voucher_toko;
                                                    ?>

                                                        <h3>Rp.<?= number_format($total_pembayaran) ?></h3>
                                                    
                                                </div>
                                                <div class="clearfix"></div>
                                            </div> <!-- end col -->
                                        </div>
                                        <!-- end row -->

                                        <div class="mt-5 mb-1">
                                            <div class="text-right d-print-none">
                                                <a href="javascript:window.print()" class="btn btn-primary"><i
                                                        class="uil uil-print mr-1"></i> Print</a>
                                                <!-- <a href="#" class="btn btn-info">Submit</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div> <!-- container-fluid -->

                </div> <!-- content -->

