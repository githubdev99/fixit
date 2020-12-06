<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Review</h4>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#informasi" data-toggle="tab" aria-expanded="false" class="nav-link active">
                        Report Produk
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a href="#banner" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Rating Produk
                    </a>
                </li> -->


            </ul>
            <div class="card-body tab-content p-3">
                <div class="tab-pane show active" id="informasi">
                    <h5>Daftar Report Produk</h5>
                    <span style="font-size:30px;"> &emsp;<?= $report ?></span> x dilaporkan

                    <hr>

                    <div class="table-responsive after-loading-produk" >
                        <table id="datatable" class="table table-bordered table-hover" style="width:100%; align:center;">
                        <thead class="thead-light">
                        <tr>
                            <th width="5%">No.</th>
                            <th>Produk</th>                                          
                            <!-- <th>Alasan Report</th> -->
                            <th>Jumlah Report</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                                        
                    <tbody>
                    </tbody>
                </table>
            </div> 

            </div>

                <div class="tab-pane " id="banner">
                    <h5>Rating Produk</h5>
                    <hr>
              
                </div>

        
            </div>
        </div>
    </div>
</div>
