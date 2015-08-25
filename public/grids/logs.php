<script>

  $(function()
  {
    var last_clicked_id = 0;

    var lastSel = 0;

    jQuery("#grid_logs").jqGrid({
        url:'public/grids/logs_xml.php',
        datatype: 'xml',
        mtype: 'GET',
        colNames:[
        'ACTION', 
        'USER_ID', 
        'USERNAME', 
        'CLIENT IP', 
        'PLATFORM', 
        'DATE', 
        'ACTION'
        ],
        colModel :[ 
          {name:'act',index:'act', width:2,sortable:false, search: false},
          {name:'userid', index:'userid', width:2, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'username', index:'username', width:2, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'ip', index:'ip', width:2, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'platform', index:'platform', width:2, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'date', index:'date', width:2, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'action', index:'action', width:10, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true}
        ],
        width: 1300,
        height: 400,
        pager: '#nav_logs',
        rowNum:30,
        rowList:[10,20,30,40,50,100,200,300,400,500],
        sortname: 'id',
        sortorder: 'desc',
        gridComplete: function()
        {
          var ids = jQuery("#grid_logs").jqGrid('getDataIDs');
          for(var i=0;i < ids.length;i++)
          {
            var id = ids[i];
            edit = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_logs').editGridRow('"+id+"', {width:300}); jQuery('#grid_logs').trigger('reloadGrid');\"><span class='ui-icon ui-icon-pencil'></span></button>"; 
            del = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_logs').delGridRow('"+id+"'); jQuery('#grid_logs').trigger('reloadGrid');\"><span class='ui-icon ui-icon-trash'></span></button>";
            save = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_logs').saveRow('"+id+"'); jQuery('#grid_logs').trigger('reloadGrid');\" ><span class='ui-icon ui-icon-check'></span></button>"; 
            jQuery("#grid_logs").jqGrid('setRowData',ids[i],{act:edit+del+save});
          }
        },
        editurl: "public/grids/logs_manipulate.php",
        viewrecords: true,
        gridview: true,
        caption: 'logs',
        multiselect:true,
        onSelectRow: function(id)
        {
         if(id && id!==lastSel)
         { 
            jQuery('#grid_logs').restoreRow(lastSel); 
            lastSel=id; 
         }
         jQuery('#grid_logs').editRow(id);
       }
    });

  jQuery("#grid_logs").jqGrid('navGrid','#nav_logs',{edit:true, add:true, del:true}).
    navButtonAdd('#nav_logs',{
       caption:"Delete Selected", 
       buttonicon:"ui-icon-add", 
       onClickButton: function(){
          var ids = jQuery("#grid_logs").jqGrid('getGridParam','selarrrow');
          if(ids.length > 0)
          {
            if(confirm("Delete selected records?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_delete.php",
                data: {ids:ids, what:"log"},
                success: function(result)
                {
                    if(result == "success")
                    {
                        //playSound("positive");
                        jQuery("#grid_logs").trigger("reloadGrid");
                        return false;
                    }
                    else
                    {
                        //playSound("negative");
                        bootbox.alert(result);
                        return false;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    //playSound("negative");
                    bootbox.alert("error");
                    return false;
                }
              });
            }
          }
          else
          {
            //playSound("negative");
            bootbox.alert("please select atleast one");
          }
          return false;
       },
       position:"last"
    });
});

</script>

<table id="grid_logs"><tr><td/></tr></table> 
<div id="nav_logs"></div>