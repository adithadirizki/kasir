<?= $this->extend('template/template') ?>

<?= $this->section('header') ?>

<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
</link>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
   <div class="modal fade" id="modal-tambah-produk">
      <div class="modal-dialog">
         <div class="modal-content">
            <form id="tambah-produk" enctype="multipart/form-data">
               <div class="modal-header">
                  <h4 class="modal-title">Tambah produk</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="form-group">
                     <label for="nama-produk">Nama Produk</label>
                     <input name="nama_produk" type="text" class="form-control" id="nama-produk" placeholder="Nama produk" autofocus required>
                  </div>
                  <div class="form-group">
                     <label>Kategori Produk</label>
                     <select class="form-control" name="kategori">
                     </select>
                  </div>
                  <div class="form-group">
                     <label for="harga">Harga</label>
                     <input name="harga" type="number" class="form-control" id="harga" placeholder="100000" required>
                  </div>
                  <div class="form-group">
                     <label for="stok">Stok</label>
                     <input name="stok" type="number" class="form-control" id="stok" placeholder="Stok" required>
                  </div>
                  <div class="form-group">
                     <label for="foto-produk">Foto Produk</label>
                     <div class="input-group">
                        <div class="custom-file">
                           <input name="foto" type="file" class="custom-file-input" id="foto-produk" accept="image/jpg, image/jpeg">
                           <label class="custom-file-label" for="foto-produk">Pilih foto</label>
                        </div>
                     </div>
                  </div>
                  <div class="form-group col-12">
                     <img id="preview" src="" width="80px">
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="submit" class="btn btn-primary btn-block">Tambah</button>
                  <button type="button" class="btn btn-danger btn-block" id="reset-form">Batal</button>
               </div>
            </form>
         </div>
         <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
   </div>
   <div class="col">
      <div class="card">
         <div class="card-header border-transparent">
            <h3 class="card-title">Data Produk</h3>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <form id="produk" action="buatpesanan" method="post">
               <button type="button" id="buat-pesanan" class="btn btn-primary mb-3"><i class="fa fa-check-circle mr-1"></i>Buat Pesanan</button>
               <a href="<?= base_url('tambahproduk') ?>" class="btn btn-success mb-3"><i class="fa fa-plus mr-1"></i>Tambah Produk</a>
               <table id="tbl_produk" class="table w-100">
                  <button type="button" id="hapus-produk" class="btn btn-danger mb-3"><i class="fa fa-trash-alt mr-1"></i>Hapus Produk</button>
                  <thead>
                     <tr>
                        <th><input type="checkbox" class="check-all"></th>
                        <th>#</th>
                        <th>Foto</th>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Tgl. Masuk</th>
                        <th>Aksi</th>
                     </tr>
                  </thead>
               </table>
            </form>
            <!-- /.table-responsive -->
         </div>
         <!-- /.card-body -->
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('template/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script>
   $(document).ready(function() {
      // Hapus data id_produk di local storage
      localStorage.removeItem('id_produk')
      $('.check-all').click(function() {
         $('input[name="produk[]"]').prop('checked', this.checked)
         if (this.checked == true) {
            var id_produk = [];
            document.querySelectorAll('.check-id').forEach((val) => {
               id_produk.push($(val).val())
            })
            localStorage.setItem('id_produk', JSON.stringify(id_produk))
         } else {
            localStorage.removeItem('id_produk')
         }
      })
      $(document).on('change', '.check-id', function() {
         var val_produk = $(this).val();
         if (this.checked == true) {
            if (localStorage.getItem('id_produk') === null) {
               var id_produk = [];
            } else {
               var id_produk = JSON.parse(localStorage.getItem('id_produk'));
            }
            id_produk.push(val_produk);
            localStorage.setItem('id_produk', JSON.stringify(id_produk))
         } else {
            var id_produk = JSON.parse(localStorage.getItem('id_produk'));
            var index = id_produk.indexOf(val_produk);
            id_produk.splice(index, 1);
            localStorage.setItem('id_produk', JSON.stringify(id_produk))
         }
      })
      $('#buat-pesanan').click(function() {
         if ($('.check-id:checked').length == 0) {
            Swal.fire({
               title: "Peringatan",
               text: "Pilih minimal satu produk",
               icon: "warning",
               showConfirmButton: false,
               timer: 2000
            })
            return false
         }
         localStorage.removeItem('id_produk')
         $('#produk').submit()
      })
      $('#hapus-produk').click(function(e) {
         e.preventDefault()
         var data = $('#produk').serialize();
         if ($('.check-id:checked').length === 0) {
            Swal.fire({
               title: "Peringatan",
               text: "Pilih minimal satu produk",
               icon: "warning",
               showConfirmButton: false,
               timer: 2000
            })
         } else {
            $.ajax({
               url: "<?= base_url('hapusproduk') ?>",
               type: "post",
               data: data,
               beforeSend: function() {
                  // 
               },
               success: function(hasil) {
                  var obj = JSON.parse(hasil);
                  if (obj.error == 0) {
                     Swal.fire({
                        title: "Berhasil",
                        text: obj.msg,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000
                     })
                     tbl_produk.ajax.reload()
                  } else {
                     Swal.fire({
                        title: "Gagal",
                        text: obj.msg,
                        icon: "error",
                        showConfirmButton: false,
                        timer: 2000
                     })
                  }
               }
            })
            return false
         }
         return false
      })
      var tbl_produk = $('#tbl_produk').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         scrollX: true,
         bLengthChange: true,
         lengthMenu: [
            [5, 10, 50, 100, -1],
            [5, 10, 50, 100, "All"]
         ],
         ajax: {
            url: "<?= base_url('allproduk') ?>",
            type: "post"
         },
         columns: [{
               "data": "id",
               "mRender": function(id) {
                  return '<input type="checkbox" name="produk[]" class="check-id" value="' + id + '">';
               },
               "orderable": false
            },
            {
               "data": null,
               "mRender": function(data, row, type, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               }
            },
            {
               "data": "foto",
               "mRender": function(foto) {
                  return '<img src="<?= base_url('assets/img') ?>/' + foto + '" style="width:70px">';
               }
            },
            {
               "data": "id"
            },
            {
               "data": "nama_produk"
            },
            {
               "data": "nama_kategori"
            },
            {
               "data": "harga"
            },
            {
               "data": "stok"
            },
            {
               "data": "created_at"
            },
            {
               "data": "id",
               "mRender": function(id) {
                  return '<a href="<?= base_url('produk') ?>/' + id + '" class="btn btn-xs btn-primary text-bold mr-1"><i class="fa fa-eye mr-1"></i>DETAIL</a><a href="" class="btn btn-xs btn-danger text-bold"><i class="fa fa-trash-alt mr-1"></i>HAPUS</a>';
               },
               "class": "text-nowrap"
            }
         ],
         language: {
            "url": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
         },
         drawCallback: function() {
            var id_produk = JSON.parse(localStorage.getItem('id_produk'));
            if (id_produk !== null) {
               id_produk.forEach(checked_box)
            }

            function checked_box(index) {
               $('input[name="produk[]"][value="' + index + '"]').prop('checked', 'checked')
            }
         }
      })
   })
</script>

<?= $this->endSection() ?>