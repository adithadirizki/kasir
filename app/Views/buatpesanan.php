<?= $this->extend('template/template') ?>

<?= $this->section('header') ?>

<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
</link>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
   <div class="col">
      <div class="card">
         <div class="card-body">
            <form id="struk" action="">
               <div class="table-responsive">
                  <table id="daftar-produk" class="table table-bordered text-center w-100">
                     <thead class="bg-gradient-navy">
                        <tr>
                           <th>Produk</th>
                           <th>Stok</th>
                           <th>Harga</th>
                           <th>Kuantitas</th>
                           <th>Subtotal</th>
                           <th>Aksi</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        helper('number');
                        $total_harga = 0;
                        foreach ($data as $row) :
                           $total_harga = $total_harga + $row['harga'];
                        ?>
                           <tr>
                              <td class="text-left">
                                 <input type="hidden" name="id[]" value="<?= $row['id'] ?>">
                                 <input type="text" name="nama_produk[]" class="border-0 bg-transparent" style="outline: 0;" value="<?= $row['nama_produk'] ?>" readonly>
                              </td>
                              <td><?= $row['stok'] ?></td>
                              <td>
                                 <input type="text" name="harga[]" class="border-0 bg-transparent text-center harga" style="outline: 0;" value="<?= number_to_currency($row['harga'], 'IDR', 'id_ID') ?>" data-id="<?= $row['harga'] ?>" readonly>
                              </td>
                              <td>
                                 <input type="number" name="qty[]" class="form-control qty" min="1" max="<?= $row['stok'] ?>" value="1" placeholder="Kuantias" required>
                              </td>
                              <td>
                                 <input type="text" name="subtotal[]" class="border-0 bg-transparent text-center h5 text-danger subtotal" style="outline: 0;" value="<?= number_to_currency($row['harga'], 'IDR', 'id_ID') ?>" data-id="<?= $row['harga'] ?>" readonly>
                              </td>
                              <td>
                                 <button class="btn btn-xs btn-danger text-bold hapus"><i class="fa fa-trash-alt mr-1"></i>BUANG</button>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
               <table class="table">
                  <tbody>
                     <tr>
                        <th>Total harga:</th>
                        <td data-id="<?= $row['harga'] ?>">
                           <input type="text" name="total_harga" class="border-0 bg-transparent h5 total-harga" style="outline: 0;" value="<?= number_to_currency($total_harga, 'IDR', 'id_ID') ?>" readonly>
                        </td>
                     </tr>
                     <tr>
                        <th>Fee:</th>
                        <td>
                           <div class="input-group">
                              <div class="input-group-prepend">
                                 <span class="input-group-text">Rp</span>
                              </div>
                              <input type="text" name="fee" class="form-control text-bold fee" placeholder="">
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <th>Total bayar:</th>
                        <td>
                           <h5 class="total-bayar text-danger m-0" data-id="<?= $total_harga ?>"><?= number_to_currency($total_harga, 'IDR', 'id_ID') ?></h5>
                        </td>
                     </tr>
                     <tr>
                        <th>Uang diterima:</th>
                        <td>
                           <div class="input-group">
                              <div class="input-group-prepend">
                                 <span class="input-group-text">Rp</span>
                              </div>
                              <input type="text" name="pay" class="form-control text-bold pay" placeholder="">
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <th>Uang sisa:</th>
                        <td>
                           <input type="text" name="sisa" class="border-0 bg-transparent h5 text-danger sisa" style="outline: 0;" value="- <?= number_to_currency($total_harga, 'IDR', 'id_ID') ?>" data-id="-<?= $total_harga ?>" readonly>
                        </td>
                     </tr>
                  </tbody>
               </table>
               <button id="cetak-struk" type="button" class="btn btn-primary float-right"><i class="fa fa-clipboard-list mr-2"></i>Simpan Struk</button>
            </form>
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
      function loadProduk(data = "") {
         $.ajax({
            url: "/listproduk",
            type: "POST",
            data: "keyword=" + data,
            beforeSend: function() {
               // 
            },
            success: function(hasil) {
               var obj = JSON.parse(hasil),
                  view = '',
                  i = 1;
               $('#pager').html(obj['pager'])
               $.each(obj, function(index, val) {
                  var harga = val['harga'],
                     sisa = harga.length % 3,
                     rupiah = harga.substr(0, sisa),
                     ribuan = harga.substr(sisa).match(/\d{3}/g);
                  if (ribuan) {
                     separator = sisa ? '.' : ''
                     rupiah += separator + ribuan.join('.')
                  }
                  var date = new Date(val['updated_at']),
                     options = {
                        weekday: 'short',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                     },
                     formatDate = date.toLocaleDateString(['id'], options);
                  view += '<div class="col-6 col-sm-4 col-md-3 p-3 boxed">';
                  view += '<div class="box">';
                  view += '<a href="<?= base_url('detailproduk') ?>/' + val['id'] + '">';
                  view += '<img src="<?= base_url('assets/img') ?>/' + val['foto'] + '">';
                  view += '</a>';
                  view += '</div>';
                  view += '<a href="<?= base_url('detailproduk') ?>/' + val['id'] + '" class="text">';
                  view += '<h6>' + val['nama_produk'] + '</h6>';
                  view += '</a>';
                  view += '<div class="d-flex justify-content-between">';
                  view += '<h5 class="mb-1 text-danger">Rp ' + rupiah + '</h5>';
                  view += '<b><span class="badge badge-pill badge-warning mb-3">' + val['stok'] + '</span></b>';
                  view += '</div>';
                  view += '<button class="btn btn-sm btn-flat w-100 btn-outline-primary add-order" data-id="' + val['id'] + '"><i class="fa fa-key mr-1"></i>Pilih</button>';
                  view += '</div>';
               })
               $('#daftar-produk').html(view)
            }
         })
      }
      $(document).on('click', '.add-order', function(e) {
         e.preventDefault()
         var id_produk = $(this).data('id');
         var nama_produk = $(this).parents('.box h6').text();
         if ($(this).hasClass('active')) {
            $(this).removeClass('active')
         } else {
            $(this).addClass('active')
         }
         var data = '';
         data += '<tr data-id="' + id_produk + '">'
         data += '<td>1</td>'
         data += '<td>' + nama_produk + '</td>'
         data += '<td>5</td>'
         data += '<td data-id="5000">Rp 5.000</td>'
         data += '<td>'
         data += '<input name="qty" type="number" class="form-control qty" min="1" max="5" value="1" autofocus required>'
         data += '</td>'
         data += '<td>'
         data += '<h5 class="subtotal" data-id="5000">Rp 5.000</h5>'
         data += '</td>'
         data += '</tr>'
         $('#data-pesanan').append(data)
      })

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

      function live_quantity(obj) {
         var harga = $(obj).closest('tr').find('td:eq(2) .harga').data('id');
         var subtotal = eval($(obj).val() * harga);
         $(obj).closest('tr').find('td:eq(4) .subtotal').val(to_rupiah(subtotal))
         $(obj).closest('tr').find('td:eq(4) .subtotal').attr('data-id', subtotal)
         var total_harga = 0;
         $('.subtotal').each(function() {
            // alert(Number($(this).attr('data-id')))
            total_harga = total_harga + Number($(this).attr('data-id'))
         })
         $('.total-harga').val(to_rupiah(total_harga))
         $('.total-harga').attr('data-id', total_harga)
         return total_harga
      }

      $(document).on('click', '#cetak-struk', save_struk)

      function save_struk() {
         var data = $('#struk').serialize();
         var subtotal = [];
         document.querySelectorAll('.subtotal').forEach((obj) => {
            subtotal.push($(obj).html().replace(/[^\d]/g, ''))
         })
         $.ajax({
            url: "<?= base_url('simpanstruk') ?>",
            type: "post",
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
                     window.location.href = "<?= base_url('riwayatpesanan') ?>"
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

      function live_fee() {
         var obj = $('.fee');
         var fee = obj.val().replace(/\./g, '');
         var total_harga = $('.total-harga').val().replace(/[^\d]/g, '');
         var total_bayar = Number(fee) + Number(total_harga);
         $('.total-bayar').attr('data-id', total_bayar)
         $('.total-bayar').text(to_rupiah(total_bayar))
         live_to_rupiah(obj)
         return total_bayar
      }

      function live_pay(total_bayar) {
         var obj = $('.pay');
         var pay = Number(obj.val().replace(/\./g, ''));
         var sisa = pay - total_bayar;
         $('.sisa').attr('data-id', sisa);
         $('.sisa').val(to_rupiah(sisa));
         if (pay < total_bayar) {
            sisa = (pay - total_bayar) * (-1);
            $('.sisa').val('- ' + to_rupiah(sisa));
         }
         live_to_rupiah(obj)
      }

      $(document).on('click', '.hapus', function() {
         $(this).parents('tr').empty()
         live_quantity($('.qty'))
         live_pay(live_fee())
      })
      $(document).on('keyup', '.qty', function() {
         live_quantity(this)
         live_pay(live_fee())
      })
      $(document).on('change', '.qty', function() {
         live_quantity(this)
         live_pay(live_fee())
      })
      $(document).on('keyup', '.fee', function() {
         live_pay(live_fee())
      })
      $(document).on('keyup', '.pay', function() {
         var total_bayar = $('.total-bayar').html().replace(/[^\d]/g, '');
         live_pay(total_bayar)
      })
   })
</script>

<?= $this->endSection() ?>