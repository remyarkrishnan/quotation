<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
        <span>
            Lead Requirements
        </span>
        
    </h4>
    <div class="panel_s">
        <div class="panel-body">
            
                
                <div class="row">
                    <div class="col-md-6 ">
                        <?= render_input('lead_req_rfq_no', 'RFQ No', "", 'text', ""); ?>
                    </div>
                    <div class="col-md-6">
                        <?= render_date_input('lead_req_date', 'Date', ""); ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        echo render_date_input('lead_req_open_till', 'Open Till', ""); ?>
                    </div>
                </div>
            </div>
            <div class="table-responsive s_table">
        </div>
    </div>
</div>
<!-- <div class="d-flex justify-content-end w-100 align-items-end">
    <a href="#" >
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
    </a>
</div> -->

<div class="clearfix " style="width: 100%;padding:10px ">

<a href="#" class="!tw-px-0 tw-group !tw-text-white hover:tw-bg-red-700 active:tw-bg-red-900 pull-right del_lead_req">
    <span class="tw-rounded-full tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-red-700 active:tw-bg-red-900" style="background-color: red; margin-right: 2px;">
        <i class="fa-solid fa-trash fa-lg"></i>
    </span>
</a>

    <a href="#" class="!tw-px-0 tw-group !tw-text-white pull-right add_lead_req" >
        <span class="tw-rounded-full tw-bg-primary-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700" style="margin-right:2px">
            <i class="fa-regular fa-plus fa-lg"></i>
        </span>
    </a>

    
</div>


<table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop lead_req_tbl">
    <thead>
        <tr>
            <th></th>
            <th>Product</th>
            <th>Description</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Image</th>
            <th>Link</th>
            <th>Assign To</th>
        </tr>
    </thead>
    <tbody>
        <tr id="tr_1" data-tr-no="1">
            <td>
                <input type="checkbox" name="check_1" id="check_1" >
            </td>
            <td>
                <?= render_input('product_name_1', '', ""); ?>
            </td>
            <td>
                <?= render_textarea('description_1', '', ""); ?>
            </td>
            <td>
                <div class="form-group" app-field-wrapper="product_name">
                    <select class="form-control" name="lead_req_unit_1" id="unit_1">
                        <option value="1" ><?= _l('quantity_as_qty'); ?></option>
                        <option value="2" ><?= _l('quantity_as_hours'); ?></option>
                        <option value="3" ><?= _l('estimate_table_quantity_heading'); ?>/<?= _l('estimate_table_hours_heading'); ?></option>
                    </select>
                </div>
            </td>
            <td>
                <?= render_input('lead_req_qty_1', '', ""); ?>
            </td>
            <td class="req_prod_img">
            <div class="image-preview">
                        <img src="https://via.placeholder.com/100" alt="Preview">
                    </div>
                    <input type="file" class="file-input" accept="image/*" name="lead_req_image_1"  id="image_1">
                    <svg class="upload-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                        <path d="M440-200h80v-167l64 64 56-57-160-160-160 160 57 56 63-63v167ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/>
                    </svg>
            </td>
            <td><?= render_input('lead_req_link_1', '', ""); ?></td>
            <td>

                <?php
                
                // echo render_select('assigned_1', $members, ['staffid', ['firstname', 'lastname']], '', "", [])
                
                ?>
                <select name="lead_req_assigned_1" id="assigned_1" class="form-control">
                    <option value="">Select a Member</option> <!-- Optional default option -->
                    <?php foreach ($members as $member): ?>
                        <option value="<?= htmlspecialchars($member['staffid']) ?>">
                            <?= htmlspecialchars($member['firstname'] . ' ' . $member['lastname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </tbody>
    </thead>
</table>