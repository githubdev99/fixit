<?php
$created_at = explode(' ', $get_data['created_at']);
$updated_at = (!empty($get_data['updated_at'])) ? explode(' ', $get_data['updated_at']) : null;
$jenis = '';

if (!empty($get_data['vehicle'])) {
    $jenis .= 'Kendaraan : ' . $get_data['vehicle']['name'];

    if (!empty($get_data['vehicle']['children'])) {
        $jenis .= '<br>Detail : ' . $get_data['vehicle']['children']['name'];
    }
} else {
    $jenis = 'Umum';
}
?>

<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Detail Data Barang</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>cashier/item" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
                            <strong>Nama</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['name'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Jenis</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $jenis ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Harga</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['price_currency_format'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Stok</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['stock'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Tanggal Input</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= date_indo(date('d-m-Y', strtotime($created_at[0]))) ?> <?= $created_at[1] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Tanggal Update</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= (!empty($updated_at)) ? date_indo(date('d-m-Y', strtotime($created_at[0]))) . ' ' . $updated_at[1] : 'Belum Update'; ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Status</strong>
                        </td>
                        <td>&ensp;</td>
                        <td>
                            <?php if ($get_data['in_active'] != 0) : ?>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="<?= $get_data['id'] ?>" onclick="show_modal({ modal: 'not_active', id: '<?= $get_data['id'] ?>' })" checked disabled>
                                    <label class="custom-control-label" for="<?= $get_data['id'] ?>"></label>
                                </div>
                            <?php else : ?>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="<?= $get_data['id'] ?>" onclick="show_modal({ modal: 'active', id: '<?= $get_data['id'] ?>' })" disabled>
                                    <label class="custom-control-label" for="<?= $get_data['id'] ?>"></label>
                                </div>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>