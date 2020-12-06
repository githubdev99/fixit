<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0"><i class="fas fa-store mr-2"></i> <?= $core['seller']->nama_toko ?></h4>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#admin" data-toggle="tab" aria-expanded="false" class="nav-link active">
                        Pengaturan Admin
                    </a>
                </li>

            </ul>
            <div class="card-body tab-content p-3">
                <div class="tab-pane show active" id="admin">
                    <!-- <p align="center"><textarea class="form-control" style="width:90%; height:300px;">
                    </textarea></p> -->
                    <h5>Data Admin</h5>
                    <small>Masukkan Alamat Email dan Nama orang yang ingin Anda tambahkan sebagai Admin. </small>
                    <hr>
                <div class="row">

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body pt-2 pb-3">

                            <form action="" method="GET" enctype="multipart/form-data" name="form_cek">
                                <?php
                                    $dataSearch = null;
                                    $text = null;                                   
                                    if (isset($_GET['email'])) {
                                        $dataSearch = $_GET['email'];                                     
                                        $text = '';                                     
                                    } else {
                                        $dataSearch = null;
                                        $text = '<small> Email belum terdaftar sebagai customer ! </small>';
                                    } 
                                ?>
                            <?php
                                $dataadmin = $this->master_model->select_data([
                                    'field' => '*',
                                    'table' => 'customer',
                                    'where' => [
                                        'email' =>  $dataSearch
                                    ]            
                                ])->result();                 

                            ?>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group ">
                                            <label>Alamat Email</label>                                            
                                            <div class="input-group">
                                                <input class="form-control" type="text" value="<?= $dataSearch ?>" size="50"  placeholder="" name="email" id="email" required />                                             
                                                <button type="submit"  class="btn btn-soft-dark" >&emsp;Cek&emsp;</button>
                                            </div>
                               
                                            <?php 
                                                if(!empty($dataadmin)){
                                                    foreach ($dataadmin as $key) { 
                                                        echo '';
                                                    }
                                                }else {
                                                    echo '<small> Masukkan Email yang sudah terdaftar ! </small>';
                                                }
                                            ?>       
                                        </div>
                                    </div>
                                </div>
                            </form>

                                 
                           

                           
                            
                            <form action="<?= base_url() ?>pengaturan/tokoadmin" method="post" enctype="multipart/form-data" name="form_tambah">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mt-3 mt-sm-0">   
                                            <label>Nama Admin <span class="text-danger">*</span></label>                                         
                                            <?php 
                                                if(!empty($dataadmin)){
                                                    foreach ($dataadmin as $key) { ?>                                                   
                                                    <input type="text" name="nama_lengkap" class="form-control" value=" <?= $key->nama_lengkap; ?>" required >
                                                    <input type="hidden" name="id_customer" class="form-control" value=" <?= $key->id_customer; ?>" required >
                                                    <input type="hidden" name="email" class="form-control" value=" <?= $key->email; ?>" required >
                                            <?php   }
                                                }else{ ?>
                                                    <input type="text" name="nama_lengkap" class="form-control" value="" required readonly>                                                   
                                            <?php }?>                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mt-3 mt-sm-0">
                                            <label>Level Admin<span class="text-danger">*</span></label>
                                             <input type="text" name="level_admin" class="form-control" value="" required >
                                                             
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-right">
                                            <?php 
                                                if(!empty($dataadmin)){
                                                    foreach ($dataadmin as $key) { ?>                                                   
                                                     <button type="submit" name="tambah" value="tambah"  id="tambah" class="btn btn-info">Tambahkan</button>
                                            <?php   }
                                                }else{ ?>
                                                     <button type="button" class="btn btn-info">Tambahkan</button>
                                                   
                                            <?php }?>                                       
                                    </div>
                                </div>
                            </form>

                            </div>
                        </div>
                    </div>


                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body ">
                                <div class="table-responsive after-loading-produk" >
                                    <table id="datatable" class="table table-bordered table-hover" style="width:100%; align:center;">
                                        <thead class="thead-light">
                                            <tr  >
                                                <th width="5%">No.</th>
                                                <th>Nama Admin</th>                                          
                                                <th>Level</th>
                                                <th>Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>

                </div>
            </div>
        </div>
    </div>
</div>



