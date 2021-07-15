<?= $this->extend('template/template') ?>

<?= $this->section('header') ?>

<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
</link>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
   <div class="card-body">
      <table id="tbl_barang_masuk" class="table table-bordered w-100">
         <thead>
            <tr>
               <th>No</th>
               <th>Produk</th>
               <th>Stok</th>
               <th>Harga</th>
               <th>Tanggal</th>
            </tr>
         </thead>
      </table>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('template/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
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

      var tbl_barang_masuk = $('#tbl_barang_masuk').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         scrollX: true,
         order: [
            [4, 'desc']
         ],
         ajax: {
            url: "<?= base_url('barang/masuk') ?>",
            type: "post"
         },
         columns: [{
               "data": "id",
               "mRender": function(data, row, type, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               }
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
            }
         ]
      })

      var tbl_barang_keluar = $('#tbl_barang_keluar').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         scrollX: true,
         order: [
            [4, 'desc']
         ],
         ajax: {
            url: "<?= base_url('barang/keluar') ?>",
            type: "post"
         },
         columns: [{
               "data": "id",
               "mRender": function(data, row, type, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               }
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
            }
         ]
      })

   })
</script>

<?= $this->endSection() ?>