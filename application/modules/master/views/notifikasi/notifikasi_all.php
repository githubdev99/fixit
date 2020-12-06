                   <!-- Start Content-->
                    <div class="container-fluid">
                        <div class="row page-title mt-2 d-print-none">
                            <div class="col-md-12">
                           
                                <h4 class="mb-1 mt-0">Semua Pemberitahuan</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">     

                                    
                                        <?php  
                                            $get_notif_wish = $this->master_model->select_data([
                                                'field' => 'notifikasi.*,notifikasi.id_data as id_trans ,produk_wishlist.*,customer.*',
                                                'table' => 'notifikasi',
                                                'join' => [
                                                    [
                                                        'table' => 'produk_wishlist',
                                                        'on' => 'notifikasi.id_wish = produk_wishlist.id_data',
                                                        'type' => 'left'
                                                    ],
                                                    [
                                                        'table' => 'customer',
                                                        'on' => 'customer.id_customer = notifikasi.id_user',
                                                        'type' => 'left'
                                                    ]                                                
                                                ],                                                  
                                                'where' => [
                                                    'notifikasi.id_toko' => $this->data['seller']->id_toko,
                                                    'notifikasi.notifikasi_seller' => 'T'
                                                ],
                                                'order_by' => [
                                                    'notifikasi.id_notif' => 'desc'
                                                ]
                                            ])->result();

                                            foreach ($get_notif_wish as $key) {
                                                $text=NULL;
                                                $message=NULL;
                                                $icon=NULL;
                                                $href = base_url().'penjualan/pesanan/detail/'.encrypt_text($key->id_trans);
                                                switch ($key->text) {
                                                    case 'Pesanan Dibatalkan':
                                                        $message = '<b>Pesanan Dibatalkan</b>
                                                                    <br><span>Yaah :( Pesanan dengan nomor invoice <b># '.$key->invoice.' </b>dibatalkan </span>
                                                                    <br>'.$key->created_date.'';
                                                        $text = '<span  style="background:#EBFFEF!important;color:#ff0b21!important;">'.$key->text.'</span>';
                                                        $icon = 'https://cdn.icon-icons.com/icons2/2387/PNG/512/shopping_cart_market_ecommerce_icon_144576.png';
                                                        
                                                    break;
                                                    
                                                    case 'Pesanan Telah dibayar':
                                                        $message = '<b>Pesanan Telah Dibayar</b>
                                                                    <br><span>Siapkan Produk Anda ! Pesanan dengan nomor invoice <b># '.$key->invoice.' </b> telah dibayar </span>
                                                                    <br>'.$key->created_date.'';
                                                        $text = '<span  style="background:#EBFFEF!important;color:#18B323!important;">'.$key->text.'</span>';
                                                        $icon = 'https://cdn.icon-icons.com/icons2/2387/PNG/512/shopping_cart_market_ecommerce_icon_144576.png';
                                                        
                                                        
                                                    break;
    
                                                    case 'Pesanan Belum Dibayar':
                                                        $message = '<b>Pesanan Telah Dibuat</b>
                                                                     <br><span>Pesanan dengan nomor invoice <b># '.$key->invoice.' </b> belum dibayar</span>
                                                                     <br>'.$key->created_date.'';
                                                        $text = '<span  style="background:#EBFFEF!important;color:#ffbe0b!important;">'.$key->text.'</span>';
                                                        $icon = 'https://cdn.icon-icons.com/icons2/2387/PNG/512/shopping_cart_market_ecommerce_icon_144576.png';    
                                                        

                                                    break;

                                                    case 'Favorit':
                                                        $message = '<b>Favorit</b>
                                                                     <br><span>Produk Toko mu <b>'.$key->produk.'</b> ditambahkan ke favorit oleh <b>'.$key->nama_lengkap.'</b></span>
                                                                     <br>'.$key->created_date.'';
                                                        $text = '';
                                                        $icon = 'https://cdn2.iconfinder.com/data/icons/pittogrammi/142/80-512.png';    
                                                        $href = 'https://jaja.id/produk/'.$key->produk_slug;
                                                                                                                                                       
                                                    break;

                                                    default:
                                                    break;
                                                } 
                                                
                                            ?>
                                            <!-- <a  href="" class="dropdown-item">                                             -->
                                            <button type="button"  href="<?= $href ?>" onclick="read(<?= $key->invoice ?>)" class="dropdown-item ">
                                            
                                                <div class="row">
                                                    <div class="col-1">
                                                            <img src="<?= $icon?>" width="50" class="img-thumbnail img-responsive">
                                                    </div>
                                                    <div class="col-9">
                                                        <?= $message; ?> 
                                                    </div>           
                                                                                     
                                                </div>
                                                <p align="right"><span  style="background:#EBFFEF!important;color:#18B323!important;"><?= $text ?></span></p>
                                                <hr>
                                            </button>
                                 
                                        <?php } ?>                                       
                                 
                                                                    
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div> <!-- container-fluid -->

                </div> <!-- content --> 
       
<script>
    function read($invoice) {		
		$.ajax({
            type: "get",
			url: '<?= base_url() ?>penjualan/pesanan/read/'+invoice,
			success: function () {				
			}
		});
	}
</script>

