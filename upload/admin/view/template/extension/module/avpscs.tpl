<?php echo $header; ?><?php echo $column_left; ?>
<!--
/*
 * OpenCart Color/Size Attributes Variant Product Switcher extension.
 *
 * Copyright (c) 2021 Alex AU <alex.au@live.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 */
-->
<div id="content">
  <!-- Admin page header -->
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <!-- Submit part -->
        <button type="submit" form="form-avpscs" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
        <!-- End Submit part -->
      <h1><?php echo $heading_title; ?></h1>
      
      <!-- Breadcrumb part -->
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <!-- End Breadcrumb part -->
    </div>
  </div>
  <!-- End Breadcrumb part -->
  
  <!-- form part -->
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-avpscs" class="form-horizontal">

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-attribute-group-color"><?php echo $entry_attribute_group_color; ?></label>
            <div class="col-sm-10">
              <select name="module_avpscs_agcolor" id="input-attribute-group-color" class="form-control">
                <option value="0"></option>
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <?php if ($attribute_group['attribute_group_id'] == $module_avpscs_agcolor) { ?>
                <option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_attribute_group_color) { ?>
              <div class="text-danger"><?php echo $error_attribute_group_color; ?></div>
              <?php } ?>
            </div>
          </div>
        
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-attribute-group-size"><?php echo $entry_attribute_group_size; ?></label>
            <div class="col-sm-10">
              <select name="module_avpscs_agsize" id="input-attribute-group-size" class="form-control">
                <option value="0"></option>
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <?php if ($attribute_group['attribute_group_id'] == $module_avpscs_agsize) { ?>
                <option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_attribute_group_size) { ?>
              <div class="text-danger"><?php echo $error_attribute_group_size; ?></div>
              <?php } ?>
            </div>
          </div>
        
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="module_avpscs_status" id="input-status" class="form-control">
                <?php if ($module_avpscs_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-log"><?php echo $entry_log; ?></label>
            <div class="col-sm-10">
              <select name="module_avpscs_log" id="input-log" class="form-control">
                <?php if ($module_avpscs_log) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
                   
        </form>
      </div>
    </div>
  </div>
  <!-- end form part -->
</div>
<?php echo $footer; ?>