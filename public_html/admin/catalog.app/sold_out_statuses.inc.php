<?php
  if (!isset($_GET['page'])) $_GET['page'] = 1;
?>
<div style="float: right;"><?php echo functions::form_draw_link_button(document::link('', array('doc' => 'edit_sold_out_status'), true), language::translate('title_create_new_status', 'Create New Status'), '', 'add'); ?></div>
<h1 style="margin-top: 0px;"><?php echo $app_icon; ?> <?php echo language::translate('title_sold_out_statuses', 'Sold Out Statuses'); ?></h1>

<?php echo functions::form_draw_form_begin('sold_out_statuses_form', 'post'); ?>
<table width="100%" align="center" class="dataTable">
  <tr class="header">
    <th><?php echo functions::draw_fonticon('fa-check-square-o fa-fw checkbox-toggle'); ?></th>
    <th><?php echo language::translate('title_id', 'ID'); ?></th>
    <th width="100%"><?php echo language::translate('title_name', 'Name'); ?></th>
    <th><?php echo language::translate('title_orderable', 'Orderable'); ?></th>
    <th>&nbsp;</th>
  </tr>
<?php

  $sold_out_status_query = database::query(
    "select sos.id, sos.orderable, sosi.name from ". DB_TABLE_SOLD_OUT_STATUSES ." sos
    left join ". DB_TABLE_SOLD_OUT_STATUSES_INFO ." sosi on (sos.id = sosi.sold_out_status_id and sosi.language_code = '". language::$selected['code'] ."')
    order by sosi.name asc;"
  );

  if (database::num_rows($sold_out_status_query) > 0) {
    
    if ($_GET['page'] > 1) database::seek($sold_out_status_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));
    
    $page_items = 0;
    while ($sold_out_status = database::fetch($sold_out_status_query)) {
?>
  <tr class="row">
    <td><?php echo functions::form_draw_checkbox('delivery_statuses['. $sold_out_status['id'] .']', $sold_out_status['id']); ?></td>
    <td><?php echo $sold_out_status['id']; ?></td>
    <td><a href="<?php echo document::href_link('', array('doc' => 'edit_sold_out_status', 'sold_out_status_id' => $sold_out_status['id']), true); ?>"><?php echo $sold_out_status['name']; ?></a></td>
    <td style="text-align: center;"><?php echo !empty($sold_out_status['orderable']) ? 'x' : ''; ?></td>
    <td style="text-align: right;"><a href="<?php echo document::href_link('', array('doc' => 'edit_sold_out_status', 'sold_out_status_id' => $sold_out_status['id']), true); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
  </tr>
<?php
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }
?>
  <tr class="footer">
    <td colspan="5"><?php echo language::translate('title_sold_out_statuses', 'Sold Out Statuses'); ?>: <?php echo database::num_rows($sold_out_status_query); ?></td>
  </tr>
</table>

<script>
  $(".dataTable .checkbox-toggle").click(function() {
    $(this).closest("form").find(":checkbox").each(function() {
      $(this).attr('checked', !$(this).attr('checked'));
    });
    $(".dataTable .checkbox-toggle").attr("checked", true);
  });

  $('.dataTable tr').click(function(event) {
    if ($(event.target).is('input:checkbox')) return;
    if ($(event.target).is('a, a *')) return;
    if ($(event.target).is('th')) return;
    $(this).find('input:checkbox').trigger('click');
  });
</script>
<?php
  echo functions::form_draw_form_end();
  
  echo functions::draw_pagination(ceil(database::num_rows($sold_out_status_query)/settings::get('data_table_rows_per_page')));
?>