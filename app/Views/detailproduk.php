<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<div class="row">
   <div class="col-12 col-sm-5 mb-3">
      <h3 class="d-inline-block d-sm-none"><?= $data->nama_produk ?></h3>
      <div class="col-12">
         <a href="<?= base_url('assets/img') ?>/<?= $data->foto ?>" target="_blank">
            <img id="preview" src="<?= base_url('assets/img') ?>/<?= $data->foto ?>" class="product-image">
         </a>
      </div>
   </div>
   <div class="col-12 col-sm-7">
      <div class="row">
         <div class="col-12">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Detail Produk</h3>
               </div>
               <!-- /.card-header -->
               <!-- form start -->
               <form id="ubah-produk" class="form-horizontal">
                  <div class="card-body">
                     <div class="form-group row">
                        <label for="nama-produk" class="col-sm-2 col-form-label">Nama produk</label>
                        <div class="col-sm-10">
                           <input type="hidden" name="id_produk" value="<?= $data->id ?>">
                           <input type="hidden" name="old_foto" value="<?= $data->foto ?>">
                           <input type="text" class="form-control" id="nama-produk" name="nama_produk" placeholder="Nama produk" value="<?= $data->nama_produk ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="kategori-produk" class="col-sm-2 col-form-label">Kategori Produk</label>
                        <div class="col-sm-10">
                           <select id="kategori-produk" class="form-control" name="kategori">
                              <?php foreach ($kategori as $row) : ?>
                                 <option value="<?= $row->id ?>"><?= $row->nama_kategori ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     </div>
                     <?php helper('number') ?>
                     <div class="form-group row">
                        <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                           <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                 <span class="input-group-text">Rp</span>
                              </div>
                              <input name="harga" type="text" class="form-control" id="harga" placeholder="100.000" value="<?= substr(number_to_currency($data->harga, 'IDR', 'id_ID'), 4) ?>" required>
                           </div>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="stok" class="col-sm-2 col-form-label">Stok</label>
                        <div class="col-sm-10">
                           <input type="number" class="form-control" id="stok" placeholder="10" value="<?= $data->stok ?>" disabled>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="foto-produk" class="col-sm-2">Foto produk</label>
                        <div class="col-sm-10">
                           <div class="input-group">
                              <div class="custom-file">
                                 <input name="foto" type="file" class="custom-file-input" id="foto-produk" accept="image/jpg, image/jpeg">
                                 <label class="custom-file-label" for="foto-produk">Pilih foto</label>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                     <button type="submit" class="btn btn-primary text-bold float-right"><i class="fa fa-save mr-1"></i>SIMPAN</button>
                     <button type="button" id="hapus-produk" class="btn btn-danger text-bold"><i class="fa fa-trash-alt mr-1"></i>HAPUS</button>
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
      $(document).on('keyup', '#harga', function() {
         var num_to_string = $(this).val().replace(/[^\d]/g, '').toString(),
            split = num_to_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
         if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
         }
         rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
         $(this).val(rupiah)
      })
      $('#ubah-produk').submit(function(e) {
         e.preventDefault()
         var data = new FormData($(this)[0]);
         $.ajax({
            url: "<?= base_url('produk') ?>/<?= $data->id ?>",
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
                     window.location.href = "<?= base_url('produk') ?>"
                  })
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
      })
      $('#hapus-produk').click(function(e) {
         e.preventDefault()
         Swal.fire({
            title: "Peringatan",
            text: "Yakin menghapus data produk?",
            icon: "warning",
            showCancelButton: true
         }).then((result) => {
            if (result.value) {
               $.ajax({
                  url: "<?= base_url('produk') ?>/<?= $data->id ?>",
                  type: "delete",
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
                           history.back()
                        })
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
      $('.custom-file-label').text('<?= $data->foto ?>')
      $(document).on('change', '#foto-produk', function() {
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