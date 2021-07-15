<?= $this->extend('template/template') ?>

<?= $this->section('header') ?>

<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
</link>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
   <div class="card-body">
      <a href="<?= base_url('produk') ?>" class="btn btn-primary mb-3"><i class="fa fa-check-circle mr-1"></i>Buat Pesanan</a>
      <div class="table-responsive">
         <table id="riwayatpesanan" class="table">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Faktur</th>
                  <th>Produk</th>
                  <th>Total Item</th>
                  <th>Total Bayar</th>
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
<script>
   $(document).ready(function() {
      var tbl_riwayat_pesanan = $('#riwayatpesanan').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         scrollY: true,
         order: [
            [5, 'desc']
         ],
         ajax: {
            url: "<?= base_url('riwayatpesanan') ?>",
            type: "post"
         },
         columns: [{
               "data": null,
               "mRender": function(data, row, type, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               },
               "orderable": false
            },
            {
               "data": "faktur"
            },
            {
               "data": "produk"
            },
            {
               "data": "item"
            },
            {
               "data": "total_bayar"
            },
            {
               "data": "created_at"
            },
            {
               "data": "faktur",
               "mRender": function(faktur) {
                  // return '<button class="btn btn-xs btn-primary text-bold"><i class="fa fa-edit mr-1"></i>DETAIL</button>';
                  return '<a href="<?= base_url('struk') ?>/' + faktur + '" class="btn btn-xs btn-primary text-bold mr-1"><i class="fa fa-edit mr-1"></i>DETAIL</a><button type="button" class="btn btn-xs btn-danger text-bold hapus-pesanan" data-id="' + faktur + '"><i class="fa fa-trash-alt mr-1"></i>HAPUS</button>';
               },
               "orderable": false
            }
         ],
         footerCallback: function(tfoot, raw, start, end, display) {
            var res = this.api().ajax.json();
            if(res) {
               var th = $(tfoot).find('th');
               th.eq(4).html(res['total_pendapatan']);
            }
         }
      })
      $(document).on('click', '.hapus-pesanan', function() {
         Swal.fire({
            title: "Peringatan",
            text: "Yakin ingin menghapus ?",
            icon: "warning",
            showCancelButton: true
         }).then((result) => {
            if (result.value) {
               $.ajax({
                  url: "<?= base_url('hapuspesanan') ?>/" + $(this).data('id'),
                  type: "delete",
                  dataType: "json",
                  beforeSend: function() {
                     // 
                  },
                  success: function(hasil) {
                     if (hasil.error == false) {
                        Swal.fire({
                           title: "Berhasil",
                           text: hasil.msg,
                           icon: "success",
                           showConfirmButton: false,
                           timer: 2000
                        }).then(function() {
                           tbl_riwayat_pesanan.ajax.reload()
                        })
                     } else {
                        Swal.fire({
                           title: "Berhasil",
                           text: hasil.msg,
                           icon: "error",
                           showConfirmButton: false,
                           timer: 2000
                        })
                     }
                  }
               })
            }
         })
      })
   })
</script>

<?= $this->endSection() ?>