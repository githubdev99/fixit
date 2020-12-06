<style>

.fa-star{
	color: gray !important;
}
.fa-star.checked{
	color: #ffbe0b !important;
}
</style>

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
                        Rating Produk
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
                    <!-- <h5>Daftar Rating Produk</h5> -->
                    <h4>Rata-Rata Rating Produk</h4>
                    <span style="font-size:30px;" class="fa fa-star checked"></span>
                    <span style="font-size:30px;"><b><?= number_format($avg,1) ?></b></span>/5.0 &emsp;
                    <span style="font-size:30px;"> &emsp;<?= $ulasan ?> </span>Ulasan
                   
                    <hr>
                    <br>
          
                    
                    <div class="table-responsive after-loading-produk" >
                        <table id="datatable" class="table table-bordered table-hover" style="width:100%; align:center;">
                        <thead class="thead-light">
                            <tr  >
                            <th width="5%"><center>No.</th>
                            <th> Produk</th>                                          
                            <th> <center>Rating <i class="fas fa-star"></center></i></th>
                            <th> <center>Jumlah Ulasan</th>
                            <th> <center>Aksi</th>
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


