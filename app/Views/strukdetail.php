<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<div class="card">
   <div class="card-header border-transparent">
      <button id="print" class="btn btn-secondary text-bold"><i class="fa fa-print mr-1"></i>PRINT</button>
   </div>
   <div id="struk" class="card-body">
      <style>
         .table th,
         .table td {
            border: 0 !important;
         }
      </style>
      <h4 class="d-inline">FK#<?= $data[0]['faktur'] ?></h4>
      <h5 class="float-right mb-4"><?= $data[0]['created_at'] ?></h5>
      
      <div class="table-responsive">
         <table class="table">
            <tbody>
               <?php
               helper('number');
               $total_harga = 0;
               foreach ($data as $row) :
                  $total_harga = $total_harga + $row['subtotal'];
               ?>
                  <tr>
                     <td><?= $row['nama_produk'] ?></td>
                     <td><?= $row['kuantitas'] ?></td>
                     <td class="text-right"><?= number_to_currency($row['harga'], 'IDR', 'id_ID') ?></td>
                     <td class="text-right"><?= number_to_currency($row['subtotal'], 'IDR', 'id_ID') ?></td>
                  </tr>
               <?php
               endforeach;
               $total_bayar = $total_harga + $data[0]['fee'];
               $sisa = $data[0]['pay'] - $total_bayar;
               ?>
            </tbody>
            <tfoot class="text-bold">
               <tr>
                  <td colspan="3">Total Harga</td>
                  <td class="text-right"><?= number_to_currency($total_harga, 'IDR', 'id_ID') ?></td>
               </tr>
               <tr>
                  <td colspan="3">Fee</td>
                  <td class="text-right"><?= number_to_currency($data[0]['fee'], 'IDR', 'id_ID') ?></td>
               </tr>
               <tr>
                  <td colspan="3">Total Bayar</td>
                  <td class="text-right text-danger h5"> <?= number_to_currency($total_bayar, 'IDR', 'id_ID') ?></td>
               </tr>
               <tr>
                  <td colspan="3">Uang diterima</td>
                  <td class="text-right text-danger h5"><?= number_to_currency($data[0]['pay'], 'IDR', 'id_ID') ?></td>
               </tr>
               <tr>
                  <td colspan="3">Uang sisa</td>
                  <td class="text-right h5"><?= number_to_currency($sisa, 'IDR', 'id_ID') ?></td>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('template/plugins/print-pdf/printThis.js') ?>"></script>
<script>
   $(document).ready(function() {
      $('#print').click(function() {
         $('#struk').printThis()
      })
   })
</script>

<?= $this->endSection() ?>