     <!-- Start Content-->
                    <div class="container-fluid">
                        <div class="row page-title mt-0 d-print-none">
                            <div class="col-md-6">
                           
                                <h4 class="mb-0 mt-0">Cetak Label</h4>
                                <div class="text-right d-print-none">
                                            <a href="javascript:window.print()" onclick="Cetak(<?=$get_data->invoice?>)" type="button" class="btn btn-primary"><i class="uil uil-print mr-1"></i> Print</a>
                                            </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Logo & title -->
                                        <div class="clearfix">
                                            <div class="float-sm-right">
                                                <address class="pl-2 mt-2">
                                                     <h6 align="right"><b>PESANAN</b> </h6>                                                   
                                                     <h6 style="color:#64B0C9"><b>#<?= $get_data->invoice ?></b> </h6>                                                   
                                                </address>
                                            </div>
                                            <img src="https://seller.jaja.id/asset/images/logo-full.png" width="150px" >
                                            
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-2">

                                            <?php 
                                                $Folder='https://nimda.jaja.id/asset/front/images/file/'.$get_data->icon;
                                           
                                            ?>
                                            <img src="<?= $Folder ?>" width="80px" >
                                           
                                            </div>
                                        </div>

                                        <p style="font-size:9pt;"><br>
                                                <i>Transaction on</i> : <?php echo date('d M Y',strtotime($get_data->created_date)).' '.$get_data->created_time; ?>
                                                <i>(Asia/Jakarta)</i>
                                                <!-- <br>Nomor Resi: <b><?= $get_data->nomor_resi ?> </b><br> --><br>
                                                (<?= $get_data->pengiriman ?>)
                                        </p>

                                        <table style="background:#fff!important;">
                                            <tbody style="background:#fff!important; font-size:8pt;">
                                                <tr>
                                                    <td width="50%" align="center">
                                                      
                                                        <img src="https://seller.jaja.id/asset/front/images/qrcode.jpg" width="125px" >
                                                    </td>
                                                    <td>
                                                        <br><b>Kepada:</b><br>
                                                        <!-- <b><?php echo strtoupper($get_data->nama_customer); ?></b>
                                                        <p>
                                                            <?php echo ucwords(strtolower($get_data->alamat_pengiriman));?><br>
                                                            <?php echo $get_data->telepon;?>
                                                        </p> -->
                                                        <?php 
                                                            if(!empty($get_data->nama_penerima)){
                                                                echo '<b>'.strtoupper($get_data->nama_penerima).'</b><br>'.$get_data->telp_penerima.'<br>';
                                                            }else{
                                                                echo '<b>'.strtoupper($get_data->nama_lengkap).'</b><br>'.$get_data->telepon.'<br>';

                                                            }                                                    
                                                        ?>

                                                        <p>
                                                            <?php echo ucwords(strtolower($get_data->alamat_pengiriman));?><br>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table style="background:#fff!important;">
                                            <tbody style="background:#fff!important; font-size:8pt;">
                                                <tr>
                                                    <td width="50%" align="center">
                                                    <?php

                                                        if($get_data->tipe_pengiriman == 'manual booking'){
                                                            echo '
                                                            <input type="checkbox" value="bulanan" disable readonly> Pickup (Jemput) - <i>Online Booking</i>
                                                            <br>
                                                            &emsp;<input type="checkbox" checked="checked"  value="bulanan" disable readonly > Antar ke Counter - <i>Manual Booking</i>
                                                            <br>';
                                                        }else{
                                                            echo '
                                                            <input type="checkbox"  checked="checked" value="bulanan" disable readonly> Pickup (Jemput) - <i>Online Booking</i>
                                                            <br>
                                                            &emsp;<input type="checkbox"  value="bulanan" disable readonly> Antar ke Counter - <i>Manual Booking</i>
                                                            <br>';
                                                        }	
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <br>&emsp;<b>Pengirim:</b><br>
                                                        <b>&emsp;<?php echo strtoupper($get_data->nama_toko); ?></b>
                                                        <p>
                                                        &emsp; Ciracas, Kota Administrasi Jakarta Timur<br>
                                                        &emsp; 09098738756
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                   
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-centered" width="500px">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Produk</th>
                                                                <th style="width: 15%">Jumlah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php 
                                                            $nomor = 1;
                                                        
                                                            foreach ($get_data_produk as $inv) {
                                                        ?>
                                                            <tr>
                                                                <td><?= $nomor;?></td>
                                                                <td>
                                                                 
                                                                    <span class="font-size-14 mt-0 mb-2"><b><?= $inv->nama_produk?></b></span>

                                                                </td>
                                                                <td><?= $inv->qty ?> pcs</td>
                                                            </tr>
                                                        <?php
                                                              
                                                        } ?>

                                                        </tbody>
                                                    </table>
                                                    <p> <span class="float-right">Biaya Ongkir : <b>Rp. <?= number_format($get_data->biaya_ongkir,0); ?></b></span></p>
                                                    <br><br><p> <span class="float-right">Berat : 1 kg </b></span></p>

                                                    <small class="text-muted">Pesan Customer:</small><br>
                                                    <small class="text-muted">
                                                    <?php 
                                                        if(!empty($get_data->pesan_customer_toko)){
                                                            echo '<i>'.$get_data->pesan_customer_toko.'</i>';
                                                        }else{
                                                            echo '<i>[Tidak Ada Pesan dari Pembeli].</i>';
                                                        }
                                                    ?> 
                                                    </small><br>
                                                    
                                                    
                                                    <small class="text-muted">Catatan Package:</small><br>
                                                    <small class="text-muted">
                                                    <?php 
                                                       if(!empty($inv->catatan_package_toko)){
                                                        echo '<i>'.$inv->catatan_package_toko.'</i>';
                                                        }else{
                                                            echo '<i>[Tidak Ada Catatan Package].</i>';
                                                        }
                                                    ?> 
                                                    </small>

                                                </div> <!-- end table-responsive -->
                                            </div> <!-- end col -->
                                        </div>
                                        <!-- end row -->

                                       

                                       
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div> <!-- container-fluid -->

                </div> <!-- content -->



