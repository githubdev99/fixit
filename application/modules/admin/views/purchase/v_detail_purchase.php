<?php
$created_at = explode(' ', $get_data['created_at']);
?>

<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Detail Data Pembelian Supplier</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>admin/purchase" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="row col-center">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <table border="0" cellpadding="5" style="width: 100%;">
                    <tr>
                        <td class="text-right">
                            <strong>Nama Supplier</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['supplier_name'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Total Qty</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['total_qty'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Total Harga</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['total_price_currency_format'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Tanggal Input</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= date_indo(date('d-m-Y', strtotime($created_at[0]))) ?> <?= $created_at[1] ?></td>
                    </tr>
                </table>
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
                                <th>Qty</th>
                                <th>Harga</th>
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