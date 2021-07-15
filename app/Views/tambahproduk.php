<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<div class="row">
   <div class="col">
      <div class="card">
         <div class="card-body">
            <form id="tambah-produk" action="">
               <div class="row">
                  <div class="col-lg-8">
                     <div class="form-group">
                        <label for="nama-produk">Nama Produk</label>
                        <input name="nama_produk" type="text" class="form-control" id="nama-produk" placeholder="Nama produk" autofocus required>
                     </div>
                     <div class="form-group">
                        <label>Kategori Produk</label>
                        <select class="form-control" name="kategori" required>
                           <option value="" selected disabled>Pilih Kategori</option>
                           <?php
                           foreach ($kategori as $row) {
                           ?>
                              <option value="<?= $row->id ?>"><?= $row->nama_kategori ?></option>
                           <?php
                           }
                           ?>
                        </select>
                     </div>
                     <div class="form-group">
                        <label for="harga">Harga</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                           </div>
                           <input name="harga" type="text" class="form-control" id="harga" placeholder="100.000" required>
                        </div>
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
                  </div>
                  <div class="col-lg-4">
                     <div class="form-group col-12">
                        <img id="preview" src="" width="100%">
                     </div>
                  </div>
                  <div class="col-lg-8">
                     <button class="btn btn-primary float-right text-bold"><i class="fa fa-check-circle mr-1"></i>SIMPAN</button>
                  </div>
               </div>
            </form>
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
      $('#tambah-produk').submit(function(e) {
         e.preventDefault()
         var data = new FormData($(this)[0]);
         $.ajax({
            url: "<?= base_url('tambahproduk') ?>",
            type: "post",
            dataType: "json",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
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
                     window.location.href = "<?= base_url('produk') ?>"
                  })
               }
            }
         })
         return false
      })
   })
</script>

<?= $this->endSection() ?>