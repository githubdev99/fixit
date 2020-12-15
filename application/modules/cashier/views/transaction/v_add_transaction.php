<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Tambah Data Transaksi</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>cashier/transaction" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<form action="<?= site_url(uri_string()) ?>" method="post" enctype="multipart/form-data" name="add">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="customer_name" class="form-control add" placeholder="Masukkan nama" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Kendaraan</label>
                                <div class="col-lg-5">
                                    <select class="form-control select2 add" name="vehicle_id" data-placeholder="Pilih salah satu">
                                    </select>
                                </div>
                                <div class="col-lg-5">
                                    <select class="form-control select2 add" name="vehicle_children_id" data-placeholder="Pilih salah satu">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Servis</label>
                                <div class="col-lg-10">
                                    <select class="form-control select2 add" name="service_data" data-placeholder="Pilih salah satu">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Barang</label>
                                <div class="col-lg-4">
                                    <select class="form-control select2 add" id="item_option" data-placeholder="Pilih salah satu">
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" id="qty" class="form-control add" placeholder="Kuantitas" onkeypress="number_only(event)">
                                    <small class="help-block mt-1 ml-1">Hanya berisi angka (0-9)</small>
                                </div>
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-success add_detail">Tambah Barang</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="row page-title ml-2 mr-2">
            <div class="col-12">
                <h4 class="mb-1 mt-0">Detail Barang</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead class="table-info">
                                <tr>
                                    <th>No.</th>
                                    <th>Barang</th>
                                    <th>Kuantitas</th>
                                    <th>Sub Total</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td align="right" colspan="3"><b>Total Kuantitas</b></td>
                                    <td align="right" colspan="2">
                                        <span id="total_qty">0</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="3"><b>Total Harga</b></td>
                                    <td align="right" colspan="2">
                                        <span id="total_price">Rp. 0</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col text-right">
                    <input type="hidden" name="item_data">
                    <a href="<?= base_url() ?>cashier/transaction" class="btn btn-soft-dark btn-lg mr-2">Batal</a>
                    <button type="submit" class="btn btn-info btn-lg" name="add" value="add">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>