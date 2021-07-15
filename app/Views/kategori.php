<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>

<style>
   .box {
      text-align: center;
      height: 160px;
      border: 5px solid #ccc;
      border-bottom: 0;
      background-color: #fff;
      overflow: hidden;
   }

   .box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
   }

   .box img:hover {
      transition: 1s all ease;
      transform: scale(1.1);
   }

   .text {
      padding: 5px 3px;
      background-color: #fff;
      text-align: center;
      color: #000;
      border: 5px solid #ccc;
      border-top: 0;
      border-bottom: 0;
      display: block;
   }

   .text:hover {
      background-color: #eee;
      transition: .3s all ease;
   }

   .text h6 {
      display: inline;
   }
</style>
<div class="modal fade" id="modal-tambah-kategori">
   <div class="modal-dialog">
      <div class="modal-content">
         <form id="tambah-kategori" enctype="multipart/form-data">
            <div class="modal-header">
               <h4 class="modal-title">Tambah Kategori</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label for="nama-kategori">Nama Kategori</label>
                  <input name="nama_kategori" type="text" class="form-control" id="nama-kategori" placeholder="Nama kategori" autofocus required>
               </div>
               <div class="form-group">
                  <label for="foto-kategori">Foto Kategori</label>
                  <div class="input-group">
                     <div class="custom-file">
                        <input name="foto" type="file" class="custom-file-input" id="foto-kategori" accept="image/jpg, image/jpeg">
                        <label class="custom-file-label" for="foto-kategori">Pilih foto</label>
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
<div class="card">
   <div class="card-header">
      <div class="card-title">Data Kategori</div>
   </div>
   <div class="card-body">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah-kategori"><i class="fa fa-plus mr-1"></i>Tambah kategori</button>
      <div class="row">
         <?php foreach ($dataKategori as $row) : ?>
            <div class="col-6 col-sm-4 col-md-3 p-3 boxed">
               <div class="box">
                  <a href="<?= base_url('kategori') ?>/<?= $row->slug ?>">
                     <img src="<?= base_url('assets/img') ?>/<?= $row->foto ?>">
                  </a>
               </div>
               <a href="<?= base_url('kategori') ?>/<?= $row->slug ?>" class="text">
                  <h6><?= $row->nama_kategori ?></h6>
               </a>
               <div class="row px-2">
                  <div class="col-6 p-0">
                     <a href="<?= base_url('kategori') ?>/<?= $row->slug ?>" class="btn btn-sm btn-flat w-100 btn-primary"><i class="fa fa-edit mr-1"></i>Ubah</a>
                  </div>
                  <div class="col-6 p-0">
                     <button type="button" class="btn btn-sm btn-flat w-100 btn-danger hapus-kategori m-0" data-slug="<?= $row->slug ?>"><i class="fa fa-trash-alt mr-1"></i>Hapus</button>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('footer') ?>

<script>
   $(document).ready(function() {
      function reset_form() {
         $('#modal-tambah-kategori').modal('hide')
         $('#tambah-kategori').trigger('reset')
         $('#preview').attr('src', '')
         $('.custom-file-label').text('')
      }
      $('#reset-form').click(function(e) {
         e.preventDefault()
         reset_form()
      })
      $('#tambah-kategori').submit(function(e) {
         e.preventDefault()
         var data = new FormData($(this)[0]);
         $.ajax({
            url: "<?= base_url('tambahkategori') ?>",
            type: "post",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
               // 
            },
            success: function(hasil) {
               var obj = JSON.parse(hasil)
               if (obj.error == 0) {
                  Swal.fire({
                     title: "Berhasil",
                     text: obj.msg,
                     icon: "success",
                     showConfirmButton: false,
                     timer: 2000
                  }).then(function() {
                     window.location.reload()
                  })
                  reset_form();
               } else {
                  Swal.fire({
                     title: "Gagal",
                     text: obj.msg,
                     icon: "error",
                     showConfirmButton: false,
                     timer: 2000
                  })
                  reset_form();
               }
            }
         })
         return false;
      })
      $('.hapus-kategori').click(function(e) {
         e.preventDefault()
         var slug = $(this).data('slug');
         var data_kategori = $(this).parents('.boxed');
         Swal.fire({
            title: "Peringatan",
            text: "Yakin ingin menghapus kategori?",
            icon: "warning",
            showCancelButton: true
         }).then((result) => {
            if (result.value) {
               $.ajax({
                  url: "<?= base_url('kategori') ?>/" + slug,
                  type: "delete",
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
                        data_kategori.remove()
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
            }
         })
      })
      $(document).on('change', '#foto-kategori', function() {
         var fileSize = this.files[0].size / 1024 / 1024;
         if (fileSize > 2) {
            Swal.fire({
               title: "Peringatan",
               text: "Ukuran tidak boleh lebih dari 2 MB",
               icon: "warning",
               showConfirmButton: false,
               timer: 2000
            })
         } else {
            if (this.files && this.files[0]) {
               var reader = new FileReader();
               reader.onload = function(e) {
                  $('#preview').attr('src', e.target.result);
               };
               reader.readAsDataURL(this.files[0]);
               $('.custom-file-label').text(this.files[0].name)
            }
         }
      })
   })
</script>

<?= $this->endSection() ?>