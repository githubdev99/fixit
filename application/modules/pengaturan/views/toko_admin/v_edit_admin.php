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
                    <h5>Edit Data Admin</h5>
                    <small>Masukkan Alamat Email dan Nama orang yang ingin Anda tambahkan sebagai Admin. </small>
                    <hr>
                <div class="row">

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body pt-2 pb-3">      

                            <form action="<?= base_url() ?>pengaturan/tokoadmin/edit/<?= encrypt_text($get_data->id_data) ?>" method="post" enctype="multipart/form-data" name="form_edit">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mt-3 mt-sm-0">
                                            <label>Alamat Email<span class="text-danger">*</span></label>
                                             <input type="text" name="email" class="form-control" value="<?= $get_data->email ?> " readonly >
                                                             
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mt-3 mt-sm-0">   
                                            <label>Nama Admin <span class="text-danger">*</span></label>                                         
                                            <input type="text" name="nama_lengkap" class="form-control" value="<?= $get_data->nama_lengkap ?> " required readonly>                                          
                                                                                   
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mt-3 mt-sm-0">
                                            <label>Level Admin<span class="text-danger">*</span></label>
                                             <input type="text" name="level_admin" class="form-control" value="<?= $get_data->level_admin ?>" required >
                                                             
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-right">
                                        <button type="submit" name="edit" value="edit"  id="edit" class="btn btn-info">Update</button>                                   
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



