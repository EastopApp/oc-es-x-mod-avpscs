<?php 

/*
 * OpenCart Color/Size Attribute Variant Product Switcher extension.
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

// catalog/controller/extension/module/avpscs.php
 
class ControllerExtensionModuleAvpscs extends Controller {

  private $error = array(); 

  private function log($msg,$forceLog=false) {
    if (($this->config->get('module_avpscs_log')) || ($forceLog)) {
      $log = new Log('avpscs.log');
      $log->write($msg);
    }
  }
  
  public function index() { 
    $this->log('avpscs extension being called'); // avpscs will not called, should never occur
  }

  public function edit_product_page(&$route = '', &$data = array(), &$output = '') {
    $this->log('edit_product_page being called');
    if (!$this->config->get('module_avpscs_status')) {
      $this->log('avpscs not enabled');
      return null;
    }
    if (!$this->config->get('module_avpscs_agcolor')) {
      $this->log('avpscs color attribute group not set');
      return null;
    }
    if (!$this->config->get('module_avpscs_agsize')) {
      $this->log('avpscs size attribute group not set');
      return null;
    }
    
    $lcid   = $this->config->get('config_language_id');
    $this->log('language id: ' . $lcid);
    
    if (isset($this->request->get['product_id'])) {
      $this->load->language('extension/module/avpscs'); //--- Load language ---
      $data['text_variants'] = $this->language->get('text_variants');
      
      $data['avpscs_attribute_group_color'] = $this->config->get('module_avpscs_agcolor');
      $this->log('avpscs_attribute_group_color = ' . $data['avpscs_attribute_group_color']);
      $data['avpscs_attribute_group_size']  = $this->config->get('module_avpscs_agsize');
      $this->log('avpscs_attribute_group_size = ' . $data['avpscs_attribute_group_size']);
      
      $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
      if ($product_info) {

        $this->log('product_info: ' . json_encode($product_info));
        $product_id = intval($this->request->get['product_id'] ?? 0);
            
        if ($product_id) {
          $product_model = $product_info['model'];
          $this->log('product_model: ' . $product_model);
          $attributes = $this->model_catalog_product->getProductAttributes($product_id);
          $this->log('$attributes: ' . json_encode($attributes));

          if ($attributes) {
            // Display Variants before <div id="product">
            $find = '<div id="product">';
            $replace = '<div id="avpscs"><ul class="list-unstyled"><li>' . $data['text_variants'] . ': <select id="avpscs_select" name="avpscs_select" onchange="load_product_variant()">';
            $query = $this->db->query(
              "
              select p.product_id, p.model, pd.name, pd.description, a.attribute_group_id, pa.attribute_id, ad.name attribute_name
              from " . DB_PREFIX . "product p left join " . DB_PREFIX . "product_description pd on p.product_id = pd.product_id left join " . DB_PREFIX . "product_attribute pa on p.product_id = pa.product_id left join " . DB_PREFIX . "attribute a on pa.attribute_id = a.attribute_id left join " . DB_PREFIX . "attribute_description ad on a.attribute_id = ad.attribute_id left join " . DB_PREFIX . "attribute_group ag on a.attribute_group_id = ag.attribute_group_id left join " . DB_PREFIX . "attribute_group_description agd on ag.attribute_group_id = agd.attribute_group_id
              where p.model = '$product_model' and p.status = 1 and pd.language_id = $lcid and pa.language_id = $lcid and ad.language_id = $lcid and agd.language_id = $lcid
              "
            );
            if (!$query->num_rows) {
              $this->log("no variants found for product [$product_id]");
              return null;
            }
            $variants = array();
            foreach ($query->rows as $row) {
              if ($row['attribute_group_id'] === (int)$data['avpscs_attribute_group_color']) {
                $variants[$row['product_id']]['id'] = $row['product_id'];
                $variants[$row['product_id']]['color'] = $row['attribute_name'];
              }
              if ($row['attribute_group_id'] === (int)$data['avpscs_attribute_group_size']) {
                $variants[$row['product_id']]['id'] = $row['product_id'];
                $variants[$row['product_id']]['size'] = $row['attribute_name'];
              }
            }
            $this->log('variants: ' . json_encode($variants));          
            foreach ($variants as $variant) {
              if ( isset($variant['color']) && isset($variant['size']) ) {
                $selected = '';
                if ($variant['id'] === $product_info['product_id']) {
                  $selected = ' selected="selected"';
                }
                $replace = $replace . '<option title="#" value=' . $variant['id'] . $selected . '>'. $variant['color'] . '-'. $variant['size'] . '</option>';
              }
            }
            $replace = $replace . '</select></li></ul></div>' . $find;
            $output = str_replace($find, $replace, $output);

            // inject code Before closing tag
            $find = '</head>';
            $replace = '<script type="text/javascript">' . "var load_product_variant = function() { $(location).attr('href', 'index.php?route=product/product&product_id='+$('#avpscs :selected').val()); } </script>" . $find;
            $output = str_replace($find, $replace, $output);
          }
          else {
            $this->log('product [' . $product_id . '] does not have attributes');
          }
        }
      }
    }
  }
}