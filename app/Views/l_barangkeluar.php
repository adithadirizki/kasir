<?= $this->extend('template/template') ?>

<?= $this->section('header') ?>

<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
</link>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
   <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
         <div class="inner">
            <h3><?= $simply['hari_ini'] ?></h3>
            <p>Barang Keluar Hari Ini</p>
         </div>
         <div class="icon">
            <i class="fa fa-clipboard-list"></i>
         </div>
         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-primary">
         <div class="inner">
            <h3><?= $simply['transaksi_hari_ini'] ?></h3>
            <p>Pendapatan Hari Ini</p>
         </div>
         <div class="icon">
            <i class="fa fa-cart-arrow-down"></i>
         </div>
         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
         <div class="inner">
            <h3><?= $simply['transaksi_tahun_ini'] ?></h3>
            <p>Pendapatan Tahun Ini</p>
         </div>
         <div class="icon">
            <i class="fa fa-money-bill-alt"></i>
         </div>
         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
         <div class="inner">
            <h3><?= $simply['total_transaksi'] ?></h3>
            <p>Total Pendapatan</p>
         </div>
         <div class="icon">
            <i class="fa fa-money-check-alt"></i>
         </div>
         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
</div>
<div class="card">
   <div class="card-body">
      <div class="table-responsive">
         <div class="form-group">
            <label for="range_date">Filter</label>
            <select name="range" id="range_date" class="form-control w-auto">
               <option value="">Semua</option>
               <option value="hari_ini">Hari ini</option>
               <option value="kemarin">Kemarin</option>
               <option value="bulan_ini">Bulan ini</option>
               <option value="tahun_ini">Tahun ini</option>
               <option value="tahun_kemarin">Tahun Kemarin</option>
            </select>
         </div>
         <table id="tbl_barang_keluar" class="table w-100">
            <thead>
               <tr>
                  <th>No</th>
                  <th>Faktur</th>
                  <th>Produk</th>
                  <th>Stok</th>
                  <th>Pemasukan</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tfoot>
               <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('template/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-buttons/js/buttons.html5.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<script>
   $(document).ready(function() {
      function to_rupiah(amount) {
         var sisa = String(amount).length % 3;
         rupiah = String(amount).substr(0, sisa),
            ribuan = String(amount).substr(sisa).match(/\d{3}/g);
         if (ribuan) {
            separator = sisa ? '.' : ''
            rupiah += separator + ribuan.join('.')
         }
         return 'Rp ' + rupiah
      }
      var tbl_barang_keluar = $('#tbl_barang_keluar').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         scrollX: true,
         order: [
            [5, 'desc']
         ],
         dom: 'Bfrltip',
         buttons: [{
               extend: "copyHtml5",
               exportOptions: {
                  columns: [0, ':visible']
               },
               className: "btn btn-primary text-bold",
               footer: true
            },
            {
               extend: "excelHtml5",
               exportOptions: {
                  columns: ':visible'
               },
               className: "btn btn-primary text-bold",
               footer: true
            },
            {
               extend: "pdfHtml5",
               exportOptions: {
                  columns: ':visible'
               },
               className: "btn btn-primary text-bold",
               footer: true
            },
            {
               extend: "print",
               exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5]
               },
               className: "btn btn-primary text-bold",
               footer: true
            },
            {
               extend: "colvis",
               className: "btn btn-secondary text-bold"
            }
         ],
         ajax: {
            url: "<?= base_url('barang/keluar') ?>",
            type: "post",
            data: function(data) {
               data.searchByDate = $('#range_date').val()
            }
         },
         columns: [{
               "data": "id",
               "mRender": function(data, row, type, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               }
            },
            {
               "data": "faktur"
            },
            {
               "data": "nama_produk"
            },
            {
               "data": "stok"
            },
            {
               "data": "harga",
               "mRender": function(harga) {
                  return to_rupiah(harga);
               }
            },
            {
               "data": "created_at"
            },
            {
               "data": "faktur",
               "mRender": function(faktur) {
                  return '<a href="<?= base_url('struk') ?>/'+faktur+'" class="btn btn-xs btn-primary text-bold"><i class="fa fa-eye mr-1"></i>DETAIL</a>';
               }
            }
         ],
         footerCallback: function(tfoot, data, start, end, display) {
            var res = this.api().ajax.json();
            if (res) {
               var th = $(tfoot).find('th');
               th.eq(4).html('Total: ' + res['total_pengeluaran'])
            }
         }
      })
      $('#range_date').change(function() {
         tbl_barang_keluar.draw()
      })
   })
</script>

<?= $this->endSection() ?>