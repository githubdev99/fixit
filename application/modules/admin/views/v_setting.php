<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Pengaturan Admin</h4>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-row align-items-center mb-4">
                    <div class="col-6">
                        <select class="form-control select2" id="jenis_potongan" data-placeholder="Jenis Potongan" onchange="show_button(this.value, '#filter_data');" required>
                            <option></option>
                            <option value="diskon">Diskon</option>
                            <option value="nominal">Nominal</option>
                        </select>
                    </div>
                    <div class="col">
                        <button type="button" class="ml-3 mr-3 btn btn-info" id="filter_data" disabled><i class="fas fa-filter mr-2"></i>Filter</button>
                        <button type="button" id="clear_filter" class="btn btn-danger hide-element" style="display: none;"><i class="fas fa-sync-alt mr-2"></i>Clear</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered table-hover" style="width: 100%;">
                        <thead class="table-info">
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Jenis Kelamin</th>
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