<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<div class="row">
   <div class="col-12 col-sm-5 mb-3">
      <h3 class="d-inline-block d-sm-none"><?= $detailKategori->nama_kategori ?></h3>
      <div class="col-12">
         <a href="<?= base_url('assets/img') ?>/<?= $detailKategori->foto ?>" target="_blank">
            <img id="preview" src="<?= base_url('assets/img') ?>/<?= $detailKategori->foto ?>" class="product-image">
         </a>
      </div>
   </div>
   <div class="col-12 col-sm-7">
      <div class="row">
         <div class="col-12">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Detail Kategori</h3>
               </div>
               <!-- /.card-header -->
               <!-- form start -->
               <form id="ubah-kategori" class="form-horizontal">
                  <div class="card-body">
                     <div class="form-group row">
                        <label for="nama-kategori" class="col-sm-2 col-form-label">Nama kategori</label>
                        <div class="col-sm-10">
                           <input type="hidden" name="old_foto" value="<?= $detailKategori->foto ?>">
                           <input type="text" class="form-control" id="nama-kategori" name="nama_kategori" placeholder="Nama kategori" value="<?= $detailKategori->nama_kategori ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="foto-kategori" class="col-sm-2">Foto Kategori</label>
                        <div class="col-sm-10">
                           <div class="input-group">
                              <div class="custom-file">
                                 <input name="foto" type="file" class="custom-file-input" id="foto-kategori" accept="image/jpg, image/jpeg">
                                 <label class="custom-file-label" for="foto-kategori">Pilih foto</label>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                     <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save mr-1"></i>Simpan</button>
                     <button type="button" id="hapus-kategori" class="btn btn-sm btn-danger float-right"><i class="fa fa-trash-alt mr-1"></i>Hapus</button>
                  </div>
                  <!-- /.card-footer -->
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script>
   $(document).ready(function() {
      $('#ubah-kategori').submit(function(e) {
         e.preventDefault()
         var data = new FormData($(this)[0]);
         $.ajax({
            url: "<?= base_url('kategori') ?>/<?= $detailKategori->slug ?>",
            type: "post",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            beforeSend: function() {
               // 
            },
            success: function(hasil) {
               var obj = JSON.parse(hasil);
               if (obj.error == false) {
                  Swal.fire({
                     title: "Berhasil",
                     text: obj.msg,
                     icon: "success",
                     showConfirmButton: false,
                     timer: 2000
                  }).then(function() {
                     window.location.href = "<?= base_url('kategori') ?>"
                  })
               } else if (obj.error == true) {
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
      })
      $('#hapus-kategori').click(function(e) {
         e.preventDefault()
         Swal.fire({
            title: "Peringatan",
            text: "Yakin menghapus data kategori?",
            icon: "warning",
            showCancelButton: true
         }).then((result) => {
            if (result.value) {
               $.ajax({
                  url: "<?= base_url('kategori') ?>/<?= $detailKategori->slug ?>",
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
                        }).then(function() {
                           history.back()
                        })
                     } else if (obj.error == 1) {
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
      $('.custom-file-label').text('<?= $detailKategori->foto ?>')
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