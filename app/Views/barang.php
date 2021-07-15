<?= $this->extend('template/template') ?>

<?= $this->section('header') ?>

<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
</link>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
   <div class="col-md-4">
      <div class="card">
         <div class="card-header bg-gradient-navy">
            <div class="card-title">Form Barang Masuk</div>
         </div>
         <form id="form_barang" action="" onsubmit="return false">
            <div class="card-body">
               <div class="form-group">
                  <label>Produk</label>
                  <select name="id" class="form-control" required>
                     <option value="" selected disabled>Pilih Produk</option>
                     <?php
                     foreach ($produk as $row) {
                     ?>
                        <option value="<?= $row['id'] ?>"><?= $row['nama_produk'] ?></option>
                     <?php
                     }
                     ?>
                  </select>
               </div>
               <div class="form-group">
                  <label for="stok">Stok</label>
                  <input type="number" name="stok" class="form-control" id="stok" placeholder="100" required>
               </div>
               <div class="form-group">
                  <label for="harga">Harga</label>
                  <div class="input-group mb-3">
                     <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                     </div>
                     <input type="text" name="harga" id="harga" class="form-control" placeholder="1.000.000" required>
                  </div>
               </div>
            </div>
            <div class="card-footer">
               <button type="submit" class="btn btn-primary text-bold float-right"><i class="fa fa-check-circle mr-1"></i>SUBMIT</button>
            </div>
         </form>
      </div>
   </div>
   <div class="col-md-8">
      <div class="card">
         <div class="card-body">
            <h4>Barang Masuk</h4>
            <hr>
            <table id="tbl_barang_masuk" class="table table-bordered w-100">
               <thead>
                  <tr>
                     <th>No</th>
                     <th>Produk</th>
                     <th>Qty</th>
                     <th>Pengeluaran</th>
                     <th>Tanggal</th>
                  </tr>
               </thead>
            </table>
            <hr>
            <h4>Barang Keluar</h4>
            <hr>
            <table id="tbl_barang_keluar" class="table table-bordered w-100">
               <thead>
                  <tr>
                     <th>No</th>
                     <th>Produk</th>
                     <th>Qty</th>
                     <th>Pendapatan</th>
                     <th>Tanggal</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
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

      function live_to_rupiah(obj) {
         var num_to_string = $(obj).val().replace(/[^\d]/g, '').toString(),
            split = num_to_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
         if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
         }
         rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
         $(obj).val(rupiah)
      }

      var tbl_barang_masuk = $('#tbl_barang_masuk').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         scrollX: true,
         order: [
            [4, 'desc']
         ],
         aLengthMenu: [[5, 25, 50, 500], [5, 25, 50, 500]],
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
         aLengthMenu: [[5, 25, 50, 500], [5, 25, 50, 500]],
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

      $(document).on('keyup', '#harga', function() {
         live_to_rupiah(this)
      })

      $(document).on('submit', '#form_barang', function(e) {
         e.preventDefault()
         var data = $(this).serialize();
         $.ajax({
            url: "<?= base_url('tambahbarangmasuk') ?>",
            type: "post",
            dataType: "json",
            data: data,
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
                     tbl_barang_masuk.ajax.reload()
                  })
               } else {
                  Swal.fire({
                     title: "Gagal",
                     text: hasil.msg,
                     icon: "error",
                     showConfirmButton: false,
                     timer: 2000
                  })
               }
            }
         })
      })

   })
</script>

<?= $this->endSection() ?>