<?php
$created_at = explode(' ', $get_data['created_at']);
$updated_at = (!empty($get_data['updated_at'])) ? explode(' ', $get_data['updated_at']) : null;
?>

<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Detail Data Mekanik</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>admin/mechanic" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
                            <strong>Username</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['username'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>No. Telepon</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['phone_number'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Jenis Kelamin</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= ($get_data['gender'] == 'male') ? 'Laki-Laki' : 'Perempuan' ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Tanggal Lahir</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= date_indo(date('d-m-Y', strtotime($get_data['birth_date']))) ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Alamat</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['address'] ?></td>
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
                </table>
            </div>
        </div>
        <div class="float-left">
            <button type="button" onclick="show_modal({ modal: 'delete', id: '<?= $get_data['id'] ?>' })" class="btn btn-danger btn-lg">Hapus</button>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>admin/mechanic/form/<?= $get_data['id'] ?>" class="btn btn-info btn-lg">Edit</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>