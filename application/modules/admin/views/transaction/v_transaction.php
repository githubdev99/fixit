<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Data Transaksi</h4>
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
                                <th>Invoice</th>
                                <th>Kasir</th>
                                <th>Antrian</th>
                                <th>Total</th>
                                <th>Detail</th>
                                <th>Status</th>
                                <th>Opsi</th>
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

<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="datatable_detail" class="table table-bordered table-hover" style="width: 100%;">
                                <thead class="table-info">
                                    <tr>
                                        <th>No.</th>
                                        <th>Barang</th>
                                        <th>Kuantitas</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>