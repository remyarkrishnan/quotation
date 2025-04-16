
<div class="row" style="margin: 10px;">
    <div class="col-md-4 col-xs-12">
        <div class="lead-info-heading">
            <h4>
            RFQ No
            </h4>
        </div>
        <dl>
            <dd class="tw-text-neutral-900 tw-mt-1 lead-name">
                <?=  $lead_req[0]['rfq_no']; ?>
            </dd>
        </dl>
    </div>
    <div class="col-md-4 col-xs-12">
        <div class="lead-info-heading">
            <h4>
                Date
            </h4>
        </div>
        <dl>
            <dd class="tw-text-neutral-900 tw-mt-1 lead-name">
                <?= date('F j, Y', strtotime($lead_req[0]['date'])); ?>
            </dd>
        </dl>
    </div>
    <div class="col-md-4 col-xs-12">
        <div class="lead-info-heading">
            <h4>
            Open Till
            </h4>
        </div>
        <dl>
            <dd class="tw-text-neutral-900 tw-mt-1 lead-name">
                <?= date('F j, Y', strtotime($lead_req[0]['open_till'])); ?>
            </dd>
        </dl>
    </div>

</div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Product Name</th>
      <th scope="col">Description</th>
      <th scope="col">Unit</th>
      <th scope="col">Qty</th>
      <th scope="col">Link</th>
      <th scope="col">User Assigned</th>
    </tr>
  </thead>
  <tbody>
    
    
    <?php
    $i=1;
    foreach ($lead_req as $key ) {
    ?>
        <tr>
            <th scope="row"><?php echo $i; ?></th>
            <td><?php echo $key['product_name'];?></td>
            <td><?php echo $key['description'];?></td>
            <td><?php
                switch ($key['unit']) {
                    case 1:
                        echo _l('quantity_as_qty');
                        break;
                    case 2:
                        echo _l('quantity_as_hours');
                        break;
                    case 3:
                        echo _l('estimate_table_quantity_heading')."/"._l('estimate_table_hours_heading');
                        break;
                    default:
                        echo ''; // optional
                }
                ?>
            </td>
            <td><?php echo $key['qty'];?></td>
            <td><?php echo $key['link'];?></td>
            <td><?php echo $key['firstname']." ".$key['lastname'];?></td>
        </tr>
    <?php
    $i++;
    }
    ?>
  </tbody>
</table>