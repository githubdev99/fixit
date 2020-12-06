
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
            </ul>
            <div class="card-body tab-content p-3">
                <div class="tab-pane show active" id="informasi">
                    <h5>Detail Rating Produk</h5> 
                    <hr>
                    <?php 
                        if (!empty($get_data->foto)) {
                            $foto = $this->data['folder_upload'] . 'products/' . $get_data->foto;
                        } else {
                            $foto = $this->data['link_seller'] . 'asset/images/img-thumbnail.svg';
                        }

                        if($avg == 1 || $avg > 1  && $avg < 2){
                            $rate ='
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star "></span>
                                    <span class="fa fa-star "></span>
                                    <span class="fa fa-star "></span>
                                    <span class="fa fa-star "></span>
                                    ';
                        }elseif($avg == 2 || $avg > 2  && $avg < 3){
                            $rate ='
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star "></span>
                            <span class="fa fa-star "></span>
                            <span class="fa fa-star "></span>
                            ';
                        }elseif($avg == 3 || $avg > 3  && $avg < 4){
                            $rate ='
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star "></span>
                            <span class="fa fa-star "></span>
                            ';
                        }elseif($avg == 4 || $avg > 4  && $avg < 5 ){
                            $rate ='
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star "></span>
                            ';
                        }
                        elseif($avg == 5){
                            $rate ='
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span> 
                            ';
                        }else{
                            $rate='';
                        }
                    ?>

                    <div class="row">
                        <div class="col-md-4">
                           
                            <div class="row">
                            
                                <div class="col">
                                <br>
                                    <a class="image-popup" href="<?= $foto ?>">
                                    &emsp;&emsp; <img src="<?= $foto ?>" width="200" class="img-thumbnail img-responsive">
                                    </a>
                                </div>
                                <div class="col">
                                <br><br>
                                    <h5><a href="https://jaja.id/produk/<?= $get_data->slug_produk ?>" target="_blank" class="text-blue-jaja text-bold"> <?= substr($get_data->nama_produk,0,30); ?></a></h5>
                                    <div class="RatingStar" style="font-size:12px;">
                                       <span style="font-size:25px;"><b><?= number_format($avg,1) ?></b></span>/5.0
                                         <?= $rate ?><br>
                                    </div>		
                                    <b><?= $ulasan ?></b> x <small>Ulasan</small><br><br>
                                    <b>Rp.<?= number_format($get_data->harga) ?></b><br>			
                                    <small>Kategori : </small> <?= $get_data->kategori ?><br>                                    
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            
                            <div class="table-responsive after-loading-produk" >
                            <table id="datatable11" class="table table-bordered table-hover" style="width:100%; align:center;">
                            
                            <thead class="thead-light">
                                <tr >
                                    <th width="5%"></th>
                                    <th></th>
                                </tr>
                            </thead>
                                                
                                <tbody>
                                </tbody>
                            </table>
                            </div>

                        </div>



                    </div>
                    <br>

                    <!-- <div class="table-responsive after-loading-produk" >
                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%; align:center;">
                        
                        <thead class="thead-light">
                            <tr >
                                <th width="5%">No.</th>
                                <th>Produk</th>    
                                <th> <center>Rating <i class="fas fa-star "></i></center></th>

                                <th>Comment</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                                            
                            <tbody>
                            </tbody>
                         </table>
                    </div> -->

                <!-- <div class="tab-pane " id="banner">
                    <h5>Rating Produk</h5>
                    <hr>
              
                </div> -->


                

        
            </div>
        </div>
    </div>
</div>



<div class="modal fade"  id="ModalRespon" name="ModalRespon" tabindex="-1" role="dialog" aria-hidden="true">
	<div  role="document" class="modal-dialog modal-dialog-centered modal-xl" style="width:25%;">
	    <form method="POST" action="<?= base_url() ?>review/rating/edit/<?= $get_data->id_produk ?>" enctype="multipart/form-data" name="form_respon">
			<div class="modal-content">			
				<div class="modal-body" >
                <button type="button" class="swal2-close" data-dismiss="modal" aria-label="Close this dialog" style="display: flex;">×</button>
                    
                    <span class="swal2-icon " style="display: flex; width:400px;" >
                        <h2>Beri Tanggapan</h2>
                    </span>
                   
                    <div class="" style="display: flex; float:center;" > 
                    <textarea name="respon"  id="respon"  class="form-control"> Masukkan tanggapan anda </textarea>
                    <!-- <input type="text" name="respon"  id="respon"  class="form-control" placeholder="Masukkan tanggapan anda" required=""> -->
                    <input type="hidden" name="rating_id" class="form-control" required="" id="rating_id">
	   
                    </div>	
				</div>
	
				<div class="modal-footer border-top">
                     <button type="button" class=" swal2-confirm swal2-styled"  style="display: inline-block; background-color: rgb(221, 51, 51);" data-dismiss="modal">Batal</button>
                    <button type="submit" name="submit" value="submit" class="swal2-cancel swal2-styled"  style="display: inline-block; background-color: rgb(100, 176, 201);">Submit</button> 

				</div>

			</div>
		</form>
	</div>
</div>