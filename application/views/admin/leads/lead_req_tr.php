<tr id="tr_<?php echo $row_no; ?>" data-tr-no="<?php echo $row_no; ?>">
            <td>
                <input type="checkbox" name="check_<?php echo $row_no; ?>" id="check_<?php echo $row_no; ?>" >
            </td>
            <td>
                <?= render_input('lead_req_product_name_'.$row_no, '', ""); ?>
            </td>
            <td>
                <?= render_textarea('lead_req_description_'.$row_no, '', ""); ?>
            </td>
            <td>
                <div class="form-group" app-field-wrapper="product_name">
                    <select class="form-control" name="lead_req_unit_".$row_no id="unit_".$row_no>
                        <option value="1" ><?= _l('quantity_as_qty'); ?></option>
                        <option value="2" ><?= _l('quantity_as_hours'); ?></option>
                        <option value="3" ><?= _l('estimate_table_quantity_heading'); ?>/<?= _l('estimate_table_hours_heading'); ?></option>
                    </select>
                </div>
            </td>
            <td>
                <?= render_input('lead_req_qty_'.$row_no, '', ""); ?>
            </td>
            <td class="req_prod_img">
            <div class="image-preview">
                        <img src="https://via.placeholder.com/100" alt="Preview">
                    </div>
                    <input type="file" class="file-input" accept="image/*" name="lead_req_image_".$row_no  id="image_".$row_no >
                    <svg class="upload-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                        <path d="M440-200h80v-167l64 64 56-57-160-160-160 160 57 56 63-63v167ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/>
                    </svg>
            </td>
            <td><?= render_input('lead_req_link_'.$row_no, '', ""); ?></td>
            <td>

            <select name="lead_req_assigned_".$row_no id="assigned_".$row_no class="form-control">
                    <option value="">Select a Member</option> <!-- Optional default option -->
                    <?php foreach ($members as $member): ?>
                        <option value="<?= htmlspecialchars($member['staffid']) ?>">
                            <?= htmlspecialchars($member['firstname'] . ' ' . $member['lastname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>